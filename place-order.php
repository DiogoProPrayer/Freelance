<?php
// place-order.php
session_start();
require_once "database/db.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['service_id']) || !is_numeric($_POST['service_id'])) {
    header("Location: index.php");
    exit;
}

$userId = $_SESSION['user_id'];
$serviceId = (int)$_POST['service_id'];

// Fetch service information
$serviceStmt = $pdo->prepare("SELECT * FROM Service WHERE id = :id");
$serviceStmt->execute(['id' => $serviceId]);
$service = $serviceStmt->fetch(PDO::FETCH_ASSOC);

// If service doesn't exist or user is trying to order their own service
if (!$service || $service['seller'] == $userId) {
    header("Location: service.php?id=" . $serviceId . "&error=invalid");
    exit;
}

// Check if already has an active order for this service
$checkOrderStmt = $pdo->prepare("
    SELECT id FROM ServiceOrder 
    WHERE service = :service_id AND buyer = :buyer_id 
    AND orderStatus IN ('PENDING', 'IN_PROGRESS')
");
$checkOrderStmt->execute([
    'service_id' => $serviceId,
    'buyer_id' => $userId
]);

if ($checkOrderStmt->rowCount() > 0) {
    // User already has an active order for this service
    header("Location: service.php?id=" . $serviceId . "&error=already_ordered");
    exit;
}

// Create new order
try {
    $orderStmt = $pdo->prepare("
        INSERT INTO ServiceOrder (service, buyer, orderStatus, amount, order_date)
        VALUES (:service, :buyer, 'PENDING', :amount, NOW())
    ");

    $orderStmt->execute([
        'service' => $serviceId,
        'buyer' => $userId,
        'amount' => $service['price']
    ]);

    $orderId = $pdo->lastInsertId();

    // Redirect to order details page
    header("Location: order.php?id=" . $orderId . "&new=true");
    exit;
} catch (PDOException $e) {
    // Handle error
    header("Location: service.php?id=" . $serviceId . "&error=failed");
    exit;
}
