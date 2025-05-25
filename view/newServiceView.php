<?php function drawNewServiceHeader() { ?>
    <!DOCTYPE html>
    <html lang='en-US'>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>Freelance</title>
        <link rel="icon" href="/images/logo2.png">
        <link rel="stylesheet" href="/css/common.css">
        <link rel="stylesheet" href="/css/newservice.css">
    </head>
    <body>
<?php } ?>
<?php function drawNewService($tags,$categories) { ?>
<body>
<main id="NewServices">
<div class="new-service-container">
        <h2>Create a New Service</h2>
        <?php if (!empty($errors)): ?>
            <div class="alert error">
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?php echo htmlspecialchars($err); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form id="serviceForm" method="post" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="20971520">
            <label for="title">Service Title</label>
            <input
                type="text" id="title" name="title"
                value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>"
                required>

            <!-- Description -->
            <label for="description">Description</label>
            <textarea
                id="description" name="description" rows="5"
                required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>

            <!-- Price -->
            <label for="price">Price (€)</label>
            <input
                type="number" id="price" name="price" step="0.01" min="0.00"
                value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>"
                required>

            <!-- Delivery Time -->
            <label for="deliverTime">Delivery Time (days)</label>
            <input
                type="number" id="deliverTime" name="deliverTime" min="1"
                value="<?php echo htmlspecialchars($_POST['deliverTime'] ?? ''); ?>"
                required>

            <!-- Category -->
            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="" disabled <?php echo empty($_POST['category']) ? 'selected' : ''; ?>>
                    Select a category
                </option>
                <?php foreach ($categories as $c): ?>
                    <option
                        value="<?php echo $c['id']; ?>"
                        <?php echo (isset($_POST['category']) && $_POST['category'] == $c['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['name']); ?></option>
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
                            <?php echo in_array($t['id'], $_POST['tags'] ?? [], true) ? 'checked' : ''; ?>>
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
                <button type="submit">Create Service</button>
            </div>
        </form>
</div>
</main>
<script src="/../js/createService.js"></script>
<?php } ?>