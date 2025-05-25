<?php

declare(strict_types=1);
require_once(__DIR__ . '/../model/authenticationClass.php');
?>

<?php function drawHomepageHeader()
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
    <link rel="stylesheet" href="/css/homepage.css">
    <script src="/js/authPopup.js" defer></script>
  </head>

  <body>
  <?php } ?>

  <?php function drawNavCategories($categories, $logged)
  { ?>
    <nav id="Popular_Categories">
      <h2>Popular Categories</h2>
      <ul id="category">
        <?php foreach ($categories as $category): ?>
          <li class="categories">
            <img src="../icons/techicon.png" alt="icon_tech" width="35">
            <a href="#" class="link-categories" onclick="handleClickedCategory('<?php echo htmlspecialchars($category['name']); ?>', <?php echo $logged ? 'true' : 'false'; ?>)">
              <?php echo htmlspecialchars($category['name']); ?>
            </a>
          </li>
        <?php endforeach ?>
      </ul>
    </nav>
  <?php } ?>


  <?php function drawSellers($topsellers)
  { ?>
    <main>
      <section id="sellers">
        <header>
          <h2>Top Sellers</h2>
        </header>
        <div class=scrollable>
          <?php foreach ($topsellers as $seller): ?>
            <a href="/pages/profile.php?user_id=<?php echo htmlspecialchars($seller['id']); ?>" class="seller-link-wrapper">
              <article class="seller">
                <img class="profile_pic" src="<?php echo htmlspecialchars($seller['profileImage']); ?>" alt="user">
                <div class="information">
                  <div class="sellerName">
                    <p>Username: <?php echo htmlspecialchars($seller['username']); ?></p>
                  </div>
                  <div class="sellerRating">
                    <p>Rating: <?php echo number_format($seller['rating'], 1); ?></p>
                  </div>
                </div>
              </article>
            </a>
          <?php endforeach ?>
        </div>
      </section>

    <?php } ?>

    <?php function drawServices($popularServices)
    { ?>
      <section id="services">
        <header>
          <h2>Trending Services</h2>
        </header>
        <div class="displayServices">
          <?php foreach ($popularServices as $service) : ?>
            <a href="/pages/service.php?id=<?php echo htmlspecialchars($service['id']); ?>" class="service-card-link">
              <ul class="ServiceImages">
                <?php if (!empty($service['images'])) : ?>
                  <?php foreach ($service['images'] as $image): ?>
                    <img class="service_images" src="<?php echo htmlspecialchars($image) ?>" alt="service">
                  <?php endforeach ?>
                <?php endif; ?>
                <?php if (empty($service['images'])): ?>
                  <img class="service_images" src="/images/art.jpg" alt="default service image">
                <?php endif; ?>
              </ul>
              <div id="information">
                <a href="/pages/profile.php?user_id=<?php echo htmlspecialchars($service['seller_id']); ?>" class="service-seller-profile-link">
                  <img class="profile_pic2" src="<?php echo htmlspecialchars($service['profileImage'] ??'/images/art.jpg') ?>" alt="seller">
                </a>
                <div class="service-details">
                  <div class="serviceTitle">
                    <p><?php echo htmlspecialchars($service['Title'] ?? 'Web development') ?></p>
                  </div>
                  <div class="sellerName">
                    <p>Username: <?php echo htmlspecialchars($service['username'] ?? 'Buttercup'); ?></p>
                  </div>
                  <div class="sellerRating">
                    <p>Rating: <?php echo number_format($service['rating'], 1); ?></p>
                  </div>
                  <div class="price">
                    <p>Price: <?php echo number_format($service['price']) ?></p>
                  </div>
                </div>
              </div>
            </a>
          <?php endforeach ?>

        </div>
      </section>
    </main>
  <?php } ?>

  <?php function drawPopup()
  { ?>
    <div id="Popup">
      <button id="close">X</button>
      <div class="image-container">
        <img id="genericImage" src="../images/freelances.jpg" alt="freelance" width=1000 height=600>
      </div>

      <div id="loginForm" class="form-wrapper">
        <h3>Login</h3>
        <form action="../controller/loginController.php" method="post">
          <input type="text" name="username" placeholder="Username" required>
          <span class="error-message" id="username-error"></span>
          
          <input type="password" name="password" placeholder="Password" required>
          <span class="error-message" id="password-error"></span>
          <button class="submit" type="submit">Login</button>
        </form>
        <p>Don't have an account? </p>
        <br>
        <a href="#" onclick="showAuthForm('register')">Register here</a>
      </div>

      <div id="registerForm" class="form-wrapper">
        <h3>Register</h3>
        <?php 
            $auth_error = $_SESSION['auth_error'] ?? null;
            $form_values = $_SESSION['form_values'] ?? [];
            $show_auth_form = $_SESSION['show_auth'] ?? null;

            if ($auth_error && $show_auth_form === 'register') {
                echo '<div class="alert error" style="display:block; margin-bottom: var(--spacing-md);"><p>' . htmlspecialchars($auth_error) . '</p></div>';
            }
            // Clear them after displaying so they don't persist
            unset($_SESSION['auth_error']);
            unset($_SESSION['form_values']); // Or unset specific fields as they are used
            // $_SESSION['show_auth'] should be cleared by JS after opening the popup
        ?>
        <form action="../controller/registerController.php" method="post">
          <input type="text" name="name" placeholder="Name" value="<?php echo htmlspecialchars($form_values['name'] ?? ''); ?>" required>
          <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($form_values['username'] ?? ''); ?>" required>
          <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($form_values['email'] ?? ''); ?>" required>
          <input type="password" name="password" placeholder="Password" required>
          <input type="password" name="confirm_password" placeholder="Confirm Password" required>
          <select name="userStatus">
            <option value="client" <?php echo (isset($form_values['userStatus']) && $form_values['userStatus'] === 'client') ? 'selected' : ''; ?>>Client</option>
            <option value="seller" <?php echo (isset($form_values['userStatus']) && $form_values['userStatus'] === 'seller') ? 'selected' : ''; ?>>Seller</option>
          </select>
          <button class="submit" type="submit">Register</button>
        </form>
        <p>Already have an account? </p>
        <br>
        <a href="#" onclick="showAuthForm('login')">Login here</a>
      </div>
    </div>
  <?php } ?>

  <?php
    // Script to automatically open popup if show_auth is set (e.g., after failed registration)
    if (isset($_SESSION['show_auth']) && $_SESSION['show_auth']) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    openAuthPopup('" . htmlspecialchars($_SESSION['show_auth']) . "');
                    " . (isset($_SESSION['auth_error']) ? "/* Error already displayed by PHP */" : "") . "
                    delete " . json_encode($_SESSION['show_auth']) ."; /* Clear after use, but PHP session_unset is better */
                });
              </script>";
        // It's better to unset PHP session variables after they are used and outputted to JS.
        // However, since this is a simple echo, direct unsetting here is tricky.
        // The controller should handle unsetting $_SESSION['show_auth'] after a successful action or display.
        // For now, JS can try to clear it, or PHP on next full load if not used by JS.
        // Ideally, clear in controller or just before rendering view if possible.
        // For this specific case, we can unset it here as it has served its purpose for this page load.
        unset($_SESSION['show_auth']); 
    }
  ?>