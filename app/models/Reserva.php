<?php
/**
 * Reserva
 *
 * Este archivo contiene la clase Reserva, que representa el modelo de datos para las reservas.
 *
 * @package SpaIbaiondo
 * @autor Lander Ribera
 * @version 1.0.0
 *
 * @description
 * Este modelo interactúa con la base de datos para realizar operaciones relacionadas con las reservas, como la creación, actualización y obtención de información.
 *
 * @purpose
 * Proporcionar métodos para gestionar los datos de las reservas en la base de datos.
 *
 * @class Reserva
 * - Métodos principales:
 *   - getById(): Obtiene una reserva por su ID.
 *   - getByUsuario(): Obtiene las reservas asociadas a un usuario.
 *   - create(): Crea una nueva reserva.
 *
 * @relationships
 * - Relación con el controlador ReservaController: Proporciona datos y lógica para las operaciones del controlador.
 * - Relación con la base de datos: Realiza consultas y operaciones en la tabla `reservas`.
 */

class Reserva {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT r.*, u.nombre as nombre_usuario, s.nombre as nombre_servicio, 
                   t.nombre as nombre_trabajador, s.duracion, s.precio
            FROM reservas r
            JOIN usuarios u ON r.id_usuario = u.id
            JOIN servicios s ON r.id_servicio = s.id
            JOIN trabajadores t ON r.id_trabajador = t.id
            WHERE r.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getByUsuario($idUsuario) {
        $stmt = $this->db->prepare("
            SELECT r.*, s.nombre as nombre_servicio, t.nombre as nombre_trabajador, 
                   s.duracion, s.precio
            FROM reservas r
            JOIN servicios s ON r.id_servicio = s.id
            JOIN trabajadores t ON r.id_trabajador = t.id
            WHERE r.id_usuario = ?
            ORDER BY r.fecha_hora DESC
        ");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getByTrabajador($idTrabajador) {
        $stmt = $this->db->prepare("
            SELECT r.*, u.nombre as nombre_usuario, s.nombre as nombre_servicio, 
                   s.duracion, s.precio
            FROM reservas r
            JOIN usuarios u ON r.id_usuario = u.id
            JOIN servicios s ON r.id_servicio = s.id
            WHERE r.id_trabajador = ?
            ORDER BY r.fecha_hora
        ");
        $stmt->execute([$idTrabajador]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAll() {
        $stmt = $this->db->query("
            SELECT r.*, u.nombre as nombre_usuario, s.nombre as nombre_servicio, 
                   t.nombre as nombre_trabajador, s.duracion, s.precio
            FROM reservas r
            JOIN usuarios u ON r.id_usuario = u.id
            JOIN servicios s ON r.id_servicio = s.id
            JOIN trabajadores t ON r.id_trabajador = t.id
            ORDER BY r.fecha_hora DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($idUsuario, $idServicio, $idTrabajador, $fechaHora) {
        $stmt = $this->db->prepare("
            INSERT INTO reservas (id_usuario, id_servicio, id_trabajador, fecha_hora, estado) 
            VALUES (?, ?, ?, ?, 'pendiente')
        ");
        return $stmt->execute([$idUsuario, $idServicio, $idTrabajador, $fechaHora]);
    }
    
    public function updateEstado($id, $estado) {
        $stmt = $this->db->prepare("UPDATE reservas SET estado = ? WHERE id = ?");
        return $stmt->execute([$estado, $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM reservas WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getDisponibilidad($idServicio, $fecha) {
        // Obtener trabajadores que pueden realizar este servicio
        $stmt = $this->db->prepare("
            SELECT t.id, t.nombre
            FROM trabajadores t
            WHERE t.rol IN ('masajista', 'terapeuta')
            ORDER BY t.nombre
        ");
        $stmt->execute();
        $trabajadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Obtener duración del servicio
        $stmt = $this->db->prepare("SELECT duracion FROM servicios WHERE id = ?");
        $stmt->execute([$idServicio]);
        $duracion = $stmt->fetchColumn();
        
        // Horario de trabajo (ejemplo: 9:00 a 20:00)
        $horaInicio = 9;
        $horaFin = 20;
        
        $disponibilidad = [];
        
        foreach ($trabajadores as $trabajador) {
            // Obtener reservas del trabajador para esa fecha
            $stmt = $this->db->prepare("
                SELECT r.fecha_hora, s.duracion
                FROM reservas r
                JOIN servicios s ON r.id_servicio = s.id
                WHERE r.id_trabajador = ? 
                AND DATE(r.fecha_hora) = ?
                AND r.estado != 'cancelada'
            ");
            $stmt->execute([$trabajador['id'], $fecha]);
            $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Convertir reservas a rangos ocupados
            $ocupado = [];
            foreach ($reservas as $reserva) {
                $dt = new DateTime($reserva['fecha_hora']);
                $horaReserva = (int)$dt->format('G'); // Hora sin ceros iniciales
                $minutoReserva = (int)$dt->format('i');
                $inicioEnMinutos = $horaReserva * 60 + $minutoReserva;
                $finEnMinutos = $inicioEnMinutos + $reserva['duracion'];
                
                $ocupado[] = [
                    'inicio' => $inicioEnMinutos,
                    'fin' => $finEnMinutos
                ];
            }
            
            // Generar horas disponibles
            $horasDisponibles = [];
            for ($hora = $horaInicio; $hora < $horaFin; $hora++) {
                for ($minuto = 0; $minuto < 60; $minuto += 30) {
                    $inicioEnMinutos = $hora * 60 + $minuto;
                    $finEnMinutos = $inicioEnMinutos + $duracion;
                    
                    // Verificar si este horario está disponible
                    $disponible = true;
                    foreach ($ocupado as $rango) {
                        // Si hay solapamiento, no está disponible
                        if ($inicioEnMinutos < $rango['fin'] && $finEnMinutos > $rango['inicio']) {
                            $disponible = false;
                            break;
                        }
                    }
                    
                    if ($disponible && $finEnMinutos <= $horaFin * 60) {
                        $horaFormato = sprintf("%02d:%02d", $hora, $minuto);
                        $horasDisponibles[] = $horaFormato;
                    }
                }
            }
            
            if (!empty($horasDisponibles)) {
                $disponibilidad[] = [
                    'id_trabajador' => $trabajador['id'],
                    'nombre_trabajador' => $trabajador['nombre'],
                    'horas_disponibles' => $horasDisponibles
                ];
            }
        }
        
        return $disponibilidad;
    }
}