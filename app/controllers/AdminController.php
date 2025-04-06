<?php
class AdminController {
    private $usuarioModel;
    private $trabajadorModel;
    private $servicioModel;
    private $reservaModel;
    
    public function __construct() {
        require_once BASE_PATH . '/app/models/Usuario.php';
        require_once BASE_PATH . '/app/models/Trabajador.php';
        require_once BASE_PATH . '/app/models/Servicio.php';
        require_once BASE_PATH . '/app/models/Reserva.php';
        
        $this->usuarioModel = new Usuario();
        $this->trabajadorModel = new Trabajador();
        $this->servicioModel = new Servicio();
        $this->reservaModel = new Reserva();
        
        // Verificar si el usuario es administrador
        $this->checkAdmin();
    }
    
    private function checkAdmin() {
        if (!isset($_SESSION['trabajador_id']) || !isset($_SESSION['trabajador_rol']) || $_SESSION['trabajador_rol'] !== 'admin') {
            $_SESSION['error'] = 'No tienes permisos para acceder a esta sección';
            Helper::redirect('trabajador/login');
            exit;
        }
    }
    
    public function dashboard() {
        // Obtener estadísticas para el dashboard
        $totalUsuarios = count($this->usuarioModel->getAll());
        $totalTrabajadores = count($this->trabajadorModel->getAll());
        $totalServicios = count($this->servicioModel->getAll());
        $totalReservas = count($this->reservaModel->getAll());
        
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
        // Esta función se implementará cuando se cree el sistema de valoraciones
        include BASE_PATH . '/app/views/layouts/admin.php';
        include BASE_PATH . '/app/views/admin/valoraciones.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function valoracionesPendientes() {
        // Esta función se implementará cuando se cree el sistema de valoraciones
        include BASE_PATH . '/app/views/layouts/admin.php';
        include BASE_PATH . '/app/views/admin/valoraciones_pendientes.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function aprobarValoracion($id) {
        // Esta función se implementará cuando se cree el sistema de valoraciones
        $_SESSION['success'] = 'Valoración aprobada correctamente';
        Helper::redirect('admin/valoraciones');
    }
    
    public function rechazarValoracion($id) {
        // Esta función se implementará cuando se cree el sistema de valoraciones
        $_SESSION['success'] = 'Valoración rechazada correctamente';
        Helper::redirect('admin/valoraciones');
    }
    
    public function eliminarValoracion($id) {
        // Esta función se implementará cuando se cree el sistema de valoraciones
        $_SESSION['success'] = 'Valoración eliminada correctamente';
        Helper::redirect('admin/valoraciones');
    }
}

