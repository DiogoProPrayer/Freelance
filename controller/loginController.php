<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../model/authenticationClass.php');

    $auth = Authentication::getInstance();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $auth->login($username,$password);
    }

?>