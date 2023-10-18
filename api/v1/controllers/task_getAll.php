<?php
    //les entetes requises
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json, charset=UTF-8');
    header('Access-Control-Allow-Methods: GET');

    require_once '../config/Database.php';
    require_once '../models/task.php';

    if($_SERVER['REQUEST_METHOD'] === 'GET')
    {   
        $database = new Database();
        $db = $database->getConnexion();
    
        $task = new Task($db);
    
        //Recupere toutes les taches
        $statement = $task->getAll();
    
        if($statement->rowCount() > 0){
            $data = [];
            $data[] = $statement->fetchAll();
            
            http_response_code(200); //code de reponse qui veut dire OK
            echo json_encode($data);
        }else{
            http_response_code(400); //code de reponse qui veut dire erreur
            echo json_encode(["message" => "Aucune tache trouvée"]);
        }
        $db = null;
    }else{
        http_response_code(405);//code de reponse qui veut dire erreur
        echo json_encode(["message" => "Méthode non autorisée"]);
    }


    /*if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $database = new Database();
        $db = $database->getConnexion();
    
        $task = new Task($db);
        
        $data = json_decode(file_get_contents('php://input'));

        if(!empty($data->title) && !empty($data->description) && !empty($data->completed)){
            $task->title = htmlspecialchars($data->title);
            $task->description = htmlspecialchars($data->description);
            $task->completed = htmlspecialchars($data->completed);

            $result = $task->create();
            if($result){
                http_response_code(201); //code de reponse qui veut dire objet creé
                echo json_encode(["message" => "Tache ajoutée"]);
            }else{
                http_response_code(503); //code de reponse qui veut dire erreur de creation de l'objet
                echo json_encode(["message" => "L'ajout de la tache a échoué"]);
            }
        }else{
            http_response_code(400); //code de reponse qui veut dire erreur
            echo json_encode(["message" => "Les données sont manquantes"]);
        }
        $db = null;
    }else{
        http_response_code(405);//code de reponse qui veut dire erreur
        echo json_encode(["message" => "Méthode non autorisée"]);
    }


    if($_SERVER['REQUEST_METHOD'] === 'PUT')
    {
        $database = new Database();
        $db = $database->getConnexion();
    
        $task = new Task($db);
        
        $data = json_decode(file_get_contents('php://input'));

        if(!empty($data->id) && !empty($data->title) && !empty($data->description) && !empty($data->completed)){

            $task->id = intval($data->id);
            $task->title = htmlspecialchars($data->title);
            $task->description = htmlspecialchars($data->description);
            $task->completed = htmlspecialchars($data->completed);

            $result = $task->update($task->id);

            if($result){
                http_response_code(201); //code de reponse qui veut dire objet creé
                echo json_encode(["message" => "Tache modifiée"]);
            }else{
                http_response_code(503); //code de reponse qui veut dire erreur de creation de l'objet
                echo json_encode(["message" => "La modification de la tache a échoué"]);
            }
        }else{
            http_response_code(400); //code de reponse qui veut dire erreur
            echo json_encode(["message" => "Les données sont manquantes"]);
        }
        $db = null;
    }else{
        http_response_code(405);//code de reponse qui veut dire erreur
        echo json_encode(["message" => "Méthode non autorisée"]);
    }



    if($_SERVER['REQUEST_METHOD'] === 'DELETE')
    {
        $database = new Database();
        $db = $database->getConnexion();
    
        $task = new Task($db);
        
        $data = json_decode(file_get_contents('php://input'));

        if(!empty($data->id)){
            $task->id = $data->id;
            if($task->delete($task->id)){
                http_response_code(200); //code de reponse qui veut dire OK
                echo json_encode(["message" => "Tache supprimée"]);
            }else{
                http_response_code(503); //code de reponse qui veut dire erreur de creation de l'objet
                echo json_encode(["message" => "La suppression de la tache a échoué"]);
            }
        }else{
            http_response_code(400); //code de reponse qui veut dire erreur
            echo json_encode(["message" => "Vous n'avez pas spécifié la tâche à supprimer"]);
        }
        $db = null;
    }else{
        http_response_code(405);//code de reponse qui veut dire erreur
        echo json_encode(["message" => "Méthode non autorisée"]);
    }*/
?>