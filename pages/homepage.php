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

    drawHomepageHeader();         
    drawTopBar($status);         
    drawNavCategories($categories,$logged);
    drawSellers($topsellers);
    drawServices($popularServices);
    drawPopup();
    drawFooter();         
?>


