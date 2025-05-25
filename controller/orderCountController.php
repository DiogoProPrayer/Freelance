<?php
    require_once(__DIR__ . '/../database/db.php');
    require_once(__DIR__ . '/../model/serviceClass.php');

    header('Content-Type: application/json');

    $db = Database::getInstance();
    $service = new Service($db);
    $orderId = $_GET['order_id'] ?? 0;

    $order = $service->getOrderServicebyId($orderId);

    if (!$order) {
        http_response_code(404);
        echo json_encode(['error' => 'Order not found']);
        exit;
    }

    // Atualiza os dias restantes (se passaram 24h)
    if ($order['remaining_days'] > 0) {
        $lastUpdated = new DateTime($order['last_updated']);
        $now = new DateTime();
        $diff = $now->diff($lastUpdated)->days;
        
        if ($diff >= 1) {
            $newRemainingDays = max(0, $order['remaining_days'] - $diff);
            
            // Atualiza no banco de dados (incluindo o status se necessário)
            $service->updateOrderDeliveryDays($orderId, $newRemainingDays);
            
            // Atualiza os dados para retorno
            $order['remaining_days'] = $newRemainingDays;
            $order['last_updated'] = $now->format('Y-m-d H:i:s');
            $order['orderStatus'] = ($newRemainingDays <= 0) ? 'Delayed' : $order['orderStatus'];
        }
    }

    echo json_encode($order);
?>