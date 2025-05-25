<?php

    require_once(__DIR__ . '/../database/db.php');

    class Service {
        private PDO $database;

        public function __construct(PDO $db){
            $this->database = $db;
        }

        public function getServices(){
            $stmt = $this->database->prepare("
                SELECT s.*, u.name as seller_name, u.username as seller_username, c.name as category_name
                FROM Service s
                LEFT JOIN User u ON s.seller = u.id
                LEFT JOIN Categories c ON s.category = c.id
                ORDER BY s.id DESC
            ");
            $stmt->execute();
            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $services;
        }

        public function deleteService(int $service_id){
            $stmt = $this->database->prepare("DELETE FROM Service WHERE id = :id");
            return $stmt->execute(['id' => $service_id]);
        }

        public function deleteReview(int $reviewId){
            $stmt = $this->database->prepare("UPDATE ServiceOrder SET review = NULL WHERE id = :id");
            return $stmt->execute(['id' => $reviewId]);
        }
        public function getReviews(){
            $stmt = $this->database->prepare("
                SELECT o.*, s.title as service_title, u.name as buyer_name, u.username as buyer_username,
                    s.id as service_id
                FROM ServiceOrder o
                JOIN Service s ON o.service = s.id
                JOIN User u ON o.buyer = u.id
                WHERE o.rating IS NOT NULL
                ORDER BY o.id DESC
            ");
            $stmt->execute();
            $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $reviews;
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
            $st->execute(['categoryId' => $categoryID]);
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
        
        public function getServicesbyCategory(int $categoryId){
            // Fetch user's orders as a client
             $servicesStmt = $this->database->prepare("
                SELECT s.*, AVG(o.rating) as average_rating, COUNT(o.id) as order_count
                FROM Service s
                LEFT JOIN ServiceOrder o ON s.id = o.service 
                WHERE s.category = :categoryId
                GROUP BY s.id
            ");
            $servicesStmt->execute(['categoryId' => $categoryId]);
            $services = $servicesStmt->fetchAll(PDO::FETCH_ASSOC);
            return $services;
        }

        public function getOrdersClient(int $userId){
            $ordersStmt = $this->database->prepare("
                SELECT o.*, s.title as service_title, u.name as seller_name, s.price, s.deliverTime
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
        public function resultsSearchInput(string $query): array {
            $st = $this->database->prepare(
                '
                SELECT s.*, u.name AS seller_name,
                    (SELECT si.image 
                        FROM ServiceImages si 
                        WHERE si.service = s.id 
                        LIMIT 1) AS image
                FROM Service s
                JOIN User u ON s.seller = u.id
                WHERE LOWER(s.title) LIKE LOWER(?) OR LOWER(u.name) LIKE LOWER(?)
                '
            );

            $searchTerm = '%' . $query . '%';
            $st->execute([$searchTerm, $searchTerm]);

            return $st->fetchAll(PDO::FETCH_ASSOC);
        }
        
        public function filterServices($categories,$max, $minRating, $minOrders, $sort, $order){
            $query = " SELECT s.*, AVG(o.rating) AS average_rating, COUNT(o.id) AS order_count
                       FROM Service s
                       LEFT JOIN ServiceOrder o ON s.id = o.service
                    ";

            $conditions = [];
            $params = [];

            if (!empty($categories)) {
                $placeholders = implode(',', array_fill(0, count($categories), '?'));
                $conditions[] = "s.category IN ($placeholders)";
                $params = array_merge($params, $categories);
            }

            if (!empty($max)) {
                $conditions[] = "s.price <= ?";
                $params[] = $max;
            }

            if (!empty($conditions)) {
                $query .= " WHERE " . implode(' AND ', $conditions);
            }

            $query .= " GROUP BY s.id";

            $having = [];
            if (!empty($minRating)) {
                $having[] = "AVG(o.rating) >= ?";
                $params[] = $minRating;
            }
            if (!empty($minOrders)) {
                $having[] = "COUNT(o.id) >= ?";
                $params[] = $minOrders;
            }

            if (!empty($having)) {
                $query .= " HAVING " . implode(" AND ", $having);
            }

            if ($sort !== 'none') {
                $orderField = match ($sort) {
                    'price' => 's.price',
                    'rating' => 'average_rating',
                    'orders' => 'order_count',
                    'title' => 's.title',
                    default => 's.id'
                };

                $query .= " ORDER BY $orderField $order";
            }
            
            $stmt = $this->database->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        function createServiceOrder($serviceId, $buyerId, $status, $rating = null, $review = null) {
            try {
                $stmt = $this->database->prepare("
                    INSERT INTO ServiceOrder (
                        service, 
                        buyer, 
                        orderStatus, 
                        rating, 
                        review
                    )
                    VALUES (?, ?, ?, ?, ?)
                ");
                
                $stmt->execute([
                    $serviceId,
                    $buyerId,
                    $status,
                    $rating,
                    $review
                ]);
                
                return $this->database->lastInsertId(); 
            } catch (PDOException $e) {
                error_log("Error creating order: " . $e->getMessage());
                return false;
            }
        }

        public function getOrderServicebyId (int $order_id){
            $st = $this->database->prepare(
                'SELECT o.*, s.deliverTime as deliverTime
                 FROM ServiceOrder o
                 JOIN Service s ON o.service = s.id
                 JOIN User u ON s.seller = u.id
                 WHERE o.id = :id
                '
            );

            $st->execute(['id' => $order_id]);
            $order = $st->fetch(PDO::FETCH_ASSOC);
            return $order;


        }

        public function updateOrderDeliveryDays($orderId, $remainingDays) {
            // Determina o novo status baseado nos dias restantes
            $newStatus = ($remainingDays <= 0) ? 'Delayed' : 'In progress';
            
            $stmt = $this->database->prepare("
                UPDATE ServiceOrder 
                SET remaining_days = :remainingDays, 
                    last_updated = CURRENT_TIMESTAMP,
                    orderStatus = :status
                WHERE id = :orderId
            ");
            
            return $stmt->execute([
                'remainingDays' => $remainingDays,
                'status' => $newStatus,
                'orderId' => $orderId
            ]);
        }

        public function deleteOrder($order_id){
            $stmt = $this->database->prepare("DELETE FROM ServiceOrder WHERE id = :id");
            return $stmt->execute(['id' => $order_id]);
        }

        public function deliverOrder($order_id){
            try {
                $stmt = $this->database->prepare("UPDATE ServiceOrder SET orderStatus = 'DELIVERED' WHERE id = :id");
                $stmt->bindParam(':id', $order_id, PDO::PARAM_INT);
                return $stmt->execute();
            } catch (PDOException $e) {
                error_log("Error in deliverOrder: " . $e->getMessage());
                return false;
            }
        }
        public function getPopularServices(){
            $popularServices=$this->database->prepare("
            Select S.id, S.rating,S.Title,U.username,U.profileImage,S.price, U.id as seller_id From Service S
            join User U on S.seller = U.id
            Where S.id in (Select service From ServiceOrder Group By service Order By Count(service) Desc Limit 10)
            ");
            $popularServices->execute();
            $popularServices = $popularServices->fetchAll(PDO::FETCH_ASSOC);
            foreach ($popularServices as &$service){
                $popularServicesImages=$this->database->prepare("Select image from ServiceImages where service= :id;");
                $popularServicesImages->execute(['id'=>$service['id']]);
                $service['images']=$popularServicesImages->fetchAll(PDO::FETCH_COLUMN);
            }
            return $popularServices;
        }   
    }

?>