<?php

declare(strict_types=1);
require_once(__DIR__ . '/../model/authenticationClass.php');
?>

<?php function drawEditServiceHeader()
{ ?>
    <!DOCTYPE html>
    <html lang='en-US'>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>Freelance</title>
        <link rel="icon" href="/images/logo2.png">
        <link rel="stylesheet" href="/css/base.css">
        <link rel="stylesheet" href="/css/components.css">
        <link rel="stylesheet" href="/css/header.css">
        <link rel="stylesheet" href="/css/footer.css">
        <link rel="stylesheet" href="/css/editService.css">
        <script src="/js/authPopup.js" defer></script>
    </head>

    <body>
    <?php } ?>

    <?php function drawEditService($serviceInfo, $imagesService, $categories, $tags, $tagsService)
    { ?>
        <main class="edit-service">
            <div class="All-Images">
                <?php if (!empty($imagesService)): ?>
                    <?php foreach ($imagesService as $image): ?>
                        <div class="contains_service_image" data-image-id="<?php echo htmlspecialchars(strval($image['id'])) ?>">
                            <?php $image['image'] = '../' . htmlspecialchars($image['image']); ?>
                            <img class="service_image" src="<?php echo htmlspecialchars($image['image']) ?>" alt="service">
                            <button class="deleteImage hidden">Delete</button>
                        </div>
                    <?php endforeach ?>
                <?php endif; ?>
            </div>
            <div class="edit-service-container">
                <form id="serviceForm" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="MAX_FILE_SIZE" value="20971520">
                    <label for="title">Service Title</label>
                    <input
                        type="text" id="title" name="title"
                        value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" placeholder="<?php echo htmlspecialchars($serviceInfo['title'] ?? ''); ?>">

                    <!-- Description -->
                    <label for="description">Description</label>
                    <textarea
                        id="description" name="description" rows="5" placeholder="<?php echo htmlspecialchars($serviceInfo['description'] ?? ''); ?>"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>

                    <!-- Price -->
                    <label for="price">Price (€)</label>
                    <input
                        type="number" id="price" name="price" step="0.01" min="0.00"
                        value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>" placeholder="<?php echo number_format($serviceInfo['price']); ?>">

                    <!-- Delivery Time -->
                    <label for="deliverTime">Delivery Time (days)</label>

                    <input
                        type="number" id="deliverTime" name="deliverTime" min="1"
                        value="<?php echo htmlspecialchars($_POST['deliverTime'] ?? ''); ?>"
                        placeholder="<?php echo number_format($serviceInfo['deliverTime']); ?>" >

                    <!-- Category -->
                    <label for="category">Category</label>
                    <?php $currentCategory = $_POST['category'] ?? $serviceInfo['category_id'] ?? ''; ?>
                    <select id="category" name="category" >
                        <option value="" disabled <?php echo empty($currentCategory) ? 'selected' : ''; ?>>
                            Select a category
                        </option>
                        <?php foreach ($categories as $c): ?>
                            <option
                                value="<?php echo $c['id']; ?>"
                                <?php echo ($currentCategory == $c['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($c['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <label>Tags (optional)</label>
                    <div class="tag-checkboxes">
                        <?php foreach ($tags as $t): ?>
                            <div>
                                <input
                                    type="checkbox"
                                    id="tag-<?php echo $t['id']; ?>"
                                    name="tags[]"
                                    value="<?php echo $t['id']; ?>"
                                    <?php echo in_array($t['id'], $tagsService) ? 'checked' : ''; ?>>
                                <label for="tag-<?php echo $t['id']; ?>">
                                    <?php echo htmlspecialchars($t['name']); ?>
                                    (<?php echo htmlspecialchars($t['category_name']); ?>)
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- Image Drop Area -->
                    <label>Upload Images</label>
                    <div class="drop-area" id="drop-area">
                        <p id="drop-text">Drag &amp; drop images here or click to upload</p>
                        <input
                            type="file" id="images" name="images[]"
                            multiple accept="image/*">
                        <div id="preview"></div>
                    </div>

                    <!-- Submit -->
                    <div class="form-actions">
                        <button type="submit" name="updateService">Update Service</button>
                        <button type="submit" name="deleteService">Delete Service</button>
                        <button type="submit" name="cancel">Cancel</button>
                    </div>
                </form>
            </div>
        </main>
        <script src="/js/serviceEditing.js"></script>
        <script src="/js/createService.js"></script>

    <?php } ?>