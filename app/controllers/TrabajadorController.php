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
        ob_start();
        include BASE_PATH . '/app/views/trabajadores/login.php';
        $content = ob_get_clean();
        include BASE_PATH . '/app/views/layouts/trabajador.php';
    }
    
    public function dashboard() {
        // Verificar si es trabajador
        $this->checkTrabajador();
        
        // Obtener datos del trabajador
        $trabajador = $this->trabajadorModel->getById($_SESSION['trabajador_id']);
        
        if ($_SESSION['trabajador_rol'] === 'recepcionista') {
            // Para recepcionistas, mostrar todas las reservas y valoraciones
            $reservas = $this->reservaModel->getAll();
            $valoraciones = $this->valoracionModel->getAll();
            
            // Obtener servicios (para estadísticas)
            require_once BASE_PATH . '/app/models/Servicio.php';
            $servicioModel = new Servicio();
            $servicios = $servicioModel->getAll();
            
            ob_start();
            include BASE_PATH . '/app/views/trabajadores/dashboard_recepcionista.php';
            $content = ob_get_clean();
        } else {
            // Para otros trabajadores, mostrar solo sus datos
            $reservas = $this->reservaModel->getByTrabajador($_SESSION['trabajador_id']);
            $valoraciones = $this->valoracionModel->getByTrabajador($_SESSION['trabajador_id']);
            
            ob_start();
            include BASE_PATH . '/app/views/trabajadores/dashboard.php';
            $content = ob_get_clean();
        }
        
        include BASE_PATH . '/app/views/layouts/trabajador.php';
    }
    
    public function misReservas() {
        // Verificar si es trabajador
        $this->checkTrabajador();
        
        if ($_SESSION['trabajador_rol'] === 'recepcionista') {
            // Para recepcionistas, mostrar todas las reservas (como admin)
            require_once BASE_PATH . '/app/models/Servicio.php';
            $servicioModel = new Servicio();
            $servicios = $servicioModel->getAll();
            $trabajadores = $this->trabajadorModel->getAll();
            
            // Aplicar filtros si los hay
            $filtros = [
                'fecha' => $_GET['filtroFecha'] ?? null,
                'servicio' => $_GET['filtroServicio'] ?? null,
                'trabajador' => $_GET['filtroTrabajador'] ?? null,
                'estado' => $_GET['filtroEstado'] ?? null,
            ];
            $reservas = $this->reservaModel->getFiltered($filtros);
            
            // Usar la vista de reservas para recepcionistas
            ob_start();
            include BASE_PATH . '/app/views/trabajadores/reservas_recepcionista.php';
            $content = ob_get_clean();
        } else {
            // Para otros trabajadores, mostrar solo sus reservas
            $reservas = $this->reservaModel->getByTrabajador($_SESSION['trabajador_id']);
            
            ob_start();
            include BASE_PATH . '/app/views/trabajadores/mis_reservas.php';
            $content = ob_get_clean();
        }
        
        include BASE_PATH . '/app/views/layouts/trabajador.php';
    }
    
    public function misValoraciones() {
        // Verificar si es trabajador
        $this->checkTrabajador();
        
        if ($_SESSION['trabajador_rol'] === 'recepcionista') {
            // Para recepcionistas, mostrar todas las valoraciones
            $valoraciones = $this->valoracionModel->getAll();
            
            ob_start();
            include BASE_PATH . '/app/views/trabajadores/valoraciones_recepcionista.php';
            $content = ob_get_clean();
        } else {
            // Para otros trabajadores, mostrar solo sus valoraciones
            $valoraciones = $this->valoracionModel->getByTrabajador($_SESSION['trabajador_id']);
            
            ob_start();
            include BASE_PATH . '/app/views/trabajadores/mis_valoraciones.php';
            $content = ob_get_clean();
        }
        
        include BASE_PATH . '/app/views/layouts/trabajador.php';
    }
    
    public function logout() {
        // Eliminar todas las variables de sesión relacionadas con el trabajador
        unset($_SESSION['trabajador']);
        unset($_SESSION['trabajador_id']);
        unset($_SESSION['trabajador_nombre']);
        unset($_SESSION['trabajador_rol']);
        
        // Como medida adicional, podemos regenerar el ID de sesión
        session_regenerate_id(true);
        
        // Redirigir al login
        $_SESSION['success'] = 'Has cerrado sesión correctamente.';
        Helper::redirect('trabajador/login');
    }
    
    private function checkTrabajador() {
        if (!isset($_SESSION['trabajador']) || !$_SESSION['trabajador'] || 
            !isset($_SESSION['trabajador_id']) || !isset($_SESSION['trabajador_rol'])) {
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

            // Cuando el trabajador edita su perfil
            if (!empty($_POST['password'])) {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            } else {
                $password = null;
            }
            $this->trabajadorModel->update($id, $nombre, $email, $rol, $password);

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

    public function completarReserva($id) {
        $this->checkTrabajador();

        // Obtener la reserva
        $reserva = $this->reservaModel->getById($id);

        // Validar que la reserva exista
        if (!$reserva) {
            $_SESSION['error'] = 'Reserva no encontrada.';
            Helper::redirect('trabajador/reservas');
            return;
        }

        // Si es recepcionista, puede confirmar cualquier reserva
        // Si es otro trabajador, solo las suyas
        if ($_SESSION['trabajador_rol'] !== 'recepcionista' && 
            $reserva['id_trabajador'] != $_SESSION['trabajador_id']) {
            $_SESSION['error'] = 'No tienes permiso para gestionar esta reserva.';
            Helper::redirect('trabajador/reservas');
            return;
        }

        // Solo permitir completar si está pendiente
        if ($reserva['estado'] !== 'pendiente') {
            $_SESSION['error'] = 'Solo puedes completar reservas pendientes.';
            Helper::redirect('trabajador/reservas');
            return;
        }

        // Actualizar el estado de la reserva a 'confirmada'
        $resultado = $this->reservaModel->actualizarEstado($id, 'confirmada');

        if ($resultado) {
            $_SESSION['success'] = 'Reserva marcada como completada.';
        } else {
            $_SESSION['error'] = 'No se pudo completar la reserva.';
        }

        Helper::redirect('trabajador/reservas');
    }

    public function cancelarReserva($id) {
        $this->checkTrabajador();

        // Obtener la reserva
        $reserva = $this->reservaModel->getById($id);

        // Validar que la reserva exista
        if (!$reserva) {
            $_SESSION['error'] = 'Reserva no encontrada.';
            Helper::redirect('trabajador/reservas');
            return;
        }

        // Si es recepcionista, puede cancelar cualquier reserva
        // Si es otro trabajador, solo las suyas
        if ($_SESSION['trabajador_rol'] !== 'recepcionista' && 
            $reserva['id_trabajador'] != $_SESSION['trabajador_id']) {
            $_SESSION['error'] = 'No tienes permiso para gestionar esta reserva.';
            Helper::redirect('trabajador/reservas');
            return;
        }

        // Solo permitir cancelar si está pendiente
        if ($reserva['estado'] !== 'pendiente') {
            $_SESSION['error'] = 'Solo puedes cancelar reservas pendientes.';
            Helper::redirect('trabajador/reservas');
            return;
        }

        // Actualizar el estado de la reserva a 'cancelada'
        $resultado = $this->reservaModel->actualizarEstado($id, 'cancelada');

        if ($resultado) {
            $_SESSION['success'] = 'Reserva cancelada correctamente.';
        } else {
            $_SESSION['error'] = 'No se pudo cancelar la reserva.';
        }

        Helper::redirect('trabajador/reservas');
    }
}

