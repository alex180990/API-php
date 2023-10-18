<?php
    //les entetes requises
    header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json, charset=UTF-8');
    header('Access-Control-Allow-Methods: PUT');

    require_once '../config/Database.php';
    require_once '../models/task.php';

    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

        $database = new Database();
        $db = $database->getConnexion();
    
        $task = new Task($db);
    
        $data = json_decode(file_get_contents('php://input'), true);
    
        if (isset($data["id"]) && isset($data["title"]) && isset($data["description"]) && isset($data["completed"])) {
            $task->id = intval($data["id"]);
            $task->title = htmlspecialchars($data["title"]);
            $task->description = htmlspecialchars($data["description"]);
            $task->completed = htmlspecialchars($data["completed"]);
    
            $result = $task->update($task->id);
    
            if ($result) {
                http_response_code(200); // Code de réponse qui signifie succès
                echo json_encode(["message" => "Tache modifiée"]);
            } else {
                http_response_code(503); // Code de réponse qui signifie erreur de modification de l'objet
               // echo json_encode(["message" => "La modification de la tâche a échoué"]);
            }
        } else {
            http_response_code(400); // Code de réponse qui signifie erreur
            echo json_encode(["message" => !empty($data["completed"])]);
            //echo json_encode(["message" => "Les données sont manquantes"]);
        }
        $db = null;
    }/* else {
        http_response_code(200); // Code de réponse qui signifie méthode non autorisée
        echo json_encode(["message" => "Méthode non autorisée"]);
    }*/
?>