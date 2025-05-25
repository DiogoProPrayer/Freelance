<?php
    declare(strict_types=1);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    require_once (__DIR__ . '/../controller/userController.php');
    require_once (__DIR__ . '/../controller/categoryController.php');
    require_once (__DIR__ . '/../templates/common.php');
    
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    if ($isAjax){
        require_once (__DIR__ . '/../controller/filtersResultsController.php');
        require_once(__DIR__ . '/../view/filterResultsView.php');
        drawFilteredServices($status,$serviceList,$database);
    }
    else {
        require_once (__DIR__ . '/../controller/filterController.php');
        require_once(__DIR__ . '/../view/filterResultsView.php');
        drawHeader();
        drawTopBar($status,$isAdmin);         
        drawTitle($categoryName);
        drawFilterOptions($categories);
        drawFilteredServices($status,$serviceList,$database);
        drawFooter();
    }
?>