<?php
declare(strict_types=1);

// Desativar a exibição de erros HTML
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

// Definir o header como JSON
header('Content-Type: application/json');

require_once(__DIR__ . '/../database/db.php');
require_once(__DIR__ . '/../model/categoryClass.php');
require_once(__DIR__ . '/../model/serviceClass.php');
require_once(__DIR__ . '/../model/userRepositoryClass.php');

$database = Database::getInstance();
$category = new Category($database);
$service = new Service($database);
$userRepository = new UserRepository($database);

// Verificar se a categoria foi enviada
$categoryName = $_POST['category'] ?? null;

if (!$categoryName) {
    echo json_encode(['success' => false, 'message' => 'Categoria não especificada.']);
    exit;
}

// Obter ID da categoria
$categorie = $category->getCategoryIdbyName($categoryName);

if (!$categorie) {
    echo json_encode(['success' => false, 'message' => 'Categoria não encontrada.']);
    exit;
}

// Obter serviços da categoria
$serviceList = $service->getServicebyCategory($categorie);

// Retornar a resposta JSON
echo json_encode(['success' => true, 'services' => $serviceList]);
?>
