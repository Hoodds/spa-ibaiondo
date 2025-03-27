-- Crear la base de datos
CREATE DATABASE spa DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;
USE spa;

-- Tabla de usuarios (clientes)
CREATE TABLE usuarios (
	id INT AUTO_INCREMENT PRIMARY KEY,
	nombre VARCHAR(100) NOT NULL,
	email VARCHAR(100) UNIQUE NOT NULL,
	contrasena VARCHAR(255) NOT NULL,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Tabla de trabajadores
CREATE TABLE trabajadores (
	id INT AUTO_INCREMENT PRIMARY KEY,
	nombre VARCHAR(100) NOT NULL,
	email VARCHAR(100) UNIQUE NOT NULL,
	contrasena VARCHAR(255) NOT NULL,
	rol ENUM('admin', 'recepcionista', 'masajista', 'terapeuta') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Tabla de servicios
CREATE TABLE servicios (
	id INT AUTO_INCREMENT PRIMARY KEY,
	nombre VARCHAR(100) NOT NULL,
	descripcion TEXT NOT NULL,
	duracion INT NOT NULL,
	precio DECIMAL(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Tabla de reservas
CREATE TABLE reservas (
	id INT AUTO_INCREMENT PRIMARY KEY,
	id_usuario INT,
	id_servicio INT,
	id_trabajador INT,
	fecha_hora DATETIME NOT NULL,
	estado ENUM('pendiente', 'confirmada', 'cancelada') NOT NULL,
	FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
	FOREIGN KEY (id_servicio) REFERENCES servicios(id),
	FOREIGN KEY (id_trabajador) REFERENCES trabajadores(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Tabla de mensajes
CREATE TABLE mensajes_contacto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    asunto VARCHAR(255) NOT NULL,
    mensaje TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Insertar datos en usuarios
INSERT INTO usuarios (nombre, email, contrasena) VALUES
('Juan Pérez', 'juan@example.com', '123456'),
('María López', 'maria@example.com', '123456'),
('Carlos Gómez', 'carlos@example.com', '123456'),
('Ana Martínez', 'ana@example.com', '123456'),
('Luis Ramírez', 'luis@example.com', '123456'),
('Elena Torres', 'elena@example.com', '123456'),
('Pedro Jiménez', 'pedro@example.com', '123456'),
('Laura Sánchez', 'laura@example.com', '123456'),
('David Ríos', 'david@example.com', '123456'),
('Andrea Castillo', 'andrea@example.com', '123456'),
('Jorge Medina', 'jorge@example.com', '123456'),
('Silvia Vega', 'silvia@example.com', '123456'),
('Fernando Herrera', 'fernando@example.com', '123456'),
('Patricia Cruz', 'patricia@example.com', '123456'),
('Alberto Flores', 'alberto@example.com', '123456'),
('Gabriela Romero', 'gabriela@example.com', '123456'),
('Manuel Ortega', 'manuel@example.com', '123456'),
('Carmen Álvarez', 'carmen@example.com', '123456'),
('Héctor Navarro', 'hector@example.com', '123456'),
('Isabel Duarte', 'isabel@example.com', '123456');

-- Insertar datos en trabajadores
INSERT INTO trabajadores (nombre, email, contrasena, rol) VALUES
('Admin General', 'admin@spa.com', 'admin123', 'admin'),
('Ana Recepcionista', 'ana@spa.com', 'rec123', 'recepcionista'),
('Carlos Masajista', 'carlos@spa.com', 'masajista123', 'masajista'),
('Pedro Masajista', 'pedro@spa.com', 'masajista123', 'masajista'),
('Lucía Terapeuta', 'lucia@spa.com', 'terapeuta123', 'terapeuta'),
('Miguel Terapeuta', 'miguel@spa.com', 'terapeuta123', 'terapeuta'),
('Sandra Recepcionista', 'sandra@spa.com', 'rec456', 'recepcionista'),
('Daniel Masajista', 'daniel@spa.com', 'masajista456', 'masajista'),
('Raúl Terapeuta', 'raul@spa.com', 'terapeuta456', 'terapeuta'),
('Beatriz Recepcionista', 'beatriz@spa.com', 'rec789', 'recepcionista');

-- Insertar datos en servicios
INSERT INTO servicios (nombre, descripcion, duracion, precio) VALUES
('Masaje relajante', 'Masaje con aceites esenciales.', 60, 50.00),
('Masaje terapéutico', 'Masaje profundo para aliviar tensiones.', 45, 55.00),
('Facial hidratante', 'Tratamiento facial con productos naturales.', 30, 40.00),
('Exfoliación corporal', 'Elimina células muertas con sales marinas.', 50, 65.00),
('Terapia con piedras calientes', 'Relajación con piedras volcánicas.', 75, 70.00),
('Drenaje linfático', 'Masaje suave para eliminar toxinas.', 40, 45.00),
('Reflexología podal', 'Terapia en puntos estratégicos del pie.', 35, 35.00),
('Aromaterapia', 'Masaje con aceites esenciales personalizados.', 60, 60.00),
('Envoltura de chocolate', 'Tratamiento corporal hidratante.', 55, 75.00),
('Circuito Spa', 'Acceso a sauna, jacuzzi y piscina climatizada.', 90, 85.00);

-- Insertar datos en reservas
INSERT INTO reservas (id_usuario, id_servicio, id_trabajador, fecha_hora, estado) VALUES
(1, 1, 3, '2024-02-15 10:00:00', 'confirmada'),
(2, 5, 5, '2024-03-10 15:30:00', 'cancelada'),
(3, 3, 6, '2024-01-20 09:00:00', 'confirmada'),
(4, 2, 4, '2024-04-05 14:00:00', 'pendiente'),
(5, 7, 9, '2024-05-22 16:00:00', 'confirmada'),
(6, 6, 10, '2024-02-08 12:30:00', 'confirmada'),
(7, 8, 8, '2024-03-25 11:45:00', 'cancelada'),
(8, 4, 7, '2024-06-14 13:15:00', 'pendiente'),
(9, 10, 2, '2024-07-01 17:00:00', 'confirmada'),
(10, 9, 5, '2024-08-19 18:30:00', 'pendiente');
