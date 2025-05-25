<?php
    require_once (__DIR__ . '/../model/userClass.php');
    require_once (__DIR__ . '/../model/userRepositoryClass.php');
    require_once (__DIR__ . '/../database/db.php');
    require_once (__DIR__ . '/../model/authenticationClass.php');


    $database = Database::getInstance();
    $authentication = Authentication::getInstance();
    $auth = $authentication->getUser();
    $status = 'client';
    $isAdmin = 0;
    
    if ($auth != null){
        $user_repository = new UserRepository($database);
        $user = $user_repository->getUserbyId($auth);

        if ($user != null){
            $status = $user->getUserStatus();
            $isAdmin = $user->getIsAdmin();
        } 
    }   
?>