<?php
declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once(__DIR__ . '/../templates/common.php'); // For drawTopBar, drawFooter
require_once(__DIR__ . '/../database/db.php');     // For Database connection
require_once(__DIR__ . '/../controller/publicProfileController.php');
require_once(__DIR__ . '/../view/publicProfileView.php');
require_once(__DIR__ . '/../controller/userController.php'); // For $status, $isAdmin for top bar

// Get user_id from URL
$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

if ($user_id <= 0) {
    // Handle invalid or missing user_id - e.g., redirect to homepage or show an error
    // For now, just a simple message, but drawHeader and Footer for consistency
    drawPublicProfileHeader("Error"); // Assuming a generic title for error
    drawTopBar($status, $isAdmin); // $status and $isAdmin from userController.php
    echo "<main style='text-align:center; padding: 2rem;'>User ID not provided or invalid.</main>";
    drawFooter();
    exit;
}

$db = Database::getInstance();
$publicProfileController = new PublicProfileController($db);
$profileData = $publicProfileController->getUserProfileData($user_id);

if (!$profileData['user']) {
    // Handle user not found
    drawPublicProfileHeader("User Not Found");
    drawTopBar($status, $isAdmin);
    echo "<main style='text-align:center; padding: 2rem;'>User not found.</main>";
    drawFooter();
    exit;
}

// Draw the page
drawPublicProfileHeader($profileData['user']['username'] ?? 'Public Profile');
drawTopBar($status, $isAdmin); // $status, $isAdmin from userController.php context
drawPublicProfileDetails($profileData['user'], $profileData['services'], $db); // Passing $db for service card image fetching
drawFooter();

?>
