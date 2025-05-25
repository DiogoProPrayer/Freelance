<?php

    class Tags{
        private PDO $database;

        public function __construct(PDO $database){
            $this->database = $database;
        }


        public function getAllTagsWithCategories() {
            $stmt = $this->database->query("
                SELECT t.*, c.name AS category_name
                FROM Tags t
                LEFT JOIN Categories c ON t.category = c.id
                ORDER BY c.name, t.name
            ");

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        public function insertTags(int $serviceId, array $selectedTags) {
            $tagStmt = $this->database->prepare("INSERT INTO ServiceTags (service, tag) VALUES (:service, :tag)");
            foreach ($selectedTags as $tagId) {
                $tagStmt->execute(['service' => $serviceId, 'tag' => intval($tagId)]);
            }
        }
        public function getTagsService(int $serviceId){
            $tagsStmt = $this->database->prepare("
                SELECT t.name 
                FROM ServiceTags st
                JOIN Tags t ON st.tag = t.id
                WHERE st.service = :id
            ");
            $tagsStmt->execute(['id' => $serviceId]);
            $tags = $tagsStmt->fetchAll(PDO::FETCH_COLUMN);
            return $tags;
        }
        public function getTagsID(int $serviceId){
            $tagsId=$this->database->prepare("
                SELECT t.id 
                FROM ServiceTags st
                JOIN Tags t ON st.tag = t.id
                WHERE st.service = :id
            ");
            $tagsId->execute(['id' => $serviceId]);
            $tags = $tagsId->fetchAll(PDO::FETCH_COLUMN);
            return $tags;
        } 
        public function updateTags(int $serviceId,$selectedTags){
            $deleteoldtags=$this->database->prepare("DELETE FROM ServiceTags WHERE service = :service");
            $deleteoldtags->execute(['service' => $serviceId]);
            $this->insertTags($serviceId, $selectedTags);
        }
    }


?>