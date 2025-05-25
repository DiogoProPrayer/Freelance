<?php

    require_once(__DIR__ . '/../database/db.php');

    class Service {
        private PDO $database;

        public function __construct(PDO $db){
            $this->database = $db;
        }


        public function storeService(int $sellerId, string $title, string $description, float $price, int $categoryId, int $deliverTime){
            $stmt = $this->database->prepare("
                    INSERT INTO Service (seller, title, description, price, category, deliverTime)
                    VALUES (:seller, :title, :description, :price, :category, :deliverTime)
                ");
            $stmt->execute([
                'seller'      => $sellerId,
                'title'       => $title,
                'description' => $description,
                'price'       => $price,
                'category'    => $categoryId,
                'deliverTime' => $deliverTime,
            ]);
            $serviceId = $this->database->lastInsertId();
            return $serviceId;
        }
        public function updateService(int $serviceId, string $title, string $description, float $price, int $categoryId, int $deliverTime){
            $update = $this->database->prepare("
                UPDATE Service 
                SET title = :title, description = :description, price = :price, category = :category, deliverTime = :deliverTime
                WHERE id = :id
            ");
            return $update->execute([
                'id'          => $serviceId,
                'title'       => $title,
                'description' => $description,
                'price'       => $price,
                'category'    => $categoryId,
                'deliverTime' => $deliverTime,
            ]);
        }
        public function getServicebyCategory(int $categoryID){
            $st = $this->database->prepare('SELECT * FROM Service WHERE category = :categoryId');
            $st->execute();
            return $st->fetchAll();
        }


        public function uploadImage($userId,$targetFile){
            $imageStmt = $this->database->prepare("UPDATE User SET profileImage = :image WHERE id = :id");
            $imageStmt->execute([
                'image' => $targetFile,
                'id' => $userId
            ]);
        }

        public function getServicesbySeller(int $userId){
            // Fetch user's orders as a client
             $servicesStmt = $this->database->prepare("
                SELECT s.*, AVG(o.rating) as average_rating, COUNT(o.id) as order_count
                FROM Service s
                LEFT JOIN ServiceOrder o ON s.id = o.service
                WHERE s.seller = :seller_id
                GROUP BY s.id
            ");
            $servicesStmt->execute(['seller_id' => $userId]);
            $services = $servicesStmt->fetchAll(PDO::FETCH_ASSOC);
            return $services;
        }
        
        public function getOrdersClient(int $userId){
            $ordersStmt = $this->database->prepare("
                SELECT o.*, s.title as service_title, u.name as seller_name 
                FROM ServiceOrder o
                JOIN Service s ON o.service = s.id
                JOIN User u ON s.seller = u.id
                WHERE o.buyer = :buyer_id
                ORDER BY o.id DESC
            ");
            $ordersStmt->execute(['buyer_id' => $userId]);
            $orders = $ordersStmt->fetchAll(PDO::FETCH_ASSOC);
            return $orders;
        }
        public function getOrdersSeller(int $userId){
            $sellerOrdersStmt = $this->database->prepare("
                SELECT o.*, s.title as service_title, u.name as buyer_name
                FROM ServiceOrder o
                JOIN Service s ON o.service = s.id
                JOIN User u ON o.buyer = u.id
                WHERE s.seller = :seller_id
                ORDER BY o.id DESC
            ");
            $sellerOrdersStmt->execute(['seller_id' => $userId]);
            $sellerOrders = $sellerOrdersStmt->fetchAll(PDO::FETCH_ASSOC);
            return $sellerOrders;
        }
        public function getServiceDetails(int $serviceId){
            $stmt = $this->database->prepare("
                SELECT s.*, u.id as seller_id, u.name as seller_name, u.username as seller_username, 
                    u.profileImage as seller_image, c.name as category_name,
                    AVG(o.rating) as average_rating, COUNT(o.id) as total_orders
                FROM Service s
                JOIN User u ON s.seller = u.id
                LEFT JOIN Categories c ON s.category = c.id
                LEFT JOIN ServiceOrder o ON s.id = o.service
                WHERE s.id = :id
                GROUP BY s.id
            ");
            $stmt->execute(['id' => $serviceId]);
            $service = $stmt->fetch(PDO::FETCH_ASSOC);
            return $service;
        }
        public function getServiceDetailsWithCategoryId(int $serviceId){
            $stmt = $this->database->prepare("
                SELECT s.*, u.id as seller_id, u.name as seller_name, u.username as seller_username, 
                    u.profileImage as seller_image, c.name as category_name,c.id as category_id,
                    AVG(o.rating) as average_rating, COUNT(o.id) as total_orders
                FROM Service s
                JOIN User u ON s.seller = u.id
                LEFT JOIN Categories c ON s.category = c.id
                LEFT JOIN ServiceOrder o ON s.id = o.service
                WHERE s.id = :id
                GROUP BY s.id
            ");
            $stmt->execute(['id' => $serviceId]);
            $service = $stmt->fetch(PDO::FETCH_ASSOC);
            return $service;
        }
        public function recommendServices(int $serviceId, string $category){
            $relatedStmt = $this->database->prepare("
                SELECT s.id, s.title, s.price, si.image 
                FROM Service s
                LEFT JOIN ServiceImages si ON s.id = si.service
                WHERE s.category = :category AND s.id != :id
                GROUP BY s.id
                LIMIT 3
            ");
            $relatedStmt->execute([
                'category' => $category,
                'id' => $serviceId
            ]);
            $relatedServices = $relatedStmt->fetchAll(PDO::FETCH_ASSOC);
            return $relatedServices;
        }
        public function getPopularServices(){
            $popularServices=$this->database->prepare("
            Select S.rating,S.Title,U.username,U.profileImage,S.price From Service S
            join User U on S.seller = U.id
            Where S.id in (Select service From ServiceOrder Group By service Order By Count(service) Desc Limit 10)
            ");
            $popularServices->execute();
            $popularServices = $popularServices->fetchAll(PDO::FETCH_ASSOC);
            foreach ($popularServices as &$service){
                $$popularServicesImages=$this->database->prepare("Select image from ServiceImages where service= :id;");
                $popularServicesImages->execute(['id'=>$service['id']]);
                $service['images']=$popularServicesImages->fetchAll(PDO::FETCH_COLUMN);
            }
            return $popularServices;
        }   
        public function deleteService($serviceId){
            $deleteService = $this->database->prepare("DELETE FROM Service WHERE id = :id");
            $deleteService->execute(['id' => $serviceId]);
        }

        
    }

?>