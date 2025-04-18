<?php
/**
 * ReservaController
 *
 * Este archivo contiene la clase ReservaController, que gestiona las operaciones relacionadas con las reservas.
 *
 * @package SpaIbaiondo
 * @autor Lander Ribera
 * @version 1.0.0
 *
 * @description
 * Este controlador maneja las acciones relacionadas con las reservas, como la creación, cancelación y visualización.
 *
 * @purpose
 * Proporcionar la lógica necesaria para gestionar las reservas realizadas por los usuarios.
 *
 * @class ReservaController
 * - Métodos principales:
 *   - misReservas(): Muestra las reservas del usuario autenticado.
 *   - showCrear(): Muestra el formulario para crear una reserva.
 *   - crear(): Procesa la creación de una nueva reserva.
 *   - cancelar(): Procesa la cancelación de una reserva.
 *
 * @relationships
 * - Relación con el modelo Reserva: Utiliza el modelo Reserva para interactuar con la base de datos.
 * - Relación con el modelo Servicio: Obtiene información de los servicios relacionados con las reservas.
 * - Relación con las vistas: Renderiza las vistas ubicadas en `app/views/reservas`.
 */

class ReservaController {
    private $reservaModel;
    private $servicioModel;
    
    public function __construct() {
        require_once BASE_PATH . '/app/models/Reserva.php';
        require_once BASE_PATH . '/app/models/Servicio.php';
        $this->reservaModel = new Reserva();
        $this->servicioModel = new Servicio();
    }
    
    public function misReservas() {
        // Verificar si el usuario está autenticado
        Auth::checkAuth();
        
        // Obtener reservas del usuario
        $reservas = $this->reservaModel->getByUsuario(Auth::id());
        
        include BASE_PATH . '/app/views/layouts/main.php';
        include BASE_PATH . '/app/views/reservas/mis_reservas.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function showCrear($idServicio) {
        // Verificar si el usuario está autenticado
        Auth::checkAuth();
        
        // Obtener el servicio
        $servicio = $this->servicioModel->getById($idServicio);
        
        if (!$servicio) {
            $_SESSION['error'] = 'El servicio no existe';
            Helper::redirect('servicios');
            return;
        }
        
        include BASE_PATH . '/app/views/layouts/main.php';
        include BASE_PATH . '/app/views/reservas/crear.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function crear() {
        // Verificar si el usuario está autenticado
        Auth::checkAuth();
        
        // Validar datos del formulario
        $idServicio = $_POST['id_servicio'] ?? '';
        $idTrabajador = $_POST['id_trabajador'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $hora = $_POST['hora'] ?? '';
        
        if (empty($idServicio) || empty($idTrabajador) || empty($fecha) || empty($hora)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            Helper::redirect('reservas/crear/' . $idServicio);
            return;
        }
        
        // Crear la fecha y hora en formato MySQL
        $fechaHora = $fecha . ' ' . $hora . ':00';
        
        // Crear la reserva
        if ($this->reservaModel->create(Auth::id(), $idServicio, $idTrabajador, $fechaHora)) {
            $_SESSION['success'] = 'Reserva creada con éxito';
            Helper::redirect('reservas');
        } else {
            $_SESSION['error'] = 'Error al crear la reserva';
            Helper::redirect('reservas/crear/' . $idServicio);
        }
    }
    
    public function cancelar($id) {
        // Verificar si el usuario está autenticado
        Auth::checkAuth();
        
        // Obtener la reserva
        $reserva = $this->reservaModel->getById($id);
        
        if (!$reserva || $reserva['id_usuario'] != Auth::id()) {
            $_SESSION['error'] = 'No tienes permiso para cancelar esta reserva';
            Helper::redirect('reservas');
            return;
        }
        
        // Cancelar la reserva
        if ($this->reservaModel->updateEstado($id, 'cancelada')) {
            $_SESSION['success'] = 'Reserva cancelada con éxito';
        } else {
            $_SESSION['error'] = 'Error al cancelar la reserva';
        }
        
        Helper::redirect('reservas');
    }
    
    public function getDisponibilidad() {
        // Esta función se puede llamar vía AJAX
        header('Content-Type: application/json');
        
        $idServicio = $_GET['id_servicio'] ?? '';
        $fecha = $_GET['fecha'] ?? '';
        
        if (empty($idServicio) || empty($fecha)) {
            echo json_encode(['error' => 'Parámetros incompletos']);
            return;
        }
        
        $disponibilidad = $this->reservaModel->getDisponibilidad($idServicio, $fecha);
        echo json_encode($disponibilidad);
    }
}