<?php
    require_once (__DIR__  . '/../model/userRepositoryClass.php');
    require_once (__DIR__ . '/../model/userClass.php');
    require_once (__DIR__ . '/../database/db.php');
    require_once (__DIR__ . '/../model/authenticationClass.php');
    require_once (__DIR__ . '/../model/serviceImageClass.php');
    require_once (__DIR__ . '/../model/serviceClass.php');

    $db = Database::getInstance();

    $auth = Authentication::getInstance();
    $user_id = $auth->getUser();
    $userR = new UserRepository($db);
    $user = $userR->getUserbyId($user_id);
    $service = new Service($db);
    $serviceImage = new ServiceImage($db);
    $services = null;
    $reviews = null;
    $users = null;

    if ($user_id == null || $user->getIsAdmin() == null || $user->getIsAdmin() != 1) {
        // Redirect non-admin users
        header("Location: index.php");
        exit;
    }

    // Get current active tab
    $activeTab = $_GET['tab'] ?? 'users';

    // Fetch data based on active tab
    switch ($activeTab) {
        case 'users':
            $users = $userR->getUsers();
            break;

        case 'services':
            $services = $service->getServices();

            // Get a sample image for each service
            foreach ($services as &$service) {
                $image = $serviceImage->getImagebyService($service['id']);
                $service['image'] = $image ? ('../' . $image) : '../images/default_service.jpg';
            }
            break;

        case 'reviews':
            $reviews = $service->getReviews();
            break;
    }
?>
