<?php

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    public function __construct() {
        // Cargar variables de entorno (si usas un archivo .env)
        $this->host = getenv('DB_HOST') ?: 'mysql';
        $this->db_name = getenv('DB_DATABASE') ?: 'tasks_db';
        $this->username = getenv('DB_USERNAME') ?: 'user';
        $this->password = getenv('DB_PASSWORD') ?: 'password';
    }

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8mb4");  // Usar utf8mb4 para soportar caracteres especiales
        } catch(PDOException $e) {
            // Loggear el error en lugar de mostrarlo directamente
            error_log("Connection error: " . $e->getMessage());
            throw new Exception("Database connection error.");
        }

        return $this->conn;
    }
}