<?php

    require_once (__DIR__ . '/../database/db.php');
    require_once (__DIR__ . '/../model/authenticationClass.php');
    require_once (__DIR__ . '/../model/serviceImageClass.php');
    require_once (__DIR__ . '/../model/tagsClass.php');
    require_once (__DIR__ . '/../model/serviceClass.php');

    $db = Database::getInstance();

    $auth = Authentication::getInstance();
    $service = new Service($db);
    $tags = new Tags($db);
    $user_id = $auth->getUser();
    $serviceImages = new ServiceImage($db);
    $order_id = $_GET['id'];


    $orderInfo = $service->getOrderServicebyId($order_id);

    $service_id = $orderInfo['service'];

    $serviceInfo = $service->getServiceDetails($service_id);

    $imagesService = $serviceImages->getServiceImages($service_id);

    if (empty($imagesService)) {
        $imagesService[] = '../images/default_service.jpg';
    }

    $tagsService = $tags->getTagsService($service_id);
?>