<?php
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

    public function update($id, $nombre, $email, $password = null) {
        $query = "UPDATE usuarios SET nombre = ?, email = ?";
        $params = [$nombre, $email];

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