    <?php
        require_once (__DIR__ . '/../database/db.php');
        require_once (__DIR__ . '/../model/authenticationClass.php');
        require_once (__DIR__ . '/../model/userClass.php');
        require_once (__DIR__ . '/../model/userRepositoryClass.php');
        require_once (__DIR__ . '/../model/messageClass.php');

        $pdo = Database::getInstance();
        $auth = Authentication::getInstance();
        $userR = new userRepository($pdo);
        $user_id = $auth->getUser();
        $message = new Message($pdo);

        if (!$user_id){
            header("Location: index.php");
            exit;
        }

        $contact_id = isset($_GET['contact_id']) ? (int)$_GET['contact_id'] : null;

        $contact_info = null;

        if ($contact_id) {

            $contact_info = $userR->getUserbyId($contact_id);

            if (!$contact_info) {
                $contact_id = null;
            }

        } 
        $conversations = [];
        try {
            $conversations = $message->getListofConversations($user_id);
        } catch (PDOException $e) {
            echo "Error fetching conversations: " . $e->getMessage();
            exit;
        }
        
        
    ?>