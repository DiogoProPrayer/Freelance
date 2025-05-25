<?php
    require_once (__DIR__ . '/../controller/paymentController.php');
    require_once (__DIR__ . '/../view/paymentView.php');
    

    drawHeaderPayment();
    drawPaymentService($serviceInfo,$user_id);

?>