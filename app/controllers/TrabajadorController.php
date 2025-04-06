<?php
class TrabajadorController {
    private $trabajadorModel;
    private $reservaModel;
    
    public function __construct() {
        require_once BASE_PATH . '/app/models/Trabajador.php';
        require_once BASE_PATH . '/app/models/Reserva.php';
        $this->trabajadorModel = new Trabajador();
        $this->reservaModel = new Reserva();
    }
    
    public function login() {
        // Si es una petición GET, mostrar el formulario de login
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            include BASE_PATH . '/app/views/layouts/main.php';
            include BASE_PATH . '/app/views/trabajadores/login.php';
            include BASE_PATH . '/app/views/layouts/footer.php';
            return;
        }
        
        // Si es una petición POST, procesar el login
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
            // Iniciar sesión como trabajador
            $_SESSION['trabajador_id'] = $trabajador['id'];
            $_SESSION['trabajador_nombre'] = $trabajador['nombre'];
            $_SESSION['trabajador_email'] = $trabajador['email'];
            $_SESSION['trabajador_rol'] = $trabajador['rol'];
            $_SESSION['is_trabajador'] = true;
            
            // Redirigir según el rol
            if ($trabajador['rol'] === 'admin') {
                Helper::redirect('admin');
            } else {
                Helper::redirect('trabajador/dashboard');
            }
        } else {
            $_SESSION['error'] = 'Credenciales incorrectas';
            Helper::redirect('trabajador/login');
        }
    }
    
    public function dashboard() {
        // Verificar si el trabajador está autenticado
        $this->checkTrabajadorAuth();
        
        // Obtener reservas del trabajador
        $reservas = $this->reservaModel->getByTrabajador($_SESSION['trabajador_id']);
        
        // Filtrar reservas para hoy
        $hoy = date('Y-m-d');
        $reservasHoy = array_filter($reservas, function($reserva) use ($hoy) {
            return date('Y-m-d', strtotime($reserva['fecha_hora'])) === $hoy;
        });
        
        include BASE_PATH . '/app/views/layouts/main.php';
        include BASE_PATH . '/app/views/trabajadores/dashboard.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function misReservas() {
        // Verificar si el trabajador está autenticado
        $this->checkTrabajadorAuth();
        
        // Obtener reservas del trabajador
        $reservas = $this->reservaModel->getByTrabajador($_SESSION['trabajador_id']);
        
        include BASE_PATH . '/app/views/layouts/main.php';
        include BASE_PATH . '/app/views/trabajadores/mis_reservas.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function misValoraciones() {
        // Verificar si el trabajador está autenticado
        $this->checkTrabajadorAuth();
        
        // Esta función se implementará cuando se cree el sistema de valoraciones
        include BASE_PATH . '/app/views/layouts/main.php';
        include BASE_PATH . '/app/views/trabajadores/mis_valoraciones.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function logout() {
        // Cerrar sesión de trabajador
        unset($_SESSION['trabajador_id']);
        unset($_SESSION['trabajador_nombre']);
        unset($_SESSION['trabajador_email']);
        unset($_SESSION['trabajador_rol']);
        unset($_SESSION['is_trabajador']);
        
        Helper::redirect('');
    }
    
    private function checkTrabajadorAuth() {
        if (!isset($_SESSION['is_trabajador']) || $_SESSION['is_trabajador'] !== true) {
            $_SESSION['error'] = 'Debes iniciar sesión como trabajador para acceder a esta página';
            Helper::redirect('trabajador/login');
            exit;
        }
    }
}

