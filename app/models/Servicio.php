<?php
/**
 * Servicio
 *
 * Este archivo contiene la clase Servicio, que representa el modelo de datos para los servicios.
 *
 * @package SpaIbaiondo
 * @autor Lander Ribera
 * @version 1.0.0
 *
 * @description
 * Este modelo interactúa con la base de datos para realizar operaciones relacionadas con los servicios, como la creación, edición y obtención de información.
 *
 * @purpose
 * Proporcionar métodos para gestionar los datos de los servicios en la base de datos.
 *
 * @class Servicio
 * - Métodos principales:
 *   - getById(): Obtiene un servicio por su ID.
 *   - getAll(): Obtiene todos los servicios.
 *   - create(): Crea un nuevo servicio.
 *   - update(): Actualiza un servicio existente.
 *
 * @relationships
 * - Relación con el controlador ServicioController: Proporciona datos y lógica para las operaciones del controlador.
 * - Relación con la base de datos: Realiza consultas y operaciones en la tabla `servicios`.
 */

class Servicio {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM servicios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM servicios ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($nombre, $descripcion, $duracion, $precio) {
        $stmt = $this->db->prepare("INSERT INTO servicios (nombre, descripcion, duracion, precio) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nombre, $descripcion, $duracion, $precio]);
    }
    
    public function update($id, $nombre, $descripcion, $duracion, $precio) {
        $stmt = $this->db->prepare("UPDATE servicios SET nombre = ?, descripcion = ?, duracion = ?, precio = ? WHERE id = ?");
        return $stmt->execute([$nombre, $descripcion, $duracion, $precio, $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM servicios WHERE id = ?");
        return $stmt->execute([$id]);
    }
}