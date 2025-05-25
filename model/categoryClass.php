<?php

    require_once(__DIR__ . '/../database/db.php');

    class Category {
        private PDO $database;

        public function __construct(PDO $database){
            $this->database = $database;
        }
        
        public function getCategoryNamebyId(int $id){
            $dat = $this->database->prepare('SELECT name FROM Categories WHERE id = :id');
            $dat->execute(['id' => $id]);

            $result = $dat->fetch(PDO::FETCH_ASSOC);

            return $result['name'] ?? null;
        }

        public function getCategoryIdbyName(string $categoryName){
            $dat = $this->database->prepare('SELECT id FROM Categories WHERE LOWER(name) =  LOWER(:categoryName)');
            $dat->execute(['categoryName' => $categoryName]);

            $result = $dat->fetch(PDO::FETCH_ASSOC);

            return $result['id'] ?? null;
        }

        public function getAllCategories(){
            $cat = $this->database->prepare('SELECT * FROM Categories ORDER BY name');

            $cat->execute();

            return $cat->fetchAll(PDO::FETCH_ASSOC);
        }

        public function createNewCategory(string $name){
            $st = $this->database->prepare('INSERT INTO Categories (name) VALUES (:name)');
            $st->execute(array($st));
        }  
    }

?>