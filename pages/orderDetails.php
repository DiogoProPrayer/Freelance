<?php
    require_once (__DIR__ . '/../controller/userController.php');
    require_once (__DIR__ . '/../controller/orderDetailsController.php');
    require_once (__DIR__ . '/../templates/common.php');
    require_once (__DIR__ . '/../view/orderDetailsView.php');

    drawOrderDetailsHeader($serviceInfo); // Changed from drawHeader()
    drawTopBar($status,$isAdmin); 
    drawViewService($serviceInfo,$imagesService);
    drawOrderDetails($orderInfo,$tagsService,$user_id,$serviceInfo);        
    drawFooter();
?>