<?php
    class Task 
    {
        private $table = "tasks";
        private $connexion = null;

        public $id;
        public $title;
        public $description;
        public $completed;

        public function __construct($db)
        {
            if($this->connexion == null){
                $this->connexion = $db;
            }
        }

        //lecture des taches
        public function getAll(){
            $sql = "SELECT id, title, description, completed FROM $this->table ORDER BY id";

            $req = $this->connexion->query($sql);
            return $req;
        }

        //lecture d'une tache
        public function getOne($id){
            $sql = "SELECT id, title, description, completed FROM $this->table WHERE id = :id";
        
            $stmt = $this->connexion->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Liez le paramètre :id à la valeur de $id
            $stmt->execute();
        
            return $stmt;
        }

        //creation d'une tache
        public function create(){
            $sql = "INSERT INTO $this->table (title, description, completed) VALUES (:title, :description, :completed)";
            
            try {
                if (!$this->validateData()) {
                    return false;
                }
                
                $stmt = $this->connexion->prepare($sql);
                $stmt->bindParam(':title', $this->title);
                $stmt->bindParam(':description', $this->description);
    
                // Convertir la valeur booléenne en entier (0 ou 1)
                $completedValue = $this->completed ? 1 : 0;
                $stmt->bindParam(':completed', $completedValue, PDO::PARAM_INT);
                
                if ($stmt->execute()) {
                    return true;
                } else {
                    return false;
                }
            } catch (PDOException $e) {
                // Gérez l'erreur ici, par exemple :
                echo "Erreur lors de la création de la tâche : " . $e->getMessage();
                return false;
            }
        }

        public function update($id){
            $sql = "UPDATE $this->table SET title = :title, description = :description, completed = :completed WHERE id = :id";
    
            try {
                if (!$this->validateData()) {
                    return false;
                }
    
                $stmt = $this->connexion->prepare($sql);
                $stmt->bindParam(':title', $this->title);
                $stmt->bindParam(':description', $this->description);
                
                // Convertir la valeur booléenne en entier (0 ou 1)
                $completedValue = $this->completed ? 1 : 0;
                $stmt->bindParam(':completed', $completedValue, PDO::PARAM_INT);
    
                $stmt->bindParam(':id', $id);
                
                if ($stmt->execute()) {
                    return true;
                } else {
                    return false;
                }
            } catch (PDOException $e) {
                // Gérez l'erreur ici, par exemple :
                echo "Erreur lors de la mise à jour de la tâche : " . $e->getMessage();
                return false;
            }
        }

        private function validateData() {
            if (empty($this->title) || empty($this->description)) {
                return false;
            }
            return true;
        }

        public function delete($id){
            $sql = "DELETE FROM $this->table WHERE id = :id";

            $req = $this->connexion->prepare($sql);
            $re = $req->execute(array(':id' => $id));

            if($re){
                return true;
            }else{
                return false;
            }
        }
    }
?>