-- Modificar la tabla de valoraciones para añadir el campo de estado
ALTER TABLE valoraciones ADD COLUMN estado ENUM('pendiente', 'aprobada', 'rechazada') NOT NULL DEFAULT 'pendiente';

-- Actualizar las valoraciones existentes a 'aprobada'
UPDATE valoraciones SET estado = 'aprobada';

