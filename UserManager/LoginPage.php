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
 * @package   UserManager
 * @author    Rob Garcia <rgarcia@rgprogramming.com>
 * @copyright 2019-2020 Rob Garcia
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @link      https://github.com/garciart/PHPUserManager
 */
/* Check if a session is already active */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

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
<div class="row">
    <div class="col-md-4 mx-auto text-center">
        <form class="form-signin" action="AuthenticateUser.php" method="post">
            <img src="img/page_logo.png" alt="" width="150" height="150">
            <h1 class="h3 my-3">Please log in:</h1>
            <label for="username" class="sr-only">Username</label>
            <input type="email" name="username" class="form-control" placeholder="Username" id="username" required autofocus />
            <br>
            <label for="password" class="sr-only">Password</label>
            <input id="password" name="password" type="password" autocomplete="on" class="form-control" placeholder="Password" required />
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
                if (!isset($_SESSION["Authenticated"]) || $_SESSION["Authenticated"] == false || $_SESSION["Authenticated"] == 0) {
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
    <div class="col-md-4 mx-auto text-center">
        <form class="form-signin" action="AuthenticateEmail.php" method="post">
            <img src="img/page_logo.png" alt="" width="150" height="150">
            <h1 class="h3 my-3">Forgot your password?</h1>
            <label for="email" class="sr-only">Please enter your email address below:</label>
            <input type="email" name="email" class="form-control" placeholder="Email Address" id="email" required />
            <br>
            <button class="btn btn-lg btn-warning btn-block" type="submit"><i class="fas fa-unlock-alt"></i> Reset Password</button>
            <?php
            // Get success flag from query string and check for errors
            $reset = filter_input(INPUT_GET, "reset", FILTER_SANITIZE_STRING);
            if ($reset == "true") {
                echo "<br><p class=\"font-weight-bold text-success\">Instructions on resetting your password have been sent to your email address.</p>";
            } else if ($reset == "false") {
                echo "<br><p class=\"font-weight-bold text-danger\">Incorrect answer.<br>Please check your credentials and try again.</p>";
            }
            if (isset($_SESSION["IsLockedOut"])) {
                if ($_SESSION["IsLockedOut"] == true) {
                    echo "<br><p class=\"font-weight-bold text-danger\">We are sorry, but your account is locked.<br>Please contact your administrator.</p>";
                    session_destroy();
                }
            }
            if (isset($_SESSION["Exists"])) {
                if ($_SESSION["Exists"] == false) {
                    echo "<br><p class=\"font-weight-bold text-danger\">Email address does not exist in the system.<br>Please try again.</p>";
                    session_destroy();
                } else {
                    header("Location: ResetPassword.php");
                    exit();
                }
            }
            ?>
        </form>
    </div>
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
