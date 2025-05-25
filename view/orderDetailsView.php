<?php function drawViewService($serviceInfo,$imagesService){ ?>
    <?php 
    foreach ($imagesService as $index => $image) {
        if (substr($image, 0, 3) !== '../') {
            $imagesService[$index] = '../' . $image;
        }
    }
    ?>

    <main class="service-page-container">
        <div class="service-breadcrumb">
            <a href="index.php">Home</a> &gt;
            <?php if (!empty($serviceInfo['category_name'])): ?>
                <a href="../pages/filter.php?category=<?php echo $serviceInfo['category']; ?>"><?php echo htmlspecialchars($serviceInfo['category_name']); ?></a> &gt;
            <?php endif; ?>
            <span class="breadcrumb-current"><?php echo htmlspecialchars($serviceInfo['title']); ?></span>
        </div>

        <div class="service-content">
            <div class="service-gallery">
                <div class="main-image">
                    <img id="current-image" src="<?php echo htmlspecialchars($imagesService[0]); ?>" alt="<?php echo htmlspecialchars($serviceInfo['title']); ?>">
                </div>

                <?php if (count($imagesService) > 1): ?>
                    <div class="thumbnail-container">
                        <?php foreach ($imagesService as $index => $image): ?>
                            <div class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                                <img src="<?php echo htmlspecialchars($image); ?>" alt="Thumbnail <?php echo $index + 1; ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
<?php } ?>



<?php function drawOrderDetails($orderInfo,$tagsService,$user_id,$serviceInfo){ ?>
<?php 
    if (!empty($serviceInfo['seller_image']) && substr($serviceInfo['seller_image'], 0, 3) !== '../') {
        $serviceInfo['seller_image'] = '../' . $serviceInfo['seller_image'];
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
                        <span class="delivery-time"><?php echo $orderInfo['deliverTime']; ?> days delivery</span>
                    <?php endif; ?> 
                </div>
            </div>

            <div class="seller-info">
                <a href="../pages/profile.php?id=<?php echo $serviceInfo['seller_id']; ?>" class="seller-link">
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
                <form action="../controller/order_deleteController.php" method="post" class="order-form">
                    <input type="hidden" name="order_id" value="<?php echo $orderInfo['id']; ?>">
                    <button type="submit" class="btn order-btn"  name="action" value="cancel" onclick="confirmCancel()">Cancel Order</button>
                </form>
            <a href="../pages/messages.php?contact_id=<?php echo $serviceInfo['seller_id']; ?>" class="btn message-btn">
            Message <?php echo ($user_id == 'seller') ? 'seller' : 'buyer'; ?>
            </a>
            </div>
        </div>
    </div>
</main> 
<script src="../js/orderCount.js"></script>
<script>setupDeliveryCounter(<?= $orderInfo['id'] ?>);</script> 
<?php }?>