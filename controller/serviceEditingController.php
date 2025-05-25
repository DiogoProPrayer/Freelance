<?php

require_once(__DIR__ . '/../model/authenticationClass.php');
require_once(__DIR__ . '/../database/db.php');
require_once(__DIR__ . '/../model/serviceImageClass.php');
require_once(__DIR__ . '/../model/serviceClass.php');
require_once(__DIR__ . '/../model/tagsClass.php');
require_once(__DIR__ . '/../model/userClass.php');
require_once(__DIR__ . '/../model/categoryClass.php');
require_once(__DIR__ . '/../model/userRepositoryClass.php');


$pd = Database::getInstance();
$auth = Authentication::getInstance();
$userId = $auth->getUser();
$userRep = new UserRepository($pd);
if($userId === null) {
    header("Location: /pages/homepage.php");
    exit;
}
$user = $userRep->getUserbyId($userId);
$service = new Service($pd);
$serviceImages = new ServiceImage($pd);
$tags = new Tags($pd);
$category = new Category($pd);
$categories = $category->getAllCategories();


$db = Database::getInstance();
$category = new Category($db);
$tag = new Tags($db);
$service = new Service($db);
$serviceImages = new ServiceImage($db);
$userRep = new UserRepository($db);

$categories = $category->getAllCategories();
$tags = $tag->getAllTagsWithCategories();
$auth = Authentication::getInstance();
$a = $auth->getUser();
$user = $userRep->getUserbyId($a);

if (!$auth || ($user->getUserStatus()) !== 'seller') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    error_log("POST request received with action: " . $_POST['action']);
    if (isset($_POST['imageId'])) {
        error_log("Image ID: " . $_POST['imageId']);
        $imageid = intval($_POST['imageId']);
        $serviceImages->deleteServiceImage($imageid);
        echo json_encode(['status' => 'success']);
        exit;
    } else {
        error_log("Image ID not provided in POST data.");
    }
    echo json_encode(['status' => 'error']);
    exit;
}

// Check if service ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /pages/homepage.php");
    exit;
}
$serviceId = (int)$_GET['id'];
$serviceInfo = $service->getServiceDetailsWithCategoryId($serviceId);

if (!$serviceInfo) {
    header("Location: /pages/homepage.php");
    exit;
}

$imagesService = $serviceImages->getServiceImages($serviceId);
$tagsService = $tag->getTagsID($serviceId);
$relatedServices = $service->recommendServices($serviceId, $serviceInfo['category']);

$isOwner = ($userId && $userId == $serviceInfo['seller_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['cancel'])) {
        header('Location: ../pages/profile.php'); // Corrected path
        exit;
    }
    if (isset($_POST['deleteService'])) { // From editService.php form
        $service->deleteService($serviceId);
        header('Location: ../pages/profile.php?service_deleted=true'); // Corrected path
        exit;
    }
    if (isset($_POST['action']) && $_POST['action'] === 'delete_from_profile') { // From profileView.php delete button
        // Ensure serviceId is fetched from GET parameter as the form action includes it
        $serviceIdToDelete = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($serviceIdToDelete > 0 && $isOwner) { // Double check ownership if not already done by a higher level check
            // Potentially add more checks here: e.g., ensure the user owns this serviceIdToDelete
            // For now, assuming $isOwner check (which uses $serviceId from GET for the page load) is sufficient
            // if $serviceId (from page load) matches $serviceIdToDelete.
            if ($serviceId === $serviceIdToDelete) {
                $service->deleteService($serviceIdToDelete);
                header('Location: ../pages/profile.php?service_deleted_profile=true'); // Redirect to profile page
                exit;
            } else {
                // ID mismatch, potential tampering or error
                header('Location: ../pages/profile.php?error=delete_id_mismatch');
                exit;
            }
        } else {
            // Not owner or invalid ID
            header('Location: ../pages/profile.php?error=delete_unauthorized');
            exit;
        }
    }
    // Detect if entire POST was dropped due to size
    if (empty($_POST) && !empty($_SERVER['CONTENT_LENGTH']) && $_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) { // Added more conditions to avoid conflict
        $errors[] = "Upload failed: total size exceeds server limit.";
    } else {
        // Gather inputs safely
        $title = isset($_POST['title']) && trim($_POST['title']) !== '' ? trim($_POST['title']) : $serviceInfo['title'];
        $description = isset($_POST['description']) && trim($_POST['description']) !== '' ? trim($_POST['description']) : $serviceInfo['description'];
        $price = isset($_POST['price']) && $_POST['price'] !== '' ? floatval($_POST['price']) : $serviceInfo['price'];
        $categoryId = isset($_POST['category']) && $_POST['category'] !== '' ? intval($_POST['category']) : $serviceInfo['category_id'];
        $deliverTime = isset($_POST['deliverTime']) && $_POST['deliverTime'] !== '' ? intval($_POST['deliverTime']) : $serviceInfo['deliverTime'];
        $selectedTags = $_POST['tags'] ?? $tagsService;
        // Validate required fields
        if ($title === '') {
            $errors[] = "Title is required.";
        }
        if ($description === '') {
            $errors[] = "Description is required.";
        }
        if ($price === null || $price < 0) {
            $errors[] = "Price must be a non-negative number.";
        }
        if ($deliverTime === null || $deliverTime < 1) {
            $errors[] = "Delivery Time must be at least 1 day.";
        }

        if (empty($errors)) {
            $result = $service->updateService($serviceId, $title, $description, $price, $categoryId, $deliverTime);
            if ($result) {
                if (!empty($selectedTags)) {
                    $tag->updateTags($serviceId, $selectedTags);
                }
                if (!empty($_FILES['images']['name'][0])) {
                    $images = $_FILES['images'];
                    $serviceImages->uploadServiceImages($serviceId, $images);
                }

                header("Location: ../pages/profile.php?service_updated=true"); // Corrected path
                exit;
            }
            // If $result was false, or other error, consider an error redirect
            header("Location: ../pages/editService.php?id=" . $serviceId . "&error=update_failed"); // Redirect back to edit page with error
            exit;
        }
    }
}
