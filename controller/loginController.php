<?php
declare(strict_types=1);

require_once(__DIR__ . '/../model/authenticationClass.php');

$auth = Authentication::getInstance();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $success = $auth->login($username, $password);

    if ($success) {
        echo json_encode(['success' => true]);
        exit;
    }
    else {  
        $errors = [];
        
        $userExists = $auth->checkUserExists($username);
        if (!$userExists) {
            $errors['username'] = 'Username not found';
        } else {
            $errors['password'] = 'Incorrect password';
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'errors' => $errors
        ]);
        exit;
    }
}
?>