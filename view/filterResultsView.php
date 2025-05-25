<?php function drawTitle($categoryName){ ?>
        <main>
            <header>
                <h1>Results</h1>
                <img src="../icons/filter.png" alt="Filter icon" class="filter-icon" id="filterToggle">
            </header>
<?php } ?>

<?php function drawFilterOptions($categories) { ?>
  <link rel="stylesheet" href="../styles/filter.css">
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
            <button type="reset" class="btn-clear">Clear Filters</button>
            <button type="submit" class="btn-apply">Apply Filters</button>   
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
            <div class="service-cards">
                <?php foreach ($serviceList as $service): ?>
                    <div class="service-card">
                        <a href="../pages/service.php?id=<?php echo $service['id']; ?>" class="service-card-link" aria-label="View <?php echo htmlspecialchars($service['title']); ?>"></a>
                        <?php
                            $imgStmt = $database->prepare('SELECT image FROM ServiceImages WHERE service=:svc LIMIT 1');
                            $imgStmt->execute(['svc' => $service['id']]);
                            $img = $imgStmt->fetchColumn() ?: '../images/default_service.jpg';
                            if (!$img) {
                                $img = '../images/default_service.jpg';
                            } else {
                                if (substr($img, 0, 3) !== '../') {
                                    $img = '../' . $img;
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
                                    <a href="service.php?id=<?php echo $service['id']; ?>" class="btn view-btn">View</a>
                                </div>
                            </div>
                        </div>
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