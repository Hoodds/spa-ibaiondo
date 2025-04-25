<?php
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

    public function update($id, $nombre, $email, $rol, $password = null) {
        $query = "UPDATE trabajadores SET nombre = ?, email = ?, rol = ?";
        $params = [$nombre, $email, $rol];

        if ($password) {
            $query .= ", contrasena = ?";
            $params[] = $password;
        }

        $query .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }
}