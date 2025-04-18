<?php
/**
 * ServicioController
 *
 * Este archivo contiene la clase ServicioController, que gestiona las operaciones relacionadas con los servicios.
 *
 * @package SpaIbaiondo
 * @autor Lander Ribera
 * @version 1.0.0
 *
 * @description
 * Este controlador maneja las acciones relacionadas con los servicios, como la visualización, creación y edición.
 *
 * @purpose
 * Proporcionar la lógica necesaria para gestionar los servicios ofrecidos por el spa.
 *
 * @class ServicioController
 * - Métodos principales:
 *   - listar(): Muestra la lista de servicios.
 *   - mostrar(): Muestra los detalles de un servicio.
 *
 * @relationships
 * - Relación con el modelo Servicio: Utiliza el modelo Servicio para interactuar con la base de datos.
 * - Relación con las vistas: Renderiza las vistas ubicadas en `app/views/servicios`.
 */

class ServicioController {
    private $servicioModel;
    private $valoracionModel;
    
    public function __construct() {
        require_once BASE_PATH . '/app/models/Servicio.php';
        require_once BASE_PATH . '/app/models/Valoracion.php';
        $this->servicioModel = new Servicio();
        $this->valoracionModel = new Valoracion();
    }
    
    public function listar() {
        // Obtener todos los servicios
        $servicios = $this->servicioModel->getAll();
        
        // Para cada servicio, obtener su puntuación media
        foreach ($servicios as &$servicio) {
            $puntuacion = $this->valoracionModel->getPuntuacionMedia($servicio['id']);
            $servicio['puntuacion_media'] = $puntuacion['media'] ? round($puntuacion['media'], 1) : 0;
            $servicio['total_valoraciones'] = $puntuacion['total'];
        }
        
        // Cargar la vista
        include BASE_PATH . '/app/views/layouts/main.php';
        include BASE_PATH . '/app/views/servicios/lista.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function mostrar($id) {
        // Obtener el servicio
        $servicio = $this->servicioModel->getById($id);
        
        if (!$servicio) {
            $_SESSION['error'] = 'El servicio no existe';
            Helper::redirect('servicios');
            return;
        }
        
        // Obtener valoraciones del servicio
        $valoraciones = $this->valoracionModel->getByServicio($id);
        
        // Obtener puntuación media
        $puntuacion = $this->valoracionModel->getPuntuacionMedia($id);
        $servicio['puntuacion_media'] = $puntuacion['media'] ? round($puntuacion['media'], 1) : 0;
        $servicio['total_valoraciones'] = $puntuacion['total'];
        
        // Verificar si el usuario ya ha valorado este servicio
        $usuarioHaValorado = false;
        $valoracionUsuario = null;
        
        if (Auth::check()) {
            $usuarioHaValorado = $this->valoracionModel->existeValoracion(Auth::id(), $id);
            
            // Si el usuario ha valorado, obtener su valoración
            if ($usuarioHaValorado) {
                foreach ($valoraciones as $val) {
                    if ($val['id_usuario'] == Auth::id()) {
                        $valoracionUsuario = $val;
                        break;
                    }
                }
            }
        }
        
        // Cargar la vista
        include BASE_PATH . '/app/views/layouts/main.php';
        include BASE_PATH . '/app/views/servicios/detalle.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function valorar($id) {
        // Verificar si el usuario está autenticado
        Auth::checkAuth();
        
        // Verificar si el servicio existe
        $servicio = $this->servicioModel->getById($id);
        
        if (!$servicio) {
            $_SESSION['error'] = 'El servicio no existe';
            Helper::redirect('servicios');
            return;
        }
        
        // Procesar el formulario de valoración
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $puntuacion = isset($_POST['puntuacion']) ? (int)$_POST['puntuacion'] : 0;
            $comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : '';
            
            // Validar datos
            if ($puntuacion < 1 || $puntuacion > 5) {
                $_SESSION['error'] = 'La puntuación debe estar entre 1 y 5';
                Helper::redirect('servicios/' . $id);
                return;
            }
            
            if (empty($comentario)) {
                $_SESSION['error'] = 'El comentario es obligatorio';
                Helper::redirect('servicios/' . $id);
                return;
            }
            
            // Guardar la valoración
            if ($this->valoracionModel->crear(Auth::id(), $id, $puntuacion, $comentario)) {
                $_SESSION['success'] = 'Valoración guardada correctamente';
            } else {
                $_SESSION['error'] = 'Error al guardar la valoración';
            }
            
            Helper::redirect('servicios/' . $id);
            return;
        }
        
        // Si no es POST, redirigir a la página del servicio
        Helper::redirect('servicios/' . $id);
    }
    
    public function misValoraciones() {
        // Verificar si el usuario está autenticado
        Auth::checkAuth();
        
        // Obtener valoraciones del usuario
        $valoraciones = $this->valoracionModel->getByUsuario(Auth::id());
        
        // Cargar la vista
        include BASE_PATH . '/app/views/layouts/main.php';
        include BASE_PATH . '/app/views/servicios/mis_valoraciones.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function eliminarValoracion($id) {
        // Verificar si el usuario está autenticado
        Auth::checkAuth();
        
        // Obtener la valoración
        $stmt = Database::getInstance()->getConnection()->prepare("
            SELECT * FROM valoraciones WHERE id = ?
        ");
        $stmt->execute([$id]);
        $valoracion = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$valoracion || $valoracion['id_usuario'] != Auth::id()) {
            $_SESSION['error'] = 'No tienes permiso para eliminar esta valoración';
            Helper::redirect('servicios/mis-valoraciones');
            return;
        }
        
        // Eliminar la valoración
        if ($this->valoracionModel->eliminar($id)) {
            $_SESSION['success'] = 'Valoración eliminada correctamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar la valoración';
        }
        
        Helper::redirect('servicios/mis-valoraciones');
    }
}

