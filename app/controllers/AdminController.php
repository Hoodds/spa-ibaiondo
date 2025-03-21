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
        
        // Verificar si es administrador
        Auth::checkAdmin();
    }
    
    public function dashboard() {
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
}