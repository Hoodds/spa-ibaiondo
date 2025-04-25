<?php
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

    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $estado = $_POST['estado'];
            $fecha = $_POST['fecha'];
            $hora = $_POST['hora'];
            $idTrabajador = $_POST['id_trabajador'];

            // Validar los datos
            if (empty($id) || empty($estado) || empty($fecha) || empty($hora) || empty($idTrabajador)) {
                $_SESSION['error'] = 'Todos los campos son obligatorios.';
                Helper::redirect('/admin/reservas');
                return;
            }

            // Crear la fecha y hora en formato MySQL
            $fechaHora = $fecha . ' ' . $hora . ':00';

            // Actualizar en la base de datos
            $reservaModel = new Reserva();
            $result = $reservaModel->update($id, $estado, $fechaHora, $idTrabajador);

            if ($result) {
                $_SESSION['success'] = 'Reserva actualizada correctamente.';
            } else {
                $_SESSION['error'] = 'Error al actualizar la reserva.';
            }

            Helper::redirect('/admin/reservas');
        }
    }
}