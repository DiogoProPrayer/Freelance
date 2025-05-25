<?php
    declare(strict_types=1);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    require_once(__DIR__ . '/../controller/userController.php');
    require_once(__DIR__ . '/../templates/common.php');
    require_once(__DIR__.'/../controller/serviceEditingController.php');
    require_once(__DIR__.'/../view/editServiceView.php');



    drawEditServiceHeader();
    drawTopBar($status);
    drawEditService($serviceInfo,$imagesService,$categories,$tags,$tagsService);
    drawFooter();
?>
