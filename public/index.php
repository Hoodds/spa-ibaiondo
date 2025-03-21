<?php
// Definir la ruta base
define('BASE_PATH', dirname(__DIR__));

// Iniciar sesión
session_start();

// Cargar configuraciones
require_once BASE_PATH . '/config/app.php';
require_once BASE_PATH . '/config/database.php';

// Cargar clases core
require_once BASE_PATH . '/core/Router.php';
require_once BASE_PATH . '/core/Auth.php';
require_once BASE_PATH . '/core/Helper.php';

// Inicializar el router
$router = new Router();

// Cargar rutas
require_once BASE_PATH . '/config/routes.php';

// Procesar la solicitud
$router->dispatch();