<?php
    require_once (__DIR__ . '/../database/db.php');
    require_once (__DIR__ . '/../model/messageClass.php');
    require_once (__DIR__ . '/../model/authenticationClass.php');
    require_once (__DIR__ . '/../model/UserRepositoryClass.php');
    require_once (__DIR__ . '/../model/userClass.php');

    date_default_timezone_set('UTC');

    $database = Database::getInstance();
    $auth = Authentication::getInstance();
    $user_id = $auth->getUser();
    $message = new Message($database);
    $userR = new UserRepository($database);

    header('Content-Type: application/json');

    if (!$user_id) {
        http_response_code(401);
        echo json_encode(['error' => 'not logged in']);
        exit;
    }
  
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $action = $_GET['action'] ?? $_POST['action'] ?? '';

    try{
        if ($action === 'fetch'){
            $other = (int) ($_GET['other_id'] ?? 0);
            if (!$other) throw new Exception('missing other_id');

            $user = $userR->getUserbyId($other);

            if (!$user){
                throw new Exception('user not found');
            }

            $messages = $message->getConversation($user_id,$other);

            // convert to ISO 8601 UTC
            foreach ($messages as &$msg) {
                $msg['timeStamp'] = gmdate('c', strtotime($msg['timeStamp']));
            }
            echo json_encode($messages);
            exit;
        }
        if ($action === 'fetch_new') {
            $other = (int) ($_GET['other_id'] ?? 0);
            $after = (int) ($_GET['after']    ?? 0);
            if (!$other) throw new Exception('missing other_id');

            $rows = $message->getNewMessages($user_id,$other,$after);
            // convert to ISO 8601 UTC
            foreach ($rows as &$msg) {
                $msg['timeStamp'] = gmdate('c', strtotime($msg['timeStamp']));
            }
            echo json_encode($rows);
            exit;
        }
        
        if ($action === 'send') {
            $other = (int) ($_POST['other_id'] ?? 0);
            $text  = trim($_POST['text'] ?? '');
            if (!$other || $text === '') throw new Exception('bad data');

            $newId = $message->sendNewMessage($user_id,$other,$text);

            // respond with ISO 8601 UTC
            echo json_encode([
                'ok'        => 1,
                'id'        => $newId,
                'timeStamp' => gmdate('c')
            ]);

            exit;
        }
        
        throw new Exception('invalid action: ' . $action);

    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    }   
?>