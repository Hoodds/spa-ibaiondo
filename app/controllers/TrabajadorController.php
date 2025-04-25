<?php
class TrabajadorController {
    private $trabajadorModel;
    private $reservaModel;
    private $valoracionModel;
    
    public function __construct() {
        require_once BASE_PATH . '/app/models/Trabajador.php';
        require_once BASE_PATH . '/app/models/Reserva.php';
        require_once BASE_PATH . '/app/models/Valoracion.php';
        
        $this->trabajadorModel = new Trabajador();
        $this->reservaModel = new Reserva();
        $this->valoracionModel = new Valoracion();
    }
    
    public function login() {
        // Si ya está autenticado, redirigir al dashboard
        if (Auth::check() && isset($_SESSION['trabajador'])) {
            Helper::redirect('trabajador/dashboard');
        }
        
        // Procesar el formulario de login
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $_SESSION['error'] = 'Todos los campos son obligatorios';
                Helper::redirect('trabajador/login');
                return;
            }
            
            // Intentar login
            $trabajador = $this->trabajadorModel->login($email, $password);
            
            if ($trabajador) {
                // Guardar datos del trabajador en la sesión
                $_SESSION['trabajador'] = true;
                $_SESSION['trabajador_id'] = $trabajador['id'];
                $_SESSION['trabajador_nombre'] = $trabajador['nombre'];
                $_SESSION['trabajador_rol'] = $trabajador['rol'];
                
                // Si es admin, redirigir al panel de admin
                if ($trabajador['rol'] === 'admin') {
                    Auth::login($trabajador, true);
                    Helper::redirect('admin');
                    return;
                }
                
                Helper::redirect('trabajador/dashboard');
            } else {
                $_SESSION['error'] = 'Credenciales incorrectas';
                Helper::redirect('trabajador/login');
            }
        }
        
        // Mostrar formulario de login
        include BASE_PATH . '/app/views/layouts/main.php';
        include BASE_PATH . '/app/views/trabajadores/login.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function dashboard() {
        // Verificar si es trabajador
        $this->checkTrabajador();
        
        // Obtener datos del trabajador
        $trabajador = $this->trabajadorModel->getById($_SESSION['trabajador_id']);
        
        // Obtener reservas asignadas al trabajador
        $reservas = $this->reservaModel->getByTrabajador($_SESSION['trabajador_id']);
        
        // Obtener valoraciones de los servicios realizados por el trabajador
        $valoraciones = $this->valoracionModel->getByTrabajador($_SESSION['trabajador_id']);
        
        include BASE_PATH . '/app/views/layouts/trabajador.php';
        include BASE_PATH . '/app/views/trabajadores/dashboard.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function misReservas() {
        // Verificar si es trabajador
        $this->checkTrabajador();
        
        // Obtener reservas asignadas al trabajador
        $reservas = $this->reservaModel->getByTrabajador($_SESSION['trabajador_id']);
        
        include BASE_PATH . '/app/views/layouts/trabajador.php';
        include BASE_PATH . '/app/views/trabajadores/mis_reservas.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function misValoraciones() {
        // Verificar si es trabajador
        $this->checkTrabajador();
        
        // Obtener valoraciones de los servicios realizados por el trabajador
        $valoraciones = $this->valoracionModel->getByTrabajador($_SESSION['trabajador_id']);
        
        include BASE_PATH . '/app/views/layouts/trabajador.php';
        include BASE_PATH . '/app/views/trabajadores/mis_valoraciones.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function logout() {
        // Eliminar datos del trabajador de la sesión
        unset($_SESSION['trabajador']);
        unset($_SESSION['trabajador_id']);
        unset($_SESSION['trabajador_nombre']);
        unset($_SESSION['trabajador_rol']);
        
        // Si también es usuario, mantener la sesión de usuario
        if (!Auth::check()) {
            session_destroy();
        }
        
        Helper::redirect('');
    }
    
    private function checkTrabajador() {
        if (!isset($_SESSION['trabajador']) || !$_SESSION['trabajador']) {
            $_SESSION['error'] = 'Debes iniciar sesión como trabajador';
            Helper::redirect('trabajador/login');
            exit;
        }
    }

    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $rol = $_POST['rol'];
            $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;

            // Validar los datos
            if (empty($id) || empty($nombre) || empty($email) || empty($rol)) {
                $_SESSION['error'] = 'Todos los campos son obligatorios.';
                Helper::redirect('/admin/trabajadores');
                return;
            }

            // Actualizar en la base de datos
            $trabajadorModel = new Trabajador();
            $result = $trabajadorModel->update($id, $nombre, $email, $rol, $password);

            if ($result) {
                $_SESSION['success'] = 'Trabajador actualizado correctamente.';
            } else {
                $_SESSION['error'] = 'Error al actualizar el trabajador.';
            }

            Helper::redirect('/admin/trabajadores');
        }
    }
}

