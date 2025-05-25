<?php
declare(strict_types=1);

require_once(__DIR__ . '/../model/userRepositoryClass.php'); // Changed from userClass.php
require_once(__DIR__ . '/../model/serviceClass.php'); 

class PublicProfileController {
    private PDO $db;
    private UserRepository $userRepository; // Changed from User to UserRepository
    private Service $serviceModel;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->userRepository = new UserRepository($db); // Instantiate UserRepository
        $this->serviceModel = new Service($db); 
    }

    public function getUserProfileData(int $user_id): array {
        $userData = $this->userRepository->getPublicProfileById($user_id); // Use UserRepository method
        $userServices = [];

        if ($userData) {
            // Only fetch services if user exists and is a seller or if services are always public regardless of status
            $userServices = $this->serviceModel->getServicesBySellerId($user_id); // Use the renamed and enhanced method
        }

        return [
            'user' => $userData,
            'services' => $userServices
        ];
    }
}
?>
