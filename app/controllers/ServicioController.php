<?php
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
        foreach ($servicios as $key => $servicio) {
            $puntuacion = $this->valoracionModel->getPuntuacionMedia($servicio['id']);
            $servicios[$key]['puntuacion_media'] = $puntuacion['media'] ? round($puntuacion['media'], 1) : 0;
            $servicios[$key]['total_valoraciones'] = $puntuacion['total'];
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

    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $duracion = $_POST['duracion'];
            $precio = $_POST['precio'];

            // Validar los datos
            if (empty($id) || empty($nombre) || empty($descripcion) || empty($duracion) || empty($precio)) {
                $_SESSION['error'] = 'Todos los campos son obligatorios.';
                Helper::redirect('/admin/servicios');
                return;
            }

            // Actualizar en la base de datos
            $servicioModel = new Servicio();
            $result = $servicioModel->update($id, $nombre, $descripcion, $duracion, $precio);

            if ($result) {
                $_SESSION['success'] = 'Servicio actualizado correctamente.';
            } else {
                $_SESSION['error'] = 'Error al actualizar el servicio.';
            }

            Helper::redirect('/admin/servicios');
        }
    }
}

