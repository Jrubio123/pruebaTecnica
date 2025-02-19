-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS tasks_db;
USE tasks_db;

-- Crear la tabla de tareas
CREATE TABLE IF NOT EXISTS tasks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    status ENUM('pendiente', 'completada', 'aplazada', 'rechazada') NOT NULL DEFAULT 'pendiente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
