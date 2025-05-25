<?php
    declare(strict_types=1);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    require_once(__DIR__ . '/../controller/userController.php');
    require_once(__DIR__ . '/../templates/common.php');
    require_once(__DIR__ . '/../view/serviceView.php');
    require_once(__DIR__ . '/../view/profileView.php');
    require_once(__DIR__ . '/../controller/profileController.php');

    drawProfileHeader();
    drawTopBar($status);
    drawProfileHead($status,$user);
    drawEditForm($user);
    drawServiceCard($status,$services,$db);
    drawOrderStatus($status,$sellerOrders);
    drawFooter();

?>