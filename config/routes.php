<?php
// Rutas públicas
$router->add('GET', '/', 'HomeController', 'index');
$router->add('GET', '/servicios', 'ServicioController', 'listar');
$router->add('GET', '/servicios/{id}', 'ServicioController', 'mostrar');
$router->add('GET', '/contacto', 'HomeController', 'contacto');
$router->add('GET', '/login', 'UsuarioController', 'showLogin');
$router->add('POST', '/login', 'UsuarioController', 'login');
$router->add('GET', '/registro', 'UsuarioController', 'showRegistro');
$router->add('POST', '/registro', 'UsuarioController', 'registro');
$router->add('POST', '/contacto/enviar', 'ContactoController', 'enviar');

// Rutas para usuarios autenticados
$router->add('GET', '/perfil', 'UsuarioController', 'perfil');
$router->add('GET', '/reservas', 'ReservaController', 'misReservas');
$router->add('GET', '/reservas/crear/{id}', 'ReservaController', 'showCrear');
$router->add('POST', '/reservas/crear', 'ReservaController', 'crear');
$router->add('GET', '/reservas/{id}/cancelar', 'ReservaController', 'cancelar');
$router->add('GET', '/logout', 'UsuarioController', 'logout');
$router->add('GET', '/reservas/disponibilidad', 'ReservaController', 'getDisponibilidad');

// Rutas para trabajadores
$router->add('GET', '/admin', 'AdminController', 'dashboard');
$router->add('GET', '/admin/reservas', 'AdminController', 'listarReservas');
$router->add('GET', '/admin/servicios', 'AdminController', 'listarServicios');
$router->add('GET', '/admin/usuarios', 'AdminController', 'listarUsuarios');
$router->add('GET', '/admin/trabajadores', 'AdminController', 'listarTrabajadores');