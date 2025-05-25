<?php
    require_once(__DIR__ . '/../database/db.php');
    require_once(__DIR__ . '/../model/serviceClass.php');


    $db = Database::getInstance();

    $service = new Service($db);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
        $order_id = $_POST['order_id'];
        $action = $_POST['action'];
        
        if ($action == 'cancel'){
            $sucess = $service->deleteOrder($order_id);

            if ($sucess){
                header("Location: ../pages/profile.php");
                exit();
            }else {
                echo "Error";
                
            }
        }
        else if ($action == 'deliver'){
            $sucess = $service->deliverOrder($order_id);

            if ($sucess){
                header("Location: ../pages/profile.php");
                exit();

            }else {
                echo "Error";
                
            }
        }
        
    }
?>