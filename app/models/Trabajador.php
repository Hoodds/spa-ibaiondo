<?php
/**
 * Trabajador
 *
 * Este archivo contiene la clase Trabajador, que representa el modelo de datos para los trabajadores.
 *
 * @package SpaIbaiondo
 * @autor Lander Ribera
 * @version 1.0.0
 *
 * @description
 * Este modelo interactúa con la base de datos para realizar operaciones relacionadas con los trabajadores, como la autenticación y obtención de información.
 *
 * @purpose
 * Proporcionar métodos para gestionar los datos de los trabajadores en la base de datos.
 *
 * @class Trabajador
 * - Métodos principales:
 *   - login(): Verifica las credenciales del trabajador.
 *   - getById(): Obtiene un trabajador por su ID.
 *   - getAll(): Obtiene todos los trabajadores.
 *
 * @relationships
 * - Relación con el controlador TrabajadorController: Proporciona datos y lógica para las operaciones del controlador.
 * - Relación con la base de datos: Realiza consultas y operaciones en la tabla `trabajadores`.
 */

class Trabajador {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT id, nombre, email, rol FROM trabajadores WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT id, nombre, email, rol FROM trabajadores ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getByRol($rol) {
        $stmt = $this->db->prepare("SELECT id, nombre, email, rol FROM trabajadores WHERE rol = ? ORDER BY nombre");
        $stmt->execute([$rol]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT id, nombre, email, contrasena, rol FROM trabajadores WHERE email = ?");
        $stmt->execute([$email]);
        $trabajador = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Nota: En producción, usar password_verify() con contraseñas hasheadas
        if ($trabajador && $password === $trabajador['contrasena']) {
            unset($trabajador['contrasena']); // No devolver la contraseña
            return $trabajador;
        }
        
        return false;
    }
}