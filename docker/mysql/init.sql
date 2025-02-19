-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS tasks_db
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

-- Usar la base de datos tasks_db
USE tasks_db;

-- Crear la tabla de tareas
CREATE TABLE IF NOT EXISTS tasks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    status ENUM('pendiente', 'completada', 'aplazada', 'rechazada') NOT NULL DEFAULT 'pendiente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insertar datos de ejemplo (opcional)
INSERT INTO tasks (title, status) VALUES
('Crear artículo blog', 'pendiente'),
('Crear página web', 'pendiente'),
('Grabar tutorial', 'pendiente'),
('Ir al banco', 'completada'),
('Ir a cita médica', 'completada'),
('Ir comprar materiales', 'aplazada'),
('Ir a cita con clientes', 'aplazada'),
('Ir al banco', 'rechazada'),
('Ir a cita médica', 'rechazada');