<?php 
    require_once(__DIR__ . '/../model/authenticationClass.php');
    require_once(__DIR__ . '/../model/categoryClass.php');
    require_once(__DIR__ . '/../database/db.php');
    require_once(__DIR__ . '/../model/serviceClass.php');
    require_once(__DIR__ . '/../model/tagsClass.php');
    require_once(__DIR__ . '/../model/serviceImageClass.php');

    $db = Database::getInstance();
    $category = new Category($db);
    $tag = new Tags($db);
    $service = new Service($db);
    $serviceImages = new ServiceImage($db);
    $userRep = new UserRepository($db);

    $auth = Authentication::getInstance();
    $a = $auth->getUser();
    $user = $userRep->getUserbyId($a);

    if (!$auth || ($user->getUserStatus()) !== 'seller') {
        header("Location: index.php");
        exit;
    }

    $categories = $category->getAllCategories();

    $tags = $tag->getAllTagsWithCategories();

    $errors = [];

    // Handle form submission only if PHP accepted the POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Detect if entire POST was dropped due to size
        if (empty($_POST) && !empty($_SERVER['CONTENT_LENGTH'])) {
            $errors[] = "Upload failed: total size exceeds server limit.";
        } else {
            // Gather inputs safely
            $title        = trim($_POST['title']       ?? '');
            $description  = trim($_POST['description'] ?? '');
            $price        = $_POST['price']    !== null ? floatval($_POST['price']) : null;
            $categoryId   = $_POST['category'] !== null ? intval($_POST['category']) : null;
            $deliverTime  = $_POST['deliverTime'] !== null ? intval($_POST['deliverTime']) : null;
            $selectedTags = $_POST['tags'] ?? [];

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

            // Validate category
            $validCatIds = array_column($categories, 'id');
            if (!in_array($categoryId, $validCatIds, true)) {
                $errors[] = "Please select a valid category.";
            }

            // If no validation errors, insert into DB
            if (empty($errors)) {
                $serviceId = $service->storeService($a,$title,$description,$price,$categoryId,$deliverTime);

                // Insert tags
                if (!empty($selectedTags)) {
                    $tag->insertTags($serviceId,$selectedTags);
                }

                // Handle image uploads
                if (!empty($_FILES['images']['name'][0])) {
                    $images = $_FILES['images'];
                    $serviceImages->uploadServiceImages($serviceId,$images);
                }

                header("Location: profile.php?service_created=true");
                exit;
                
            }
        }
    }
 
?>