<?php

// Incluir el archivo de configuración de la base de datos
include_once 'config/database.php';

try {
    // Crear una instancia de la clase Database
    $database = new Database();
    
    // Intentar obtener la conexión
    $db = $database->getConnection();
    
    // Si la conexión es exitosa, mostrar un mensaje
    echo json_encode(["message" => "Hola, si tenemos conexion :D"]);
} catch (Exception $e) {
    // Si hay un error, mostrar el mensaje de error
    echo json_encode(["message" => "Error de conexión: " . $e->getMessage()]);
}