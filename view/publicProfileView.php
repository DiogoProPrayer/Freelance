<?php
declare(strict_types=1);

// Function to draw the header for the public profile page
function drawPublicProfileHeader(string $username) { ?>
    <!DOCTYPE html>
    <html lang='en-US'>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title><?php echo htmlspecialchars($username); ?>'s Profile - Freelance</title>
        <link rel="icon" href="/images/logo2.png">
        <link rel="stylesheet" href="/css/base.css">
        <link rel="stylesheet" href="/css/components.css">
        <link rel="stylesheet" href="/css/header.css">
        <link rel="stylesheet" href="/css/footer.css">
        <link rel="stylesheet" href="/css/profile.css"> <!-- Reusing profile.css for similar layout -->
        <link rel="stylesheet" href="/css/public_profile.css"> <!-- For any public-specific tweaks -->
    </head>
    <body>
<?php }

// Function to draw the public profile details
function drawPublicProfileDetails(array $userData, array $userServices, PDO $db) { ?>
    <main class="profile-container public-profile-container"> <?php // Reusing .profile-container from profile.css ?>
        <div class="profile-header public-profile-header">
            <div class="profile-avatar">
                <img src="<?php echo htmlspecialchars($userData['profileImage'] ?? '/images/default_user.jpg'); ?>" alt="<?php echo htmlspecialchars($userData['username']); ?>'s Profile Picture">
            </div>
            <div class="profile-info">
                <h1><?php echo htmlspecialchars($userData['name']); ?></h1>
                <p class="username">@<?php echo htmlspecialchars($userData['username']); ?></p>
                <?php if (!empty($userData['bio'])): ?>
                    <p class="user-bio"><?php echo nl2br(htmlspecialchars($userData['bio'])); ?></p>
                <?php else: ?>
                    <p class="user-bio">No biography provided.</p>
                <?php endif; ?>
                <?php if (!empty($userData['country'])): ?>
                     <p class="user-country">Country: <?php echo htmlspecialchars($userData['country']); ?></p>
                <?php endif; ?>
                <?php // Add other public info as needed, e.g., member since, etc. ?>
            </div>
        </div>

        <div class="profile-content services-section public-services-section">
            <div class="section-header">
                <h2>Services Offered by <?php echo htmlspecialchars($userData['username']); ?></h2>
            </div>
            <?php if (empty($userServices)): ?>
                <p class="no-data">This user has not listed any services yet.</p>
            <?php else: ?>
                <div class="service-cards"> <?php // Reusing .service-cards from profile.css ?>
                    <?php foreach ($userServices as $service): ?>
                        <?php // This structure should match what .card and .service-card in profile.css expect ?>
                        <article class="card service-card">
                            <?php
                            // Simplified image fetching for public profile - assuming service array has a primary image or first image
                            // The getServicesbySellerIdForPublicProfile should ideally prepare this image path
                            $image_path = $service['image'] ?? '/images/default_service.jpg';
                            if (substr($image_path, 0, 3) !== '../' && $image_path[0] !== '/') {
                                $image_path = '/' . $image_path; // Ensure it's a root-relative path if not already
                            }
                            ?>
                            <div class="card-image-container">
                                <a href="/pages/service.php?id=<?php echo htmlspecialchars((string)$service['id']); ?>">
                                    <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($service['Title']); ?>">
                                </a>
                            </div>
                            <div class="card-content">
                                <h3 class="card-title">
                                    <a href="/pages/service.php?id=<?php echo htmlspecialchars((string)$service['id']); ?>"><?php echo htmlspecialchars($service['Title']); ?></a>
                                </h3>
                                <p class="card-text service-description">
                                    <?php
                                    $description = htmlspecialchars($service['description']);
                                    echo strlen($description) > 100 ? substr($description, 0, 97) . '...' : $description;
                                    ?>
                                </p>
                                <div class="service-meta">
                                    <p class="price">$<?php echo htmlspecialchars(number_format((float)$service['price'], 2)); ?></p>
                                    <p class="rating">
                                        <?php echo ($service['average_rating'] ?? 0) ? number_format((float)$service['average_rating'], 1) . ' ★' : 'Not rated'; ?>
                                        (<?php echo htmlspecialchars((string)($service['order_count'] ?? 0)); ?> orders)
                                    </p>
                                </div>
                            </div>
                            <div class="card-actions">
                                <a href="/pages/service.php?id=<?php echo htmlspecialchars((string)$service['id']); ?>" class="btn btn-primary">View Service</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
<?php }
?>
