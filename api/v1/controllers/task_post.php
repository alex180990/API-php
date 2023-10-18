<?php
    header('Access-Control-Allow-Origin: *');

    /*
        Carine : Ajouter OPTIONS manquant
        
        Pourquoi OPTIONS ? 

        Requêtre de pré-vérification : 
        https://developer.mozilla.org/fr/docs/Glossary/Preflight_request

        "Une requête de pré-vérification cross-origin CORS est une requête de vérification faite 
        pour contrôler si le protocole CORS est autorisé."
        C'est la raison pour laquelle ça fonctionne pour postman et pas pour ton site. 
        Sur postman, tu apelles l'api localement, alors que dans ton app, tu utilises un autre site (sur un autre port)
        Celui-ci détecte une origine différente et fait appel à la requête de pré-vérification OPTIONS
    */
    header('Access-Control-Allow-Methods: POST, OPTIONS'); 

    /*
        Carine : Ajouter 'Access-Control-Allow-Headers: Content-Type' 
        sinon impossible de fournir du contenu dans le body du fetch 
        lorsque ça implique une origine différente
    */
    header('Access-Control-Allow-Headers: Content-Type');

    header('Content-Type: application/json, charset=UTF-8');

    require_once '../config/Database.php';
    require_once '../models/task.php';


if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $database = new Database();
        $db = $database->getConnexion();
    
        $task = new Task($db);
        
        $data = json_decode(file_get_contents('php://input'), true);
        //print_r($data);

        if (!empty($data["title"]) && !empty($data["description"]) && isset($data["completed"])) {
            //<b>Notice</b>:  Undefined index: id in <b>C:\Program Files\Ampps\www\a\API-php\api\v1\controllers\task_post.php</b> on line <b>22</b><br />
            //$task->id = intval($data["id"]);

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

    /*
        Carine : Ajouter une condition permettant d'intercepter l'appel à la methode OPTIONS. 
        Sinon, tu renvois le code 405, 
        la requête de pré-vérification échoue alors le site n'exécute pas le POST
    */
    } else if($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { 
        echo json_encode(["message" => "Méthode OPTIONS appelée"]);
    }else{
        http_response_code(405);//code de reponse qui veut dire erreur
        echo json_encode(["message" => "Méthode non autorisée"]);
    }
?>