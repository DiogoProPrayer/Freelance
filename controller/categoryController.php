<?php
    require_once(__DIR__ . '/../database/db.php');
    require_once(__DIR__ . '/../model/categoryClass.php');
    require_once(__DIR__ . '/../model/authenticationClass.php');
    require_once(__DIR__ . '/../templates/filterPage.php');
    
    $auth = Authentication::getInstance();
    $database = Database::getInstance();

    $category = new Category($database);


    $logged = $auth->getUser() ? true : false;
    
    $categories = $category->getAllCategories();
    
   
?>