<?php
    //les entetes requises
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json, charset=UTF-8');
    header('Access-Control-Allow-Methods: POST');

    require_once '../config/Database.php';
    require_once '../models/task.php';


if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $database = new Database();
        $db = $database->getConnexion();
    
        $task = new Task($db);
        
        $data = json_decode(file_get_contents('php://input'), true);
        print_r($data);

        if (!empty($data["title"]) && !empty($data["description"]) && isset($data["completed"])) {
            $task->id = intval($data["id"]);
            $task->title = htmlspecialchars($data["title"]);
            $task->description = htmlspecialchars($data["description"]);
            $task->completed = htmlspecialchars($data["completed"]);

            $result = $task->create($task);
            if($result){
                http_response_code(200); //code de reponse qui veut dire objet creé
                echo json_encode(["message" => "Tache ajoutée"]);
            }else{
                http_response_code(503); //code de reponse qui veut dire erreur de creation de l'objet
                echo json_encode(["message" => "L'ajout de la tache a échoué"]);
            }
        }else{
            http_response_code(400); //code de reponse qui veut dire erreur
            echo json_encode(["message" => !empty($data["completed"])]);
        }
        $db = null;
    }else{
        http_response_code(405);//code de reponse qui veut dire erreur
        echo json_encode(["message" => "Méthode non autorisée"]);
    }
?>