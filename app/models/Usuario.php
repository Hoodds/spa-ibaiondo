<?php
/**
 * Usuario
 *
 * Este archivo contiene la clase Usuario, que representa el modelo de datos para los usuarios.
 *
 * @package SpaIbaiondo
 * @autor Lander Ribera
 * @version 1.0.0
 *
 * @description
 * Este modelo interactúa con la base de datos para realizar operaciones relacionadas con los usuarios, como el registro, inicio de sesión y obtención de información.
 *
 * @purpose
 * Proporcionar métodos para gestionar los datos de los usuarios en la base de datos.
 *
 * @class Usuario
 * - Métodos principales:
 *   - login(): Verifica las credenciales del usuario.
 *   - register(): Registra un nuevo usuario en la base de datos.
 *   - emailExists(): Comprueba si un email ya está registrado.
 *   - getById(): Obtiene un usuario por su ID.
 *
 * @relationships
 * - Relación con el controlador UsuarioController: Proporciona datos y lógica para las operaciones del controlador.
 * - Relación con la base de datos: Realiza consultas y operaciones en la tabla `usuarios`.
 */

class Usuario {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT id, nombre, email, fecha_registro FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT id, nombre, email, fecha_registro FROM usuarios ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT id, nombre, email, contrasena FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Nota: En producción, usar password_verify() con contraseñas hasheadas
        // Para este ejemplo, comparamos directamente ya que en la BD están en texto plano
        if ($user && $password === $user['contrasena']) {
            unset($user['contrasena']); // No devolver la contraseña
            return $user;
        }
        
        return false;
    }
    
    public function register($nombre, $email, $password) {
        // En producción, usar password_hash() para hashear contraseñas
        // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, email, contrasena) VALUES (?, ?, ?)");
        return $stmt->execute([$nombre, $email, $password]);
    }
    
    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return (int)$stmt->fetchColumn() > 0;
    }
}