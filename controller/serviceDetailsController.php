<?php

    require_once (__DIR__ . '/../model/authenticationClass.php');
    require_once (__DIR__ . '/../database/db.php');
    require_once (__DIR__ . '/../model/serviceImageClass.php');
    require_once (__DIR__ . '/../model/serviceClass.php');
    require_once (__DIR__ . '/../model/tagsClass.php');
    require_once (__DIR__ . '/../model/userClass.php');
    require_once (__DIR__ . '/../model/userRepositoryClass.php');

    $pd = Database::getInstance();
    $auth = Authentication::getInstance();
    $userId = $auth->getUser();
    if ($userId === null) {
        header("Location: /pages/homepage.php");
        exit;
    }
    $userRep = new UserRepository($pd);
    $user = $userRep->getUserbyId($userId);
    $service = new Service($pd);
    $serviceImages = new ServiceImage($pd);
    $tags = new Tags($pd);

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
        error_log("POST request received with action: " . $_POST['action']);
        if (isset($_POST['imageId'])) {
            error_log("Image ID: " . $_POST['imageId']);
            $imageid =intval($_POST['imageId']);
            $serviceImages->deleteServiceImage($imageid);
            echo json_encode(['status' => 'success']);
            exit;
        } else {
            error_log("Image ID not provided in POST data.");
        }
            echo json_encode(['status' => 'error']);
        exit;
    }
    if($_SERVER['REQUEST_METHOD']=='POST'&& isset($_POST['action'])&& $_POST['action']==='addImage'){

    }

    // Check if service ID is provided
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header("Location: /pages/homepage.php");
        exit;
    }  
    $serviceId = (int)$_GET['id'];
    $serviceInfo = $service->getServiceDetails($serviceId);

    if (!$serviceInfo) {
        header("Location: /pages/homepage.php");
        exit;
    }

    $imagesService = $serviceImages->getServiceImages($serviceId);

    if (empty($imagesService)) {
        $imagesService[] = ['image' => '/images/default_service.jpg'];
    }

    $tagsService = $tags-> getTagsService($serviceId);
    $relatedServices = $service->recommendServices($serviceId,$serviceInfo['category']);

    $isOwner = ($userId && $userId == $serviceInfo['seller_id']);



?>