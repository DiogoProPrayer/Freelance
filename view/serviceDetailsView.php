<?php function drawServiceHeader()
{ ?>
<!DOCTYPE html>
<html lang='en-US'>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Freelance</title>
    <link rel="icon" href="/images/logo2.png">
    <link rel="stylesheet" href="/css/base.css">
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/footer.css">
    <link rel="stylesheet" href="/css/components.css">
    <link rel="stylesheet" href="/css/service.css">
</head>

<body>
<?php } ?>

<?php function drawViewService($serviceInfo, $imagesService)
{ ?>
    <?php
    // Corrigir os caminhos das imagens antes do uso
    foreach ($imagesService as $index => $image) {
        if (substr($image['image'], 0, 3) !== '../') {
            $imagesService[$index] = '/../' . $image['image'];
        }
    }
    ?>
    <main class="service-page-container">
        <div class="service-breadcrumb">
            <a href="/pages/homepage.php">Home</a> &gt;
            <?php if (!empty($serviceInfo['category_name'])): ?>
                <a href="search.php?category=<?php echo $serviceInfo['category']; ?>"><?php echo htmlspecialchars($serviceInfo['category_name']); ?></a> &gt;
            <?php endif; ?>
            <span class="breadcrumb-current"><?php echo htmlspecialchars($serviceInfo['title']); ?></span>
        </div>

        <div class="service-content">
            <div class="service-gallery">
                <div class="carousel">
                    <button class="prev"  ><</button>
                <?php if (!empty($imagesService)): ?>
                    <?php foreach ($imagesService as $index => $image): ?>
                        <div class="image-container<?php echo $index === 0 ? ' active' : ''; ?>">
                            <img id="current-image" src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($serviceInfo['title']); ?>">
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <button class="next">></button>
                </div>
            </div>
        <?php } ?>
        



        <?php function drawServiceDetails($serviceInfo, $tagsService, $isOwner, $userId, $relatedServices)
        { ?>
            <?php
            // Ajustar prefixo '../' na imagem do vendedor
            if (!empty($serviceInfo['seller_image']) && substr($serviceInfo['seller_image'], 0, 3) !== '../') {
                $serviceInfo['seller_image'] = '/' . $serviceInfo['seller_image'];
            }

            // Ajustar prefixo '../' nas imagens dos related services
            foreach ($relatedServices as $index => $related) {
                if (!empty($related['image']) && substr($related['image'], 0, 3) !== '../') {
                    $relatedServices[$index]['image'] = '../' . $related['image'];
                }
            }
            ?>
            <div class="service-details">
                <h1 class="service-title"><?php echo htmlspecialchars($serviceInfo['title']); ?></h1>

                <div class="service-meta">
                    <div class="rating-info">
                        <span class="rating">
                            <?php
                            $rating = round($serviceInfo['average_rating'] * 2) / 2; // Round to nearest 0.5
                            $fullStars = floor($rating);
                            $halfStar = $rating - $fullStars >= 0.5;

                            // Display full stars
                            for ($i = 0; $i < $fullStars; $i++) {
                                echo '<i class="fas fa-star"></i>';
                            }

                            // Display half star if needed
                            if ($halfStar) {
                                echo '<i class="fas fa-star-half-alt"></i>';
                            }

                            // Display empty stars
                            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                            for ($i = 0; $i < $emptyStars; $i++) {
                                echo '<i class="far fa-star"></i>';
                            }

                            echo ' ' . ($serviceInfo['average_rating'] ? number_format($serviceInfo['average_rating'], 1) : 'Not rated');
                            ?>
                        </span>
                        <span class="orders">(<?php echo $serviceInfo['total_orders']; ?> orders)</span>
                    </div>

                    <div class="price-info">
                        <span class="price">$<?php echo number_format($serviceInfo['price'], 2); ?></span>
                        <?php if ($serviceInfo['deliverTime']): ?>
                            <span class="delivery-time"><?php echo $serviceInfo['deliverTime']; ?> days delivery</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="seller-info">
                    <a href="seller-profile.php?id=<?php echo $serviceInfo['seller_id']; ?>" class="seller-link">
                        <img src="<?php echo htmlspecialchars($serviceInfo['seller_image'] ?? '../images/default_user.jpg'); ?>" alt="<?php echo htmlspecialchars($serviceInfo['seller_name']); ?>">
                        <div class="seller-text">
                            <span class="seller-name"><?php echo htmlspecialchars($serviceInfo['seller_name']); ?></span>
                            <span class="seller-username">@<?php echo htmlspecialchars($serviceInfo['seller_username']); ?></span>
                        </div>
                    </a>
                </div>

                <div class="service-description">
                    <h2>Description</h2>
                    <div class="description-text">
                        <?php echo nl2br(htmlspecialchars($serviceInfo['description'])); ?>
                    </div>
                </div>

                <?php if (!empty($tagsService)): ?>
                    <div class="service-tags">
                        <?php foreach ($tagsService as $tag): ?>
                            <span class="tag"><?php echo htmlspecialchars($tag); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="service-actions">
                    <?php if ($isOwner): ?>
                        <a href="edit-service.php?id=<?php echo $serviceInfo['id']; ?>" class="btn btn-secondary edit-btn">Edit Service</a>
                    <?php else: ?>
                        <?php if ($userId): ?>
                            <form action="place-order.php" method="post" class="order-form">
                                <input type="hidden" name="service_id" value="<?php echo $serviceInfo['id']; ?>">
                                <button type="submit" class="btn btn-primary order-btn">Order Now</button>
                            </form>
                            <a href="/pages/messages.php?contact_id=<?php echo $serviceInfo['seller_id']; ?>" class="btn btn-outline message-btn">Message Seller</a>
                        <?php else: ?>
                            <a href="#" onclick="openAuthPopup('login')" class="btn btn-primary login-to-order-btn">Login to Order</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if (!empty($relatedServices)): ?>
            <div class="related-services">
                <h2>Similar Services</h2>
                <div class="related-service-cards">
                    <?php foreach ($relatedServices as $relatedService): ?>
                        <a href="service.php?id=<?php echo $relatedService['id']; ?>" class="card related-service-card">
                            <div class="related-service-image">
                                <img src="<?php echo htmlspecialchars($relatedService['image'] ?? '../images/default_service.jpg'); ?>" alt="Related Service">
                            </div>
                            <div class="related-service-details">
                                <h3 class="related-title"><?php echo htmlspecialchars($relatedService['title']); ?></h3>
                                <span class="related-price">$<?php echo number_format($relatedService['price'], 2); ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </main>
    <script src="/js/serviceDetails.js"></script>
<?php } ?>