<?php function drawProfileHeader() { ?>
    <!DOCTYPE html>
    <html lang='en-US'>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>Freelance</title>
        <link rel="icon" href="/images/logo2.png">
        <link rel="stylesheet" href="/css/common.css">
        <link rel="stylesheet" href="/css/profile.css">
        <script src="/js/profile.js" defer></script>
    </head>
    <body>
<?php } ?>

<?php function drawProfileHead($status,$user) { ?>
<main class="profile-container">
    <?php if (isset($_GET['updated']) && $_GET['updated'] === 'true'): ?>
        <div class="alert success">Profile updated successfully!</div>
    <?php endif; ?>

    <?php if (isset($_GET['status_updated']) && $_GET['status_updated'] === 'true'): ?>
        <div class="alert success">Account mode updated successfully!</div>
    <?php endif; ?>

    <div class="profile-header">
        <div class="profile-avatar">
            <img src="<?php echo htmlspecialchars($user->getProfileImage() ?? '/images/default_user.jpg'); ?>" alt="Profile Picture" id="currentProfilePicDisplay">
        </div>
        <div class="profile-info">
            <h1><?php echo htmlspecialchars($user->getName()); ?></h1>
            <p class="username">@<?php echo htmlspecialchars($user->getUsername()); ?></p>
            <div class="user-status-container"> 
                <form action="/controller/profileController.php" method="post" id="statusToggleForm">
                    <div class="status-toggle">
                        <span class="status-label <?php echo $status === 'client' ? 'active' : ''; ?>">Client</span>
                        <label class="switch">
                            <input type="hidden" name="toggle_status" id="toggle_status_value" value="<?php echo $status; ?>">
                            <input type="checkbox" id="statusToggleCheckbox" <?php echo $status === 'seller' ? 'checked' : ''; ?>>
                            <span class="slider round"></span>
                        </label>
                        <span class="status-label <?php echo $status === 'seller' ? 'active' : ''; ?>">Seller</span>
                    </div>
                    <button type="submit" name="toggle_status_submit" id="toggleSubmitBtn" style="display:none;">Update Status</button>
                </form>
            </div>

            <p class="user-country"><?php echo htmlspecialchars($user->getCountry() ?: 'No country specified'); ?></p>
            <button id="editProfileBtn" class="edit-btn main-edit-btn">Edit Profile</button>
        </div>
    </div>
<?php } ?>



<?php function drawEditform($user) { ?>
    <section id="editProfileForm" class="edit-profile-form hidden">
        <h2>Edit Profile</h2>
        <form action="/controller/profileController.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user->getName()); ?>">
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user->getUsername()); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user->getEmail()); ?>">
            </div>
            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($user->getCountry() ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="phoneNumber">Phone Number</label>
                <input type="text" id="phoneNumber" name="phoneNumber" value="<?php echo htmlspecialchars($user->getPhoneNumber() ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="profile_image_input">Profile Image</label>
                <div id="imageUploadBox" class="image-upload-box">
                    <img id="imagePreview" src="" alt="Image Preview" class="image-preview hidden">
                    <div id="uploadPlaceholder" class="upload-placeholder">
                        <p>Drag & drop your image here, or click to select</p>
                        <span>(Recommended: Square)</span>
                    </div>
                    <input type="file" id="profile_image_input" name="profile_image" accept="image/*" class="hidden">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" name="update_profile" class="save-btn">Save Changes</button>
                <button type="button" id="cancelEditBtn" class="cancel-btn">Cancel</button>
            </div>
        </form>
    </section>
<?php } ?>

