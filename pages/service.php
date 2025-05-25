<?php
    declare(strict_types=1);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    require_once (__DIR__ . '/../controller/userController.php');
    require_once (__DIR__ . '/../templates/common.php');
    require_once (__DIR__ . '/../controller/serviceDetailsController.php');
    require_once (__DIR__ . '/../view/serviceDetailsView.php');

    drawServiceHeader();
    drawTopBar($status,$isAdmin);         
    drawViewService($serviceInfo,$imagesService);
    drawServiceDetails($serviceInfo,$tagsService,$isOwner,$userId,$relatedServices);
    drawFooter();
?>