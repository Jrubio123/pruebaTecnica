<?php

class Task {
    private $conn;
    private $table_name = "tasks";

    public $id;
    public $title;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todas las tareas
    public function getTasks() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Crear una nueva tarea
    public function createTask() {
        $query = "INSERT INTO " . $this->table_name . " (title, status) VALUES (:title, :status)";

        $stmt = $this->conn->prepare($query);
        
        // Limpieza y validación de datos
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->status = htmlspecialchars(strip_tags($this->status));
        
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":status", $this->status);

        return $stmt->execute();
    }

    // Obtener una sola tarea
    public function getTaskById() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar una tarea
    public function updateTask() {
        $query = "UPDATE " . $this->table_name . " SET title = :title, status = :status WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        
        // Limpieza y validación de datos
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->status = htmlspecialchars(strip_tags($this->status));
        
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    // Eliminar una tarea
    public function deleteTask() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }
}