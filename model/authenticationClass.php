<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../database/db.php');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    class Authentication {
        private PDO $db; 
        private static ?Authentication $instance = null;

        public static function getInstance(): Authentication {
            if (self::$instance === null) {
                self::$instance = new Authentication();
            }
            return self::$instance;
        }


        public function __construct(){
            session_start();
            $this->db = Database::getInstance(); 
        }

        public function checkUserExists(string $username){
            $stmt = $this->db->prepare('SELECT id FROM User WHERE username = ?');
            $stmt->execute([$username]);
            return $stmt->fetch() !== false;
        }
        
        public function login(string $username,string $password){
            $stmt = $this->db->prepare('SELECT * FROM User WHERE username = :username');
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['passwordHash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                return true;
            } else {
                return false;
            }
        }

        public function logout(){
            session_destroy();
        }

        public function register(string $name, string $username, string $email, string $password,string $userStatus){
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare('INSERT INTO User (name, username, email, passwordHash, userStatus) VALUES (:name, :username, :email, :passwordHash, :userStatus)');
            $stmt->execute([
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'passwordHash' => $passwordHash,
                'userStatus' =>  $userStatus,
            ]);
            
            $_SESSION['user_id'] = $this->db->lastInsertId();
            $_SESSION['username'] = $username;
        }

        
        public function verificationRegister(string $username, string $email){
            $stmt = $this->db->prepare('SELECT * FROM User WHERE username = :username OR email = :email');
            $stmt->execute(['username' => $username, 'email' => $email]);
            
            return $stmt->fetch();
        }

        public function getUser(){
            return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        }
        public function getProfileImage(){
            $stmt = $this->db->prepare('SELECT profileImage FROM User WHERE id = :id');
            $stmt->execute(['id' => $_SESSION['user_id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['profileImage'] ?? '/images/default_user.jpg';
        }
    }
?>
