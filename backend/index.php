<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Manejar las solicitudes OPTIONS (preflight CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

require_once "config/database.php";
require_once "routes/task.php";

$database = new Database();
$db = $database->getConnection();
$task = new Task($db);

// Obtener el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $task->id = $_GET['id'];
            $data = $task->getTaskById();
            echo json_encode($data ? $data : ["message" => "Task not found"]);
        } else {
            $stmt = $task->getTasks();
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($tasks);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->title)) {
            $task->title = $data->title;
            $task->status = $data->status ?? 'pendiente';
            
            if ($task->createTask()) {
                echo json_encode(["message" => "Task created successfully"]);
            } else {
                echo json_encode(["message" => "Failed to create task"]);
            }
        } else {
            echo json_encode(["message" => "Title is required"]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->id)) {
            $task->id = $data->id;
            $currentTask = $task->getTaskById();
            
            if ($currentTask) {
                $task->title = $data->title ?? $currentTask['title'];
                $task->status = $data->status ?? $currentTask['status'];

                if ($task->updateTask()) {
                    echo json_encode(["message" => "Task updated successfully"]);
                } else {
                    echo json_encode(["message" => "Failed to update task"]);
                }
            } else {
                echo json_encode(["message" => "Task not found"]);
            }
        } else {
            echo json_encode(["message" => "Task ID is required"]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->id)) {
            $task->id = $data->id;
            if ($task->deleteTask()) {
                echo json_encode(["message" => "Task deleted successfully"]);
            } else {
                echo json_encode(["message" => "Failed to delete task"]);
            }
        } else {
            echo json_encode(["message" => "Task ID is required"]);
        }
        break;

    default:
        echo json_encode(["message" => "Invalid request method"]);
        break;
}