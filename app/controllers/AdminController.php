<?php
class AdminController {
    private $usuarioModel;
    private $trabajadorModel;
    private $servicioModel;
    private $reservaModel;
    private $valoracionModel;
    
    public function __construct() {
        require_once BASE_PATH . '/app/models/Usuario.php';
        require_once BASE_PATH . '/app/models/Trabajador.php';
        require_once BASE_PATH . '/app/models/Servicio.php';
        require_once BASE_PATH . '/app/models/Reserva.php';
        require_once BASE_PATH . '/app/models/Valoracion.php';
        
        $this->usuarioModel = new Usuario();
        $this->trabajadorModel = new Trabajador();
        $this->servicioModel = new Servicio();
        $this->reservaModel = new Reserva();
        $this->valoracionModel = new Valoracion();
        
        // Verificar si es administrador
        Auth::checkAdmin();
    }
    
    public function dashboard() {
        // Obtener estadísticas para el dashboard
        $stats = [
            'usuarios' => count($this->usuarioModel->getAll()),
            'trabajadores' => count($this->trabajadorModel->getAll()),
            'servicios' => count($this->servicioModel->getAll()),
            'reservas' => count($this->reservaModel->getAll()),
            'valoraciones' => $this->valoracionModel->getEstadisticas()
        ];
        
        include BASE_PATH . '/app/views/layouts/admin.php';
        include BASE_PATH . '/app/views/admin/dashboard.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function listarUsuarios() {
        $usuarios = $this->usuarioModel->getAll();
        
        include BASE_PATH . '/app/views/layouts/admin.php';
        include BASE_PATH . '/app/views/admin/usuarios.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function listarTrabajadores() {
        $trabajadores = $this->trabajadorModel->getAll();
        
        include BASE_PATH . '/app/views/layouts/admin.php';
        include BASE_PATH . '/app/views/admin/trabajadores.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function listarServicios() {
        $servicios = $this->servicioModel->getAll();
        
        // Para cada servicio, obtener su puntuación media
        foreach ($servicios as $key => $servicio) {
            $puntuacion = $this->valoracionModel->getPuntuacionMedia($servicio['id']);
            $servicios[$key]['puntuacion_media'] = $puntuacion['media'] ? round($puntuacion['media'], 1) : 0;
            $servicios[$key]['total_valoraciones'] = $puntuacion['total'];
        }
        
        include BASE_PATH . '/app/views/layouts/admin.php';
        include BASE_PATH . '/app/views/admin/servicios.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function listarReservas() {
        $reservas = $this->reservaModel->getAll();
        
        include BASE_PATH . '/app/views/layouts/admin.php';
        include BASE_PATH . '/app/views/admin/reservas.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function listarValoraciones() {
        $valoraciones = $this->valoracionModel->getAll();
        
        include BASE_PATH . '/app/views/layouts/admin.php';
        include BASE_PATH . '/app/views/admin/valoraciones.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function valoracionesPendientes() {
        $valoraciones = $this->valoracionModel->getByEstado('pendiente');
        
        include BASE_PATH . '/app/views/layouts/admin.php';
        include BASE_PATH . '/app/views/admin/valoraciones_pendientes.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function aprobarValoracion($id) {
        $valoracion = $this->valoracionModel->getById($id);
        
        if (!$valoracion) {
            $_SESSION['error'] = 'La valoración no existe';
            Helper::redirect('admin/valoraciones');
            return;
        }
        
        if ($this->valoracionModel->cambiarEstado($id, 'aprobada')) {
            $_SESSION['success'] = 'Valoración aprobada correctamente';
        } else {
            $_SESSION['error'] = 'Error al aprobar la valoración';
        }
        
        Helper::redirect('admin/valoraciones/pendientes');
    }
    
    public function rechazarValoracion($id) {
        $valoracion = $this->valoracionModel->getById($id);
        
        if (!$valoracion) {
            $_SESSION['error'] = 'La valoración no existe';
            Helper::redirect('admin/valoraciones');
            return;
        }
        
        if ($this->valoracionModel->cambiarEstado($id, 'rechazada')) {
            $_SESSION['success'] = 'Valoración rechazada correctamente';
        } else {
            $_SESSION['error'] = 'Error al rechazar la valoración';
        }
        
        Helper::redirect('admin/valoraciones/pendientes');
    }
    
    public function eliminarValoracion($id) {
        $valoracion = $this->valoracionModel->getById($id);
        
        if (!$valoracion) {
            $_SESSION['error'] = 'La valoración no existe';
            Helper::redirect('admin/valoraciones');
            return;
        }
        
        if ($this->valoracionModel->eliminar($id)) {
            $_SESSION['success'] = 'Valoración eliminada correctamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar la valoración';
        }
        
        Helper::redirect('admin/valoraciones');
    }

    public function crearUsuario() {
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$nombre || !$email || !$password) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            Helper::redirect('admin/usuarios');
            return;
        }

        // Verifica si el email ya existe
        if ($this->usuarioModel->emailExists($email)) {
            $_SESSION['error'] = 'El email ya está registrado';
            Helper::redirect('admin/usuarios');
            return;
        }

        // Crea el usuario
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $result = $this->usuarioModel->crear([
            'nombre' => $nombre,
            'email' => $email,
            'password' => $hash
        ]);

        if ($result) {
            $_SESSION['success'] = 'Usuario creado correctamente';
        } else {
            $_SESSION['error'] = 'Error al crear el usuario';
        }
        Helper::redirect('admin/usuarios');
    }

    public function crearTrabajador() {
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $rol = trim($_POST['rol'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$nombre || !$email || !$rol || !$password) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            Helper::redirect('admin/trabajadores');
            return;
        }

        // Verifica si el email ya existe
        if ($this->trabajadorModel->emailExists($email)) {
            $_SESSION['error'] = 'El email ya está registrado';
            Helper::redirect('admin/trabajadores');
            return;
        }

        // Crea el trabajador
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $result = $this->trabajadorModel->crear([
            'nombre' => $nombre,
            'email' => $email,
            'rol' => $rol,
            'password' => $hash
        ]);

        if ($result) {
            $_SESSION['success'] = 'Trabajador creado correctamente';
        } else {
            $_SESSION['error'] = 'Error al crear el trabajador';
        }
        Helper::redirect('admin/trabajadores');
    }

    public function crearServicio() {
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $duracion = intval($_POST['duracion'] ?? 0);
        $precio = floatval($_POST['precio'] ?? 0);

        if (!$nombre || !$descripcion || !$duracion || !$precio) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            Helper::redirect('admin/servicios');
            return;
        }

        $result = $this->servicioModel->create($nombre, $descripcion, $duracion, $precio);

        if ($result) {
            $_SESSION['success'] = 'Servicio creado correctamente';
        } else {
            $_SESSION['error'] = 'Error al crear el servicio';
        }
        Helper::redirect('admin/servicios');
    }
}

