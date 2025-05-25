<?php
    declare(strict_types=1);
    require_once(__DIR__ . '/../model/authenticationClass.php');

    $auth = Authentication::getInstance();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $userStatus = $_POST['userStatus'];

        if ($password !== $confirm_password) {
            $_SESSION['auth_error'] = 'Passwords do not match.';
            $_SESSION['show_auth'] = 'register';
            header('Location: /');
            exit;
        }
        
        $flag = $auth->verificationRegister($username,$email);
        
        if (!$flag){
            $auth->register($name,$username,$email,$password,$userStatus);
        }
        header('Location: /');
    }
    else {
        header('Location: /');
        exit;
    }
?>
