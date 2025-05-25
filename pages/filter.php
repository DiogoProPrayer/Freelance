<?php
declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once(__DIR__ . '/../database/db.php');
require_once(__DIR__ . '/../model/categoryClass.php');
require_once(__DIR__ . '/../model/authenticationClass.php');
require_once(__DIR__ . '/../templates/filterPage.php');

// Inicialização de objetos
$auth = Authentication::getInstance();
$database = Database::getInstance();
$category = new Category($database);

// Verificar se o usuário está logado
$logged = $auth->getUser() ? true : false;

// Verificar a existência da categoria no GET
$categoryName = $_GET['category'] ?? null;

// Verificar se a categoria foi definida
if ($categoryName === null || $categoryName === '') {
    echo "Categoria não definida ou inválida.";
    exit;
}

// Obter ID da categoria
$categorie = $category->getCategoryIdbyName($categoryName);

// Verificar se a categoria existe
if ($categorie === null) {
    echo "Categoria não encontrada.";
    exit;
}

// Obter serviços da categoria
$serviceList = $category->getServicebyCategory($categorie);

// Renderizar a página
drawTitle($categoryName);
?>