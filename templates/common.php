<?php 
declare(strict_types=1);
require_once(__DIR__ . '/../model/authenticationClass.php');
?>

<?php function drawTopBar($status) { ?>
    <header id="TopBar">
        <a href="/pages/homepage.php"> <img id="logo"  src="/images/logo2.png" width=80 height=80> </a>
        <form action="search.php" method="get" role="search">
            <input class="searchbar" type="search" name="search" placeholder="Search…">
        </form>
        <nav class="user-nav">
            <ul id="user-controls">
                <?php if (Authentication::getInstance()->getUser()) drawLogoutForm($status); else drawLoginForm(); ?>
            </ul>
        </nav>
    </header>
<?php } ?>

<?php function drawLoginForm() { ?>
    <li><a class="register" href="#" onclick="openAuthPopup('login')">Login</a></li>
    <li><a class="register" href="#" onclick="openAuthPopup('register')">Join Now</a></li>
<?php } ?>

<?php function drawLogoutForm($status) { ?>
    <li class="profile-dropdown">
        <div class="profile-icon">
            <a href="profile.php">
            <img src="<?php echo htmlspecialchars(Authentication::getInstance()->getProfileImage())?>" alt="Avatar">
            </a>
        </div>
        <ul class="dropdown-menu">
            <li><a href="/controller/logoutController.php" class="logout">Logout</a></li>
            <li><a href="/pages/messages.php">Messages</a></li>
        </ul>
    </li>
<?php } ?>


<?php function drawFooter() { ?>
    <footer>
        <ul class="footer-items">
            <li>About us</li>
            <li>Contact us at support@freelance.com</li>
            <li>Follow us on social media</li>
            <li>© 2023 Freelance Inc.</li>
        </ul>
    </footer>
    </body>
    </html>
<?php } ?>
