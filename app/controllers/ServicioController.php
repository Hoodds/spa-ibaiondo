<?php
class ServicioController {
    private $servicioModel;
    
    public function __construct() {
        require_once BASE_PATH . '/app/models/Servicio.php';
        $this->servicioModel = new Servicio();
    }
    
    public function listar() {
        // Obtener todos los servicios
        $servicios = $this->servicioModel->getAll();
        
        include BASE_PATH . '/app/views/layouts/main.php';
        include BASE_PATH . '/app/views/servicios/lista.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function mostrar($id) {
        // Obtener el servicio por ID
        $servicio = $this->servicioModel->getById($id);
        
        if (!$servicio) {
            // Si no existe, mostrar error 404
            header("HTTP/1.0 404 Not Found");
            include BASE_PATH . '/app/views/errors/404.php';
            return;
        }
        
        include BASE_PATH . '/app/views/layouts/main.php';
        include BASE_PATH . '/app/views/servicios/detalle.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
}