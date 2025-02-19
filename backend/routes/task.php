<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        $stmt = $db->prepare("SELECT * FROM tasks");
        $stmt->execute();
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($tasks);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        $stmt = $db->prepare("INSERT INTO tasks (title, status) VALUES (:title, :status)");
        $stmt->bindParam(':title', $data->title);
        $stmt->bindParam(':status', $data->status);
        
        if($stmt->execute()) {
            echo json_encode(["message" => "Task created successfully"]);
        } else {
            echo json_encode(["message" => "Unable to create task"]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        $stmt = $db->prepare("UPDATE tasks SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $data->status);
        $stmt->bindParam(':id', $data->id);
        
        if($stmt->execute()) {
            echo json_encode(["message" => "Task updated successfully"]);
        } else {
            echo json_encode(["message" => "Unable to update task"]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        $stmt = $db->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->bindParam(':id', $data->id);
        
        if($stmt->execute()) {
            echo json_encode(["message" => "Task deleted successfully"]);
        } else {
            echo json_encode(["message" => "Unable to delete task"]);
        }
        break;
}