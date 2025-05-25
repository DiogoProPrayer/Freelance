<?php function drawTitle($categoryName){ ?>
        <main>
            <header>
                <h1>Results</h1>
                <img src="../icons/filter.png" alt="Filter icon" class="filter-icon" id="filterToggle">
            </header>
<?php } ?>

<?php function drawFilterOptions($categories) { ?>
  <div class="filter-container">
    <form method="get" action="filter.php" id="filterForm">
        <div class="filter-main">
            <div class="filter-categories">
            <p>Choose Categories:</p>
            <?php foreach ($categories as $category): ?>
                <label class="category-label">
                <input type="checkbox" 
                        id="<?= htmlspecialchars($category['name']) ?>" 
                        name="categories[]" 
                        value="<?= htmlspecialchars($category['name']) ?>">
                <?= htmlspecialchars($category['name']) ?>
                </label>
            <?php endforeach; ?>
            </div>

            <div class="filter-controls">
            <div class="filter-group">
                <label for="sort">Sort by:</label>
                <select id="sort" name="sort">
                <option value="none">None</option>
                <option value="price">Price</option>
                <option value="title">Title</option>
                <option value="rating">Rating</option>
                <option value="ord">Number Orders</option>
                </select>
                <span class="error-message"></span>
                
                <label for="order">Order by:</label>
                <select id="order" name="order">
                <option value="none">None</option>
                <option value="asc">Ascendent</option>
                <option value="desc">Descendent</option>
                </select>
                <span class="error-message"></span>
            </div>

                <div class="filter-group price-rating">
                    <label for="max">Max Price:</label>
                    <input type="number" id="max" name="max">
                    <span class="error-message"></span>

                    <label for="rat">Min Rating:</label>
                    <input type="number" id="rat" name="rat">
                    <span class="error-message"></span>

                    <label for="ord">Min Orders:</label>
                    <input type="number" id="ord" name="ord">
                    <span class="error-message"></span>
                </div>
            </div>

        </div>

        <div class="filter-buttons">
            <button type="reset" class="btn btn-outline">Clear Filters</button>
            <button type="submit" class="btn btn-primary">Apply Filters</button>   
        </div>
    </form>
  </div>
<?php } ?>


<?php 
function drawFilteredServices($status,$serviceList,$database) { ?>
<div class="profile-content">
    <section class="services-section">
        <?php if (empty($serviceList)): ?>
            <p class="no-data">This Category does't have any services</p>
        <?php else: ?>
            <div class="service-cards"> <?php // This class can be renamed to service-list-grid to match css/filter.css ?>
                <?php foreach ($serviceList as $service): ?>
                    <?php
                        $service_page_url = "../pages/service.php?id=" . htmlspecialchars((string)($service['id'] ?? ''));
                        $imgStmt = $database->prepare('SELECT image FROM ServiceImages WHERE service=:svc LIMIT 1');
                        $imgStmt->execute(['svc' => $service['id']]);
                        $img_path = $imgStmt->fetchColumn() ?: '../images/default_service.jpg';
                        if (!$img_path) {
                            $img_path = '../images/default_service.jpg';
                        } else {
                            if (substr($img_path, 0, 3) !== '../') {
                                $img_path = '../' . $img_path;
                            }
                        }
                    ?>
                    <article class="card service-card-item">
                        <div class="card-image-container">
                            <a href="<?php echo $service_page_url; ?>">
                                <img src="<?php echo htmlspecialchars($img_path); ?>" alt="<?php echo htmlspecialchars($service['title'] ?? 'Service Image'); ?>">
                            </a>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title">
                                <a href="<?php echo $service_page_url; ?>"><?php echo htmlspecialchars($service['title'] ?? 'Untitled Service'); ?></a>
                            </h3>
                            <p class="card-text service-description"><?php echo htmlspecialchars($service['description'] ?? ''); ?></p>
                            <div class="service-meta"> <?php // Custom meta section for filter page cards ?>
                                <p class="price">$<?php echo htmlspecialchars(number_format((float)($service['price'] ?? 0), 2)); ?></p>
                                <p class="rating">
                                    <?php echo ($service['average_rating'] ?? 0) ? number_format((float)$service['average_rating'], 1) . ' ★' : 'Not rated'; ?>
                                    (<?php echo htmlspecialchars((string)($service['order_count'] ?? 0)); ?> orders)
                                </p>
                            </div>
                        </div>
                        <div class="card-actions">
                            <a href="<?php echo $service_page_url; ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>  
            <?php endif; ?>
    </section>
</div>
<script src="../js/filter.js"></script>
</main>
<?php 
} 
?>