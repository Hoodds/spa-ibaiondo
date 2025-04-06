<?php
/**
 * HomeController
 *
 * Este archivo contiene la clase HomeController, que gestiona las operaciones relacionadas con la página principal y la página de contacto.
 *
 * @package SpaIbaiondo
 * @autor Lander Ribera
 * @version 1.0.0
 *
 * @description
 * Este controlador maneja las acciones relacionadas con la visualización de la página principal y la página de contacto.
 *
 * @purpose
 * Proporcionar la lógica necesaria para mostrar información general del spa y gestionar la navegación inicial.
 *
 * @class HomeController
 * - Métodos principales:
 *   - index(): Muestra la página principal.
 *   - contacto(): Muestra la página de contacto.
 *
 * @relationships
 * - Relación con el modelo Servicio: Obtiene servicios destacados para la página principal.
 * - Relación con las vistas: Renderiza las vistas ubicadas en `app/views/home`.
 */

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