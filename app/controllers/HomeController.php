<?php
class HomeController {
    private $servicioModel;
    
    public function __construct() {
        require_once BASE_PATH . '/app/models/Servicio.php';
        $this->servicioModel = new Servicio();
    }
    
    public function index() {
        // Obtener servicios destacados para mostrar en la página principal
        $serviciosDestacados = $this->servicioModel->getAll();
        
        // Cargar la vista
        include BASE_PATH . '/app/views/layouts/main.php';
        include BASE_PATH . '/app/views/home/index.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function contacto() {
        // Cargar la vista de contacto
        include BASE_PATH . '/app/views/layouts/main.php';
        include BASE_PATH . '/app/views/home/contacto.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
}