<?php
    require_once(__DIR__ . '/../database/db.php');
    require_once(__DIR__ . '/../model/userClass.php');

    class UserRepository {
        private PDO $database;

        public function __construct(PDO $db){
            $this->database = $db;
        }

        public function getUserbyId(int $id){
            $st = $this->database->prepare('SELECT * FROM User WHERE id = :id');
            $st->execute(['id' => $id]);
            $data = $st->fetch(PDO::FETCH_ASSOC);

            return $data ? new User($data) : null;
        }

        public function setUserStatus(string $new_userStatus, string $old_userStatus, int $userID) {

            $stmt = $this->database->prepare("UPDATE User SET userStatus = :userStatus WHERE id = :userID");

            $stmt->bindParam(":userStatus", $new_userStatus, PDO::PARAM_STR);
            $stmt->bindParam(":userID", $userID, PDO::PARAM_INT);


            if ($stmt->execute()) {
                echo "Status atualizado com sucesso para o usuário ID: $userID";
            } else {
                echo "Erro ao atualizar: " . $stmt->errorInfo()[2]; 
            }
        }

        public function getUsers(){
            $stmt = $this->database->prepare("SELECT * FROM User ORDER BY id");
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $users;
        }

        public function deleteUser(int $user_id){
            $stmt = $this->database->prepare("DELETE FROM User WHERE id = :id");
            return $stmt->execute(['id' => $user_id]);
        }

        public function elevateUsertoAdmin(int $user_id){
            $stmt = $this->database->prepare("UPDATE User SET isAdmin = 1 WHERE id = :id");
            return $stmt->execute(['id' => $user_id]);
        }

        public function setUserInfo(string $name, string $username, string $email, string $country, string $phoneNumber, int $userID) {
            $stmt = $this->database->prepare('
                UPDATE User 
                SET name = :name, username = :username, email = :email, country = :country, phoneNumber = :phoneNumber 
                WHERE id = :userID
            ');

            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':country', $country);
            $stmt->bindParam(':phoneNumber', $phoneNumber);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo "Informações do usuário ID $userID atualizadas com sucesso!";
            } else {
                echo "Erro ao atualizar as informações: " . implode(" | ", $stmt->errorInfo());
            }
        }

        public function getTopSellers(){
            $topsellers =$this->database->prepare('Select * FROM User WHERE userStatus = "seller" ORDER BY rating DESC LIMIT 10');
            $topsellers->execute();
            return $topsellers->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getPublicProfileById(int $id): ?array {
            // Fetches only publicly relevant information
            // Added bio, country which might be relevant for a public profile
            $stmt = $this->database->prepare('
                SELECT id, name, username, profileImage, bio, country 
                FROM User 
                WHERE id = :id
            ');
            $stmt->execute(['id' => $id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            // We return an array directly, not a User object, to control exposed fields
            return $data ?: null;
        }
    }
?>