<?php
    require_once (__DIR__ . '/../controller/userController.php');
    require_once (__DIR__ . '/../controller/messageController.php');
    require_once (__DIR__ . '/../templates/common.php');
    require_once (__DIR__ . '/../view/messagesView.php');
    
    drawMessagesHeader();
    drawTopBar($status,$isAdmin);         
    drawMessagesSection($conversations,$contact_info,$contact_id,$user_id);
    drawChatArea($contact_id,$contact_info,$user_id);
    drawFooter();

?>