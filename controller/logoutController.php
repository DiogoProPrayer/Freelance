<?php

    declare(strict_types=1);
    
    require_once(__DIR__ . '/../model/authenticationClass.php');
    
    $auth = Authentication::getInstance();

    $auth->logout();

    header('Location: /');
?>