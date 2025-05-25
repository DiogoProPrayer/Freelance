<?php
    require_once (__DIR__ . '/../model/userClass.php');
    require_once (__DIR__ . '/../model/userRepositoryClass.php');
    require_once (__DIR__ . '/../database/db.php');
    require_once (__DIR__ . '/../model/authenticationClass.php');
    require_once (__DIR__ . '/../controller/userController.php');
    require_once (__DIR__ . '/../model/serviceClass.php');

    $db = Database::getInstance();

    $auth = Authentication::getInstance();
    $userRep = new UserRepository($db);
    $service = new Service($db);
    $user = $userRep->getUserbyId($auth->getUser());
    $services = [];
    $sellerOrders = [];
    $orders = [];

    if ($auth->getUser() === 'null'){
        header("Location: index.php");
        exit;
    } 

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_status_submit'])) { // Changed to look for a submit button
        $newStatus = ($_POST['toggle_status'] === 'seller') ? 'seller' : 'client';

        $userRep->setUserStatus($newStatus,$user->getUserStatus(),$auth->getUser());

        // Update session
        $_SESSION['userStatus'] = $newStatus;
        $status = $newStatus;

        // Redirect to refresh the page with updated status
        header("Location: /pages/profile.php?status_updated=true");
        exit;
    }
    // Handle profile update if form submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
        echo 2;
        $name = $_POST['name'] ?? $user['name'];
        $username = $_POST['username'] ?? $user['username'];
        $email = $_POST['email'] ?? $user['email'];
        $country = $_POST['country'] ?? $user['country'];
        $phoneNumber = $_POST['phoneNumber'] ?? $user['phoneNumber'];

        $userRep->setUserInfo($name,$username,$email,$country,$phoneNumber,$auth->getUser());

        // Handle profile image upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/profile/';

            // Create directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = $userId . '_' . time() . '_' . basename($_FILES['profile_image']['name']);
            $targetFile = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
                // Delete old profile image if it exists and is not the default
                if (!empty($user->getProfileImage()) && $user->getProfileImage() !== '/images/default_user.jpg' && file_exists($user->getProfileImage())) {
                    @unlink($user->getProfileImage());
                }
                
                $service->uploadImage($auth->getUser(),$targetFile);
                
            }
        }
        // Redirect to refresh the page with updated info
        header("Location: /pages/profile.php?updated=true");
        exit;
    }


    if ($user->getUserStatus() === 'seller'){
        $services = $service->getServicesbySeller($auth->getUser());
        $sellerOrders = $service->getOrdersSeller($auth->getUser());
    }

    $orders = $service->getOrdersClient($auth->getUser());
?>