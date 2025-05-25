<?php
declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once(__DIR__ . '/../database/db.php');
require_once(__DIR__ . '/../model/serviceClass.php');

header('Content-Type: application/json');

$db = Database::getInstance();
$service = new Service($db);

$query = $_GET['query'] ?? '';

if (empty($query)) {
    echo json_encode([]);
    exit;
}

$results = $service->resultsSearchInput($query);
echo json_encode($results);
?>