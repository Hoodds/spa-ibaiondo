<?php
/**
 * MensajeContacto
 *
 * Este archivo contiene la clase MensajeContacto, que representa el modelo de datos para los mensajes de contacto.
 *
 * @package SpaIbaiondo
 * @autor Lander Ribera
 * @version 1.0.0
 *
 * @description
 * Este modelo interactúa con la base de datos para realizar operaciones relacionadas con los mensajes de contacto, como el almacenamiento y la recuperación.
 *
 * @purpose
 * Proporcionar métodos para gestionar los datos de los mensajes de contacto en la base de datos.
 *
 * @class MensajeContacto
 * - Métodos principales:
 *   - guardar(): Guarda un mensaje de contacto en la base de datos.
 *   - obtenerTodos(): Obtiene todos los mensajes de contacto.
 *
 * @relationships
 * - Relación con el controlador ContactoController: Proporciona datos y lógica para las operaciones del controlador.
 * - Relación con la base de datos: Realiza consultas y operaciones en la tabla `mensajes_contacto`.
 */

class MensajeContacto {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function guardar($data) {
        $stmt = $this->db->prepare("
            INSERT INTO mensajes_contacto (nombre, email, asunto, mensaje) 
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['nombre'],
            $data['email'],
            $data['asunto'],
            $data['mensaje']
        ]);
    }

    public function obtenerTodos() {
        $stmt = $this->db->query("SELECT * FROM mensajes_contacto ORDER BY fecha DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}