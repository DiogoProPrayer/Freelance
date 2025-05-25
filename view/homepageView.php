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
    <link rel="stylesheet" href="/css/common.css">
    <link rel="stylesheet" href="/css/homePage.css">
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
            <article href="/pages/service.php?id=<?php echo $popularServices['id']; ?>">
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
                <img class="profile_pic2" src="<?php echo htmlspecialchars($service['profileImage'] ??'/images/art.jpg') ?>" alt="seller">
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
            </article>
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
        <form action="../controller/registerController.php" method="post">
          <input type="text" name="name" placeholder="Name" required>
          <input type="text" name="username" placeholder="Username" required>
          <input type="email" name="email" placeholder="Email" required>
          <input type="password" name="password" placeholder="Password" required>
          <input type="password" name="confirm_password" placeholder="Confirm Password" required>
          <select name="userStatus">
            <option value="client">Client</option>
            <option value="seller">Seller</option>
          </select>
          <button class="submit" type="submit">Register</button>
        </form>
        <p>Already have an account? </p>
        <br>
        <a href="#" onclick="showAuthForm('login')">Login here</a>
      </div>
    </div>
  <?php } ?>