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
            // Atualiza a coluna userStatus
            $stmt = $this->database->prepare("UPDATE User SET userStatus = :userStatus WHERE id = :userID");

            // Bind dos parâmetros corretamente
            $stmt->bindParam(":userStatus", $new_userStatus, PDO::PARAM_STR);
            $stmt->bindParam(":userID", $userID, PDO::PARAM_INT);

            // Executa a consulta
            if ($stmt->execute()) {
                echo "Status atualizado com sucesso para o usuário ID: $userID";
            } else {
                echo "Erro ao atualizar: " . $stmt->errorInfo()[2]; // Mostra o erro completo da execução
            }
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
    }
?>