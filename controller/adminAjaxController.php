<?php

    require_once (__DIR__ . "/../database/db.php");
    require_once (__DIR__ . '/../model/authenticationClass.php');
    require_once (__DIR__ . '/../model/userRepositoryClass.php');
    require_once (__DIR__ . '/../model/serviceClass.php');

    $db = Database::getInstance();

    $userR = new UserRepository($db);
    $service = new Service($db);
    
    // Handle AJAX requests
    if (isset($_POST['action'])) {
        header('Content-Type: application/json');

        switch ($_POST['action']) {
            case 'delete_user':
                if (isset($_POST['userId'])) {
                    try {
                        $success = $userR->deleteUser($_POST['userId']);
                        echo json_encode(['success' => $success]);

                    } catch (PDOException $e) {
                        echo json_encode(['success' => $success, 'message' => $e->getMessage()]);
                    }
                }
                exit;

            case 'promote_user':
                if (isset($_POST['userId'])) {
                    try {
                        $success = $userR->elevateUsertoAdmin($_POST['userId']);
                        echo json_encode(['success' => $success]);
                    } catch (PDOException $e) {
                        echo json_encode(['success' => $success, 'message' => $e->getMessage()]);
                    }
                }
                exit;

            case 'delete_service':
                if (isset($_POST['serviceId'])) {
                    try {
                        $success = $service->deleteService($_POST['serviceId']);
                        echo json_encode(['success' => $success]);
                    } catch (PDOException $e) {
                        echo json_encode(['success' => $success, 'message' => $e->getMessage()]);
                    }
                }
                exit;

            case 'delete_review':
                if (isset($_POST['reviewId'])) {
                    try {
                        $success = $service->deleteReview($_POST['reviewId']);
                        echo json_encode(['success' => $success]);
                    } catch (PDOException $e) {
                        echo json_encode(['success' => $success, 'message' => $e->getMessage()]);
                    }
                }
                exit;
        }
    }
?>