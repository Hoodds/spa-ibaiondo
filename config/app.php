<?php
// Configuracion de la aplicacion
define('APP_NAME', 'Spa Ibaiondo');
define('APP_URL', 'http://localhost/spa-ibaiondo/public');
define('APP_VERSION', '1.0.0');

// Configuracion de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Zona horaria
date_default_timezone_set('Europe/Madrid');

// Configuracion de sesion
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);