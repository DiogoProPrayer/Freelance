<?php 
    require_once(__DIR__ . '/../database/db.php');
    require_once(__DIR__ . '/../model/userClass.php');
    require_once(__DIR__ . '/../model/userRepositoryClass.php');
    require_once(__DIR__.'/../model/serviceClass.php');

    $pd = Database::getInstance();
    $user_repository = new UserRepository($pd);
    $service = new Service($pd);
    $topsellers = $user_repository->getTopSellers();
    $popularServices= $service->getPopularServices();    
?>