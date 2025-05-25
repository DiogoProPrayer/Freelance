<?php
    require_once (__DIR__ . '/../controller/userController.php');
    require_once (__DIR__ . '/../controller/adminController.php');
    require_once (__DIR__ . '/../templates/common.php');
    require_once (__DIR__ . '/../view/adminView.php');

    drawAdminHeader(); // Changed from drawHeader()
    drawTopBar($status,$isAdmin);  
    drawAdminPage($activeTab);
    drawAdminPanel($activeTab,$users,$services,$reviews); 
    drawConfirmModel();      
    drawFooter();
?>