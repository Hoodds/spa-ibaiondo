-- Tabla de valoraciones
CREATE TABLE valoraciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_servicio INT NOT NULL,
    puntuacion INT NOT NULL CHECK (puntuacion BETWEEN 1 AND 5),
    comentario TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_servicio) REFERENCES servicios(id) ON DELETE CASCADE,
    UNIQUE KEY (id_usuario, id_servicio) -- Un usuario solo puede valorar un servicio una vez
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Insertar algunas valoraciones de ejemplo
INSERT INTO valoraciones (id_usuario, id_servicio, puntuacion, comentario) VALUES
(1, 1, 5, 'Excelente masaje, muy relajante. La terapeuta fue muy profesional.'),
(2, 1, 4, 'Muy buen servicio, aunque el ambiente podría ser más tranquilo.'),
(3, 2, 5, 'El masaje terapéutico alivió mi dolor de espalda. ¡Increíble!'),
(4, 3, 4, 'El facial dejó mi piel radiante. Recomendado.'),
(5, 5, 5, 'La terapia con piedras calientes es lo mejor que he probado.'),
(6, 4, 3, 'La exfoliación fue buena, pero esperaba más del tratamiento.'),
(7, 6, 5, 'El drenaje linfático me ayudó mucho con la retención de líquidos.'),
(8, 8, 4, 'La aromaterapia fue muy relajante, volveré pronto.'),
(9, 10, 5, 'El circuito spa es completo y las instalaciones están impecables.'),
(10, 9, 4, 'La envoltura de chocolate dejó mi piel suave e hidratada.');

