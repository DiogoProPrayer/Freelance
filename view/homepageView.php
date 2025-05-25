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
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/footer.css">
    <link rel="stylesheet" href="/css/components.css">
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
            <article class="seller card">
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
            <article class="card service-card-item" onclick="window.location.href='/pages/service.php?id=<?php echo htmlspecialchars((string)$service['id']); ?>'" style="cursor:pointer;">
              <ul class="ServiceImages">
                <?php if (!empty($service['images'])) : ?>
                  <?php foreach ($service['images'] as $image): ?>
                    <img class="service_images" src="<?php echo htmlspecialchars($image) ?>" alt="service">
                  <?php endforeach ?>
                <?php endif; ?>
              </ul>
              <div id="information">
                <img class="profile_pic2" src="<?php echo htmlspecialchars($service['profileImage']) ?>" alt="seller">
                <div class="service-details">
                  <div class="serviceTitle">
                    <p><?php echo htmlspecialchars($service['Title']) ?></p>
                  </div>
                  <div class="sellerName">
                    <p>Username: <?php echo htmlspecialchars($service['username']); ?></p>
                  </div>
                  <div class="sellerRating">
                    <p>Rating: <?php echo number_format($service['rating'], 1); ?></p>
                  </div>
                  <div class="price card-text service-price"> <!-- Added classes for styling -->
                    <p>Price: $<?php echo number_format((float)$service['price'], 2); ?></p> <!-- Assuming price is a number -->
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
    <div id="authPopup" style="display: none;"> <!-- Changed ID and added style display:none -->
      <div class="popup-content">
        <button id="closePopup" class="close-btn">X</button> <!-- Changed id to closePopup -->

        <section id="loginSection" class="active"> <!-- Changed id and class for consistency -->
          <h3>Login</h3>
          <form id="loginFormActual" action="../controller/loginController.php" method="post"> <!-- Changed id -->
            <div class="form-group">
              <label for="login-username">Username</label>
              <input type="text" id="login-username" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
              <label for="login-password">Password</label>
              <input type="password" id="login-password" name="password" placeholder="Password" required>
            </div>
            <div class="form-actions">
              <button class="btn btn-primary" type="submit">Login</button> <!-- Removed submit class -->
            </div>
          </form>
          <p>Don't have an account? <a href="#" id="showRegister">Register here</a></p> <!-- Changed click handler -->
        </section>

        <section id="registerSection"> <!-- Changed id -->
          <h3>Register</h3>
          <form id="registerFormActual" action="../controller/registerController.php" method="post"> <!-- Changed id -->
            <div class="form-group">
              <label for="register-name">Name</label>
              <input type="text" id="register-name" name="name" placeholder="Name" required>
            </div>
            <div class="form-group">
              <label for="register-username">Username</label>
              <input type="text" id="register-username" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
              <label for="register-email">Email</label>
              <input type="email" id="register-email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
              <label for="register-password">Password</label>
              <input type="password" id="register-password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
              <label for="register-confirm-password">Confirm Password</label>
              <input type="password" id="register-confirm-password" name="confirm_password" placeholder="Confirm Password" required>
            </div>
            <div class="form-group">
              <label for="register-userStatus">Register as</label>
              <select name="userStatus" id="register-userStatus">
                <option value="client">Client</option>
                <option value="seller">Seller</option>
              </select>
            </div>
            <div class="form-actions">
              <button class="btn btn-primary" type="submit">Register</button> <!-- Removed submit class -->
            </div>
          </form>
          <p>Already have an account? <a href="#" id="showLogin">Login here</a></p> <!-- Changed click handler -->
        </section>
      </div> <!-- End of .popup-content -->
    </div>
  <?php } ?>