<?php

    class ServiceImage {
        private PDO $database;

        public function __construct(PDO $db){
            $this->database = $db;
        }

        public function getImagebyService(int $serviceId){
            $imgStmt = $this->database->prepare("SELECT image FROM ServiceImages WHERE service = :id LIMIT 1");
            $imgStmt->execute(['id' => $serviceId]);
            $image = $imgStmt->fetchColumn();
            return $image;
        }

        public function uploadServiceImages(int $serviceId, array $files){
            $uploadDir = __DIR__ . '/../uploads/services/';

            // Cria o diretório se não existir
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Prepara a query
            $imgStmt = $this->database->prepare("INSERT INTO ServiceImages (service, image) VALUES (:service, :image)");

            try {
                foreach ($files['tmp_name'] as $i => $tmpName) {
                    if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                        continue;  // Ignora arquivos com erro
                    }

                    $origName = basename($files['name'][$i]);
                    $ext      = pathinfo($origName, PATHINFO_EXTENSION);
                    $newName  = uniqid('img_', true) . '.' . $ext;
                    $target   = $uploadDir . $newName;

                    // Move o arquivo e registra no banco
                    if (move_uploaded_file($tmpName, $target)) {
                        $relativePath = 'uploads/services/' . $newName;
                        $imgStmt->execute([
                            ':service' => $serviceId,
                            ':image' => $relativePath
                        ]);
                    }
                }

                return true;
            } catch (Exception $e) {
                error_log("Erro ao fazer upload das imagens: " . $e->getMessage());
                return false;
            }
        }

        public function getServiceImages(int $serviceId){
            $imgStmt = $this->database->prepare("SELECT ServiceImages.id,image FROM ServiceImages WHERE service = :id");
            $imgStmt->execute(['id' => $serviceId]);
            $images = $imgStmt->fetchAll(PDO::FETCH_ASSOC);
            return $images;
        }
        public function deleteServiceImage($imageid){
            $result = $this->database->prepare("DELETE FROM ServiceImages WHERE id = :imageid");
            $result->execute(['imageid' => $imageid]);
            
        }
    }



?>