<?php 
declare(strict_types=1);
require_once(__DIR__ . '/../model/authenticationClass.php');
?>

<?php function drawTopBar($status,$isAdmin) { ?>
    <header id="TopBar">
        <a href="/pages/homepage.php"> <img id="logo"  src="/images/logo2.png" width=80 height=80> </a>
        <form method="get" role="search" class="search-wrapper" autocomplete="off">
            <input id="search-input" class="searchbar" type="search" name="search" placeholder="Search…">
            <div id="results-box"></div>
        </form>
        <nav class="user-nav">
            <ul id="user-controls">
                <?php if (Authentication::getInstance()->getUser()) drawLogoutForm($status,$isAdmin); else drawLoginForm(); ?>
            </ul>
        </nav>
    </header>
<?php } ?>

<?php function drawLoginForm() { ?>
    <li><a class="register" href="#" onclick="openAuthPopup('login')">Login</a></li>
    <li><a class="register" href="#" onclick="openAuthPopup('register')">Join Now</a></li>
<?php } ?>

<?php function drawLogoutForm($status, $isAdmin) { ?>
    <?php // Admin Panel link is now inside the dropdown ?>
    <li><a href="/pages/messages.php">Messages</a></li> <?php // Messages link remains in the top bar ?>
    <li class="profile-dropdown">
        <div class="profile-icon">
            <a href="#" onclick="return false;"> <?php // Made profile icon non-navigable, dropdown is the interaction point ?>
            <img src="<?php echo htmlspecialchars(Authentication::getInstance()->getProfileImage() ?? '/images/art.jpg')?>" alt="Avatar">
            </a>
        </div>
        <ul class="dropdown-menu">
            <?php if ($isAdmin == 1): ?>
                <li><a href="/pages/admin.php">Admin Panel</a></li> <?php // Moved here, points to pages/admin.php ?>
            <?php endif; ?>
            <li><a href="/pages/profile.php">Profile</a></li> <?php // Added Profile link ?>
            <li><a href="/pages/messages.php">Messages</a></li> <?php // Duplicated Messages link inside dropdown for convenience ?>
            <li><hr class="dropdown-divider"></li> <?php // Optional divider ?>
            <li><a href="/controller/logoutController.php" class="logout">Logout</a></li>
        </ul>
    </li>
<?php } ?>


<?php function drawFooter() { ?>
    <footer>
        <div class="footer-content">
            <ul class="footer-items">
                <li><a href="#">About us</a></li>
                <li><a href="mailto:support@freelance.com">Contact us</a></li> 
                <li><a href="#">Follow us</a></li> 
            </ul>
            <p class="copyright">© <?php echo date("Y"); ?> Freelance Inc.</p>
        </div>
    </footer>
    <script src="/js/search.js"></script>
    </body>
    </html>
<?php } ?>
