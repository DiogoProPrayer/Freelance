<?php
declare(strict_types=1);

function drawFilterPageHeader($categoryName) { ?>
<!DOCTYPE html>
<html lang='en-US'>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Filter Results - <?php echo htmlspecialchars($categoryName); ?></title>
    <link rel="icon" href="/images/logo2.png">
    <link rel="stylesheet" href="/css/base.css">
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/footer.css">
    <link rel="stylesheet" href="/css/components.css">
    <link rel="stylesheet" href="/css/filter.css">
</head>
<body class="filter-page-active">
<?php }

function drawFilterPageHeading($categoryName) { ?>
<header> <!-- class="filter-page-title-header" was removed based on filter.css structure -->
    <h1><?php echo htmlspecialchars($categoryName); ?></h1>
</header>
<?php }

function drawServiceList($serviceList, $categoryName) { // Added $categoryName for context in no-results
    if (empty($serviceList)) { ?>
        <div class="no-results-message">
            <h2>No services found in "<?php echo htmlspecialchars($categoryName); ?>".</h2>
            <p>Try exploring other categories or refining your search.</p>
        </div>
    <?php } else { ?>
        <div class="service-list-grid">
            <?php foreach ($serviceList as $service): ?>
                <article class="card service-card-item">
                    <div class="card-image-container">
                        <a href="/pages/service.php?id=<?php echo htmlspecialchars((string)($service['id'] ?? '')); ?>">
                            <img src="<?php echo htmlspecialchars($service['image_path'] ?? '/images/default_service.jpg'); ?>" alt="<?php echo htmlspecialchars($service['title'] ?? 'Service Image'); ?>">
                        </a>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">
                            <a href="/pages/service.php?id=<?php echo htmlspecialchars((string)($service['id'] ?? '')); ?>"><?php echo htmlspecialchars($service['title'] ?? 'Untitled Service'); ?></a>
                        </h3>
                        <p class="card-text seller-name">By: <?php echo htmlspecialchars($service['seller_username'] ?? 'N/A'); ?></p>
                        <p class="card-text service-price">$<?php echo number_format((float)($service['price'] ?? 0), 2); ?></p>
                    </div>
                    <div class="card-actions">
                        <a href="/pages/service.php?id=<?php echo htmlspecialchars((string)($service['id'] ?? '')); ?>" class="btn btn-primary">View Details</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php }
} 
?>
