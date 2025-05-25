<?php

    require_once (__DIR__ . '/../model/authenticationClass.php');
    require_once (__DIR__ . '/../database/db.php');
    require_once (__DIR__ . '/../model/serviceImageClass.php');
    require_once (__DIR__ . '/../model/serviceClass.php');
    require_once (__DIR__ . '/../model/tagsClass.php');
    require_once (__DIR__ . '/../model/userRepositoryClass.php');

    $pd = Database::getInstance();
    $auth = Authentication::getInstance();
    $user_id = $auth->getUser();
    
    $service = new Service($pd);
    
    $serviceId = $_GET['id'];
    $serviceInfo = $service->getServiceDetails($serviceId);
    
?>