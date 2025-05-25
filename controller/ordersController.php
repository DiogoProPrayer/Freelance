<?php
    declare(strict_types=1);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    require_once(__DIR__ . '/../database/db.php');
    require_once(__DIR__ . '/../model/authenticationClass.php');
    require_once (__DIR__ . '/../model/serviceClass.php');

    $db = Database::getInstance();
    $service = new Service($db);
    
    $service_id = (int) $_POST['service_id'];
    $buyer_id = (int) $_POST['user_id'];

    $status = 'IN_PROGRESS';
    $serviceInformation = $service->getServiceDetails($service_id);
    
    $service->createServiceOrder($service_id, $buyer_id, $status);

    header('Location: ../pages/profile.php');
?>