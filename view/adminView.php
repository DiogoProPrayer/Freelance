<?php function drawAdminHeader() { ?>
<!DOCTYPE html>
<html lang='en-US'>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Admin Panel - Freelance</title>
    <link rel="icon" href="/images/logo2.png">
    <link rel="stylesheet" href="/css/base.css">
    <link rel="stylesheet" href="/css/components.css">
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/footer.css">
    <link rel="stylesheet" href="/css/admin.css"> <!-- Page-specific CSS -->
    <!-- Font Awesome for icons, consider a local setup or CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<?php } ?>

<?php function drawAdminPage() { ?>
<main class="admin-container">
    <div class="admin-sidebar">
        <h2>Admin Panel</h2>
        <nav class="admin-nav">
            <a href="?tab=users" class="<?php echo $activeTab === 'users' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> Users
            </a>
            <a href="?tab=services" class="<?php echo $activeTab === 'services' ? 'active' : ''; ?>">
                <i class="fas fa-briefcase"></i> Services
            </a>
            <a href="?tab=reviews" class="<?php echo $activeTab === 'reviews' ? 'active' : ''; ?>">
                <i class="fas fa-star"></i> Reviews
            </a>
        </nav>
    </div>
<?php } ?>


<?php function drawAdminPanel($activeTab,$users,$services,$reviews){ ?>
<div class="admin-content">
        <?php switch ($activeTab):
            case 'users': ?>
                <div class="admin-header">
                    <h1>User Management</h1>
                    <p>Total Users: <?php echo count($users); ?></p>
                </div>

                <div class="admin-table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Profile</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Admin</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr data-user-id="<?php echo $user['id']; ?>">
                                    <td><?php echo $user['id']; ?></td>
                                    <td class="profile-cell">
                                        <img src="<?php echo $user['profileImage'] ?? 'images/default_user.jpg'; ?>" alt="Profile">
                                    </td>
                                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['userStatus']); ?></td>
                                    <td>
                                        <?php if ($user['isAdmin']): ?>
                                            <span class="admin-badge">Admin</span>
                                        <?php else: ?>
                                            <span class="user-badge">User</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions-cell">
                                        <?php if (!$user['isAdmin']): ?>
                                            <button class="btn btn-secondary promote-btn" data-user-id="<?php echo $user['id']; ?>">
                                                <i class="fas fa-user-shield"></i> Promote
                                            </button>
                                        <?php endif; ?>

                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <button class="btn btn-danger delete-btn" data-user-id="<?php echo $user['id']; ?>">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php break; ?>

            <?php
            case 'services': ?>
                <div class="admin-header">
                    <h1>Service Management</h1>
                    <p>Total Services: <?php echo count($services); ?></p>
                </div>

                <div class="services-list">
                    <?php foreach ($services as $service): ?>
                        <div class="service-list-item" data-service-id="<?php echo $service['id']; ?>">
                            <div class="service-list-image">
                                <img src="<?php echo htmlspecialchars($service['image']); ?>" alt="Service Image">
                            </div>
                            <div class="service-list-info">
                                <h3><?php echo htmlspecialchars($service['title']); ?></h3>
                                <p class="service-seller">By <?php echo htmlspecialchars($service['seller_name']); ?></p>
                                <p class="service-category">
                                    <?php echo htmlspecialchars($service['category_name'] ?? 'Uncategorized'); ?>
                                </p>
                                <p class="service-price"><?php echo number_format($service['price'], 2); ?>€</p>
                            </div>
                            <div class="service-list-actions">
                                <a href="service.php?id=<?php echo $service['id']; ?>" class="btn btn-outline view-btn">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <button class="btn btn-danger delete-service-btn" data-service-id="<?php echo $service['id']; ?>">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php break; ?>

            <?php
            case 'reviews': ?>
                <div class="admin-header">
                    <h1>Review Management</h1>
                    <p>Total Reviews: <?php echo count($reviews); ?></p>
                </div>

                <div class="reviews-container">
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-card" data-review-id="<?php echo $review['id']; ?>">
                            <div class="review-header">
                                <div class="review-service-info">
                                    <h3>
                                        <a href="service.php?id=<?php echo $review['service_id']; ?>">
                                            <?php echo htmlspecialchars($review['service_title']); ?>
                                        </a>
                                    </h3>
                                    <p class="review-buyer">
                                        Review by <?php echo htmlspecialchars($review['buyer_name']); ?>
                                        (@<?php echo htmlspecialchars($review['buyer_username']); ?>)
                                    </p>
                                </div>
                                <div class="review-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= $review['rating']): ?>
                                            <i class="fas fa-star"></i>
                                        <?php else: ?>
                                            <i class="far fa-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    <span class="rating-value"><?php echo $review['rating']; ?>/5</span>
                                </div>
                            </div>

                            <div class="review-content">
                                <?php if ($review['review']): ?>
                                    <p><?php echo nl2br(htmlspecialchars($review['review'])); ?></p>
                                <?php else: ?>
                                    <p class="no-review">No written review provided.</p>
                                <?php endif; ?>
                            </div>

                            <div class="review-actions">
                                <?php if ($review['review']): ?>
                                    <button class="btn btn-danger delete-review-btn" data-review-id="<?php echo $review['id']; ?>">
                                        <i class="fas fa-trash"></i> Delete Review Text
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php break; ?>
        <?php endswitch; ?>
    </div>
</main>
<?php } ?>

<?php function drawConfirmModel() { ?>
<div id="confirmModal" class="modal">
    <div class="modal-content">
        <h2>Confirm Action</h2>
        <p id="confirmMessage"></p>
        <div class="modal-actions">
            <button id="confirmButton" class="btn btn-primary confirm-btn">Confirm</button>
            <button id="cancelButton" class="btn btn-outline cancel-btn">Cancel</button>
        </div>
    </div>
</div>
<script src="../js/admin.js"></script>
<?php } ?>



