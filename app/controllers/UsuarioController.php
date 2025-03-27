<?php
class UsuarioController {
    private $usuarioModel;
    private $reservaModel;
    
    public function __construct() {
        require_once BASE_PATH . '/app/models/Usuario.php';
        require_once BASE_PATH . '/app/models/Reserva.php';
        $this->usuarioModel = new Usuario();
        $this->reservaModel = new Reserva();
    }
    
    public function showLogin() {
        // Si ya está autenticado, redirigir al perfil
        if (Auth::check()) {
            Helper::redirect('perfil');
        }
        
        include BASE_PATH . '/app/views/layouts/main.php';
        include BASE_PATH . '/app/views/usuarios/login.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function login() {
        // Validar datos del formulario
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            Helper::redirect('login');
        }
        
        // Intentar login
        $user = $this->usuarioModel->login($email, $password);
        
        if ($user) {
            Auth::login($user);
            Helper::redirect('perfil');
        } else {
            $_SESSION['error'] = 'Credenciales incorrectas';
            Helper::redirect('login');
        }
    }
    
    public function showRegistro() {
        // Si ya está autenticado, redirigir al perfil
        if (Auth::check()) {
            Helper::redirect('perfil');
        }
        
        include BASE_PATH . '/app/views/layouts/main.php';
        include BASE_PATH . '/app/views/usuarios/registro.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function registro() {
        // Validar datos del formulario
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        
        if (empty($nombre) || empty($email) || empty($password) || empty($password_confirm)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            Helper::redirect('registro');
        }
        
        if ($password !== $password_confirm) {
            $_SESSION['error'] = 'Las contraseñas no coinciden';
            Helper::redirect('registro');
        }
        
        // Verificar si el email ya existe
        if ($this->usuarioModel->emailExists($email)) {
            $_SESSION['error'] = 'El email ya está registrado';
            Helper::redirect('registro');
        }
        
        // Registrar usuario
        if ($this->usuarioModel->register($nombre, $email, $password)) {
            $_SESSION['success'] = 'Registro exitoso. Ahora puedes iniciar sesión';
            Helper::redirect('login');
        } else {
            $_SESSION['error'] = 'Error al registrar el usuario';
            Helper::redirect('registro');
        }
    }
    
    public function perfil() {
        // Verificar si el usuario está autenticado
        Auth::checkAuth();
        
        // Obtener datos del usuario
        $usuario = $this->usuarioModel->getById(Auth::id());
        
        // Obtener reservas del usuario
        $reservas = $this->reservaModel->getByUsuario(Auth::id());
        
        include BASE_PATH . '/app/views/layouts/main.php';
        include BASE_PATH . '/app/views/usuarios/perfil.php';
        include BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    public function logout() {
        Auth::logout();
        session_start();
        $_SESSION['success'] = 'Has cerrado sesión correctamente.';
        Helper::redirect('');
    }
}