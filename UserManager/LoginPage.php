<?php
/**
 * Login page.
 *
 * PHP version used: 5.5.4
 * SQLite version used: 3.28.0
 *
 * Styling guide: PSR-12: Extended Coding Style
 *     (https://www.php-fig.org/psr/psr-12/)
 *
 * @category  PHPUserManager
 * @package   usermanager
 * @author    Rob Garcia <rgarcia@rgprogramming.com>
 * @copyright 2019-2020 Rob Garcia
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @link      https://github.com/garciart/PHPUserManager
 */
session_start();
require_once "CommonCode.php";
/* Start placing content into an output buffer */
ob_start();
?>
<!-- Head Content Start -->
<title>Login Page | PHP User Manager</title>
<!-- Head Content End -->
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolderHead = ob_get_contents();
/* Clean out the buffer, but do not destroy the output buffer */
ob_clean();
?>
<!-- Body Content Start -->
<!-- Header Element Content -->
<h1 class="mt-3">PHP User Manager</h1>
<p class="lead">Login Page</p>
<hr>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolderHeader = ob_get_contents();
/* Clean out the buffer, but do not destroy the output buffer */
ob_clean();
?>
<!-- Main Element Content -->
<div class="col-sm-4 mx-auto text-center">
    <form class="form-signin" action="AuthenticateUser.php" method="post">
        <img class="mb-4" src="img/logo.png" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Please log in:</h1>
        <label for="username" class="sr-only">Username</label>
        <input type="email" name="username" class="form-control" placeholder="Username" id="username" required autofocus />
        <br>
        <label for="password" class="sr-only">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Password" id="password" required />
        <br>
        <button class="btn btn-lg btn-primary btn-block" type="submit"><i class="fas fa-sign-out-alt"></i> Log in</button>
        <?php
        if (isset($_SESSION["IsLockedOut"])) {
            if ($_SESSION["IsLockedOut"] == true) {
                echo "<br><p class=\"font-weight-bold text-danger\">We are sorry, but your account is locked.<br>Please contact your administrator.</p>";
                session_destroy();
            }
        }
        if (isset($_SESSION["Authenticated"])) {
            if ($_SESSION["Authenticated"] == false) {
                echo "<br><p class=\"font-weight-bold text-danger\">Incorrect username or password.<br>Please try again.</p>";
                session_destroy();
            } else {
                header("Location: MainPage.php");
                exit();
            }
        }
        ?>
    </form>
</div>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolderMain = ob_get_contents();
/* Clean out the buffer once again, but do not destroy the output buffer */
ob_clean();
?>
<!-- Footer Element Content -->

<!-- Body Content End -->
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolderFooter = ob_get_contents();
/* Clean out the buffer and turn off output buffering */
ob_end_clean();
/* Call the master page. It will echo the content of the placeholders in the designated locations */
require_once "MasterPage.php";
