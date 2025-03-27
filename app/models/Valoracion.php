<?php
class Valoracion {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Obtener todas las valoraciones de un servicio
     */
    public function getByServicio($idServicio) {
        $stmt = $this->db->prepare("
            SELECT v.*, u.nombre as nombre_usuario
            FROM valoraciones v
            JOIN usuarios u ON v.id_usuario = u.id
            WHERE v.id_servicio = ?
            ORDER BY v.fecha_creacion DESC
        ");
        $stmt->execute([$idServicio]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener todas las valoraciones de un usuario
     */
    public function getByUsuario($idUsuario) {
        $stmt = $this->db->prepare("
            SELECT v.*, s.nombre as nombre_servicio
            FROM valoraciones v
            JOIN servicios s ON v.id_servicio = s.id
            WHERE v.id_usuario = ?
            ORDER BY v.fecha_creacion DESC
        ");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Verificar si un usuario ya ha valorado un servicio
     */
    public function existeValoracion($idUsuario, $idServicio) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM valoraciones 
            WHERE id_usuario = ? AND id_servicio = ?
        ");
        $stmt->execute([$idUsuario, $idServicio]);
        return (int)$stmt->fetchColumn() > 0;
    }
    
    /**
     * Crear una nueva valoración
     */
    public function crear($idUsuario, $idServicio, $puntuacion, $comentario) {
        // Verificar si ya existe una valoración
        if ($this->existeValoracion($idUsuario, $idServicio)) {
            return $this->actualizar($idUsuario, $idServicio, $puntuacion, $comentario);
        }
        
        $stmt = $this->db->prepare("
            INSERT INTO valoraciones (id_usuario, id_servicio, puntuacion, comentario)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$idUsuario, $idServicio, $puntuacion, $comentario]);
    }
    
    /**
     * Actualizar una valoración existente
     */
    public function actualizar($idUsuario, $idServicio, $puntuacion, $comentario) {
        $stmt = $this->db->prepare("
            UPDATE valoraciones 
            SET puntuacion = ?, comentario = ?, fecha_creacion = CURRENT_TIMESTAMP
            WHERE id_usuario = ? AND id_servicio = ?
        ");
        return $stmt->execute([$puntuacion, $comentario, $idUsuario, $idServicio]);
    }
    
    /**
     * Eliminar una valoración
     */
    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM valoraciones WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Obtener la puntuación media de un servicio
     */
    public function getPuntuacionMedia($idServicio) {
        $stmt = $this->db->prepare("
            SELECT AVG(puntuacion) as media, COUNT(*) as total
            FROM valoraciones
            WHERE id_servicio = ?
        ");
        $stmt->execute([$idServicio]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener las valoraciones más recientes (para la página principal)
     */
    public function getRecientes($limite = 5) {
        $stmt = $this->db->prepare("
            SELECT v.*, u.nombre as nombre_usuario, s.nombre as nombre_servicio
            FROM valoraciones v
            JOIN usuarios u ON v.id_usuario = u.id
            JOIN servicios s ON v.id_servicio = s.id
            ORDER BY v.fecha_creacion DESC
            LIMIT ?
        ");
        $stmt->execute([$limite]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

