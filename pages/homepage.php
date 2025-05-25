<?php
    declare(strict_types=1);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    require_once(__DIR__ . '/../controller/userController.php');
    require_once(__DIR__ . '/../templates/common.php');
    require_once(__DIR__ . '/../view/homepageView.php');
    require_once(__DIR__ . '/../controller/categoryController.php');
    require_once(__DIR__ . '/../controller/sellerControler.php');   
    // Added for fetching popular services
    require_once(__DIR__ . '/../database/db.php');
    require_once(__DIR__ . '/../model/serviceClass.php');

    $db = Database::getInstance();
    $serviceModel = new Service($db);
    $popularServices = $serviceModel->getPopularServices();
    // Note: $categories and $topsellers are assumed to be populated by their respective controllers or a general context script.

    drawHomepageHeader();         
    drawTopBar($status,$isAdmin);         
    drawNavCategories($categories,$logged);
    drawSellers($topsellers);
    drawServices($popularServices);
    drawPopup();
    drawFooter();         
?>