<?php 
function drawServiceCard($status,$services,$db) { ?>
<div class="profile-content">
    <?php if ($status === 'seller'): ?>
        <section class="services-section">
            <div class="section-header">
                <h2>My Services</h2>
                <a href="/pages/creationService.php" class="btn">Add New Service</a>
            </div>
            <?php if (empty($services)): ?>
                <p class="no-data">You haven't added any services yet.</p>
            <?php else: ?>
                <div class="service-cards">
                    <?php foreach ($services as $service): ?>
                        <div class="service-card">
                            <a href="/pages/service.php?id=<?php echo $service['id']; ?>" class="service-card-link" aria-label="View <?php echo htmlspecialchars($service['title']); ?>"></a>
                            <?php
                            $imgStmt = $db->prepare('SELECT image FROM ServiceImages WHERE service=:svc LIMIT 1');
                            $imgStmt->execute(['svc' => $service['id']]);
                            $img = $imgStmt->fetchColumn() ?: '/images/default_service.jpg';
                            if (!$img) {
                                $img = '/images/default_service.jpg';
                            } else {
                                if (substr($img, 0, 3) !== '../') {
                                    $img = '/' . $img;
                                }
                            }     
                            ?>

                            <div class="service-image">
                                <img src="<?php echo htmlspecialchars($img); ?>" alt="Service Image">
                            </div>
                            
                            <div class="service-details">
                                <h3><?php echo htmlspecialchars($service['title']); ?></h3>
                                <p class="service-description"><?php echo htmlspecialchars($service['description']); ?></p>
                                
                                <div class="service-meta">
                                    <p class="price">$<?php echo htmlspecialchars(number_format($service['price'], 2)); ?></p>
                                    <p class="rating">
                                        <?php echo $service['average_rating'] ? number_format($service['average_rating'], 1) . ' ★' : 'Not rated'; ?>
                                        (<?php echo $service['order_count']; ?> orders)
                                    </p>
                                </div>
                                
                                <div class="service-actions">
                                    <a href="editService.php?id=<?php echo $service['id'];?>" class="btn edit-service-btn">Edit</a>
                                    <a href="service.php?id=<?php echo $service['id']; ?>" class="btn view-btn">View</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    <?php endif; ?>
</div>
<?php 
} 
?>

<?php
function drawOrderStatus($status,$sellerOrders,$orders) { ?>
    <div class="orders-content">
        <?php if ($status === 'seller'): ?>
            <section class="orders-section">
                <h2>Unfinished Orders</h2>
                <?php if (empty($sellerOrders)): ?>
                    <p class="no-data">You don't have any orders for your services yet.</p>
                <?php else: ?>
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Service</th>
                                <th>Buyer</th>
                                <th>Status</th>
                                <th>Rating</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sellerOrders as $order): ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                                    <td><?php echo htmlspecialchars($order['service_title']); ?></td>
                                    <td><?php echo htmlspecialchars($order['buyer_name']); ?></td>
                                    <td class="status-<?php echo strtolower(htmlspecialchars($order['orderStatus'])); ?>">
                                        <?php echo htmlspecialchars($order['orderStatus']); ?>
                                    </td>
                                    <td><?php echo $order['rating'] ? htmlspecialchars($order['rating']) . ' ★' : 'Not rated'; ?></td>
                                    <td>
                                        <a href="../pages/orderDetails.php?id=<?php echo htmlspecialchars($order['id']); ?>" class="btn view-btn">View</a>
                                        <?php if ($order['orderStatus'] === 'IN_PROGRESS'): ?>
                                            <form action="../controller/order_deleteController.php" method="post" class="order-form">
                                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                <button type="submit" class="btn view-btn" name="action" value="deliver" style="background-color: green;">Deliver</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <?php if ($status === 'client'): ?>
            <section class="orders-section">
                <h2>My Orders</h2>
                <?php if (empty($orders)): ?>
                    <p class="no-data">You haven't placed any orders yet.</p>
                <?php else: ?>
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Service</th>
                                <th>Seller</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Delivery Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                                    <td><?php echo htmlspecialchars($order['service_title']); ?></td>
                                    <td><?php echo htmlspecialchars($order['seller_name']); ?></td>
                                    <td>$<?php echo htmlspecialchars(number_format($order['price'], 2)); ?></td>
                                    <td class="status-<?php echo strtolower(htmlspecialchars($order['orderStatus'])); ?>">
                                        <?php echo htmlspecialchars($order['orderStatus']); ?>
                                    </td>
                                    <td>
                                        <?php if ($order['orderStatus'] === 'DELIVERED'): ?>
                                            <?php echo htmlspecialchars(0) ?>
                                        <?php endif; ?>
                                        <?php if ($order['orderStatus'] !== 'DELIVERED'): ?>
                                            <?php echo htmlspecialchars($s = $order['remaining_days'] <= 0 ? 0 :  $order['remaining_days']); ?></td>
                                        <?php endif; ?>    
                                    <td>
                                        <a href="../pages/orderDetails.php?id=<?php echo htmlspecialchars($order['id']); ?>" class="btn view-btn">View</a>
                                        <?php if ($order['orderStatus'] === 'DELIVERED' && !$order['rating']): ?>
                                            <a href="rate-order.php?id=<?php echo htmlspecialchars($order['id']); ?>" class="btn rate-btn">Rate</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </div>
    </main>
    <script src="/js/profile.js"></script>
</body>
<?php 
}
?>
