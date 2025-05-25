<?php
    declare(strict_types=1);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    require_once(__DIR__ . '/../database/db.php');
    require_once (__DIR__ . '/../model/userClass.php');
    require_once (__DIR__ . '/../model/userRepositoryClass.php');
    require_once(__DIR__ . '/../model/categoryClass.php');
    require_once(__DIR__ . '/../model/authenticationClass.php');
    require_once (__DIR__ . '/../model/serviceClass.php');

    $auth = Authentication::getInstance();
    $user_id = $auth->getUser();
    $database = Database::getInstance();
    $category = new Category($database);
    $service = new Service($database);
    $userRepository = new UserRepository($database);
    $user = $userRepository->getUserbyId($user_id);
    $status = $user->getUserStatus();
    $logged = $auth->getUser() ? true : false;

    $max = $_GET['max'] ?? null;
    $minRating = $_GET['rat'] ?? null;
    $minOrders = $_GET['ord'] ?? null;
    $categories = $_GET['categories'] ?? [];
    $sort = $_GET['sort'] ?? 'none';
    $order = $_GET['order'] ?? 'none';

    $checkAsc = 0;

    if (!empty($categories)) {
        $categoryIds = [];
        foreach ($categories as $categor) {
            $id = $category->getCategoryIdbyName($categor);
            if ($id !== null) {
                $categoryIds[] = $id;
            }
        }
        $categories = $categoryIds;
    }

    $serviceList = [];

    if ($order == "Ascendent"){
        $order = 'asc';
    }
    else if ($order == "Descendent"){
        $order = 'desc';
    }
    
    $serviceList = $service->filterServices($categories, $max, $minRating, $minOrders, $sort, $order);
?>