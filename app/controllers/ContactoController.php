<?php
/**
 * ContactoController
 *
 * Este archivo contiene la clase ContactoController, que gestiona las operaciones relacionadas con el formulario de contacto.
 *
 * @package SpaIbaiondo
 * @autor Lander Ribera
 * @version 1.0.0
 *
 * @description
 * Este controlador maneja las acciones relacionadas con el envío y gestión de mensajes de contacto.
 *
 * @purpose
 * Proporcionar la lógica necesaria para gestionar los mensajes enviados desde el formulario de contacto.
 *
 * @class ContactoController
 * - Métodos principales:
 *   - enviar(): Procesa el envío de un mensaje de contacto.
 *
 * @relationships
 * - Relación con el modelo MensajeContacto: Utiliza el modelo MensajeContacto para guardar y gestionar los mensajes.
 * - Relación con las vistas: Renderiza las vistas ubicadas en `app/views/home`.
 */

class ContactoController {
    private $mensajeModel;

    public function __construct() {
        require_once BASE_PATH . '/app/models/MensajeContacto.php';
        $this->mensajeModel = new MensajeContacto();
    }

    public function enviar() {
        // Validar los datos del formulario
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $asunto = $_POST['asunto'] ?? '';
        $mensaje = $_POST['mensaje'] ?? '';

        if (empty($nombre) || empty($email) || empty($asunto) || empty($mensaje)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            Helper::redirect('contacto');
            return;
        }

        // Guardar el mensaje en la base de datos
        if ($this->mensajeModel->guardar([
            'nombre' => $nombre,
            'email' => $email,
            'asunto' => $asunto,
            'mensaje' => $mensaje
        ])) {
            $_SESSION['success'] = 'Tu mensaje ha sido enviado correctamente.';
        } else {
            $_SESSION['error'] = 'Hubo un problema al enviar tu mensaje.';
        }

        Helper::redirect('contacto');
    }
}