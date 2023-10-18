<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json, charset=UTF-8');
header('Access-Control-Allow-Methods: GET');

require_once '../config/Database.php';
require_once '../models/task.php';

$database = new Database();
$db = $database->getConnexion();

// Créez une instance de la classe Task en utilisant la connexion à la base de données
$task = new Task($db);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Récupérer l'ID de la tâche depuis la requête GET
    $taskId = isset($_GET['id']) ? intval($_GET['id']) : null;

    if ($taskId !== null) {
        // Utiliser la méthode getOne() pour récupérer une tâche par son ID
        $statement = $task->getOne($taskId);

        if ($statement->rowCount() > 0) {
            $data = $statement->fetch(PDO::FETCH_ASSOC);
            http_response_code(200); // Code de réponse OK
            echo json_encode($data);
        } else {
            http_response_code(404); // Code de réponse pour tâche non trouvée
            echo json_encode(["message" => "Aucune tâche trouvée avec cet ID"]);
        }
    } else {
        http_response_code(400); // Code de réponse pour une mauvaise demande
        echo json_encode(["message" => "L'ID de la tâche est manquant ou invalide"]);
    }
} else {
    http_response_code(405); // Code de réponse pour une méthode non autorisée
    echo json_encode(["message" => "Méthode non autorisée"]);
}
?>