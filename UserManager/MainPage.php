<?php
/**
 * Site landing page.
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
require_once "User.class.php";
require_once "UserDB.class.php";

if (!isset($_SESSION["Authenticated"]) || $_SESSION["Authenticated"] == false || $_SESSION["Authenticated"] == 0) {
    header("Location: LoginPage.php");
    exit();
} else {
    /* Start placing content into an output buffer */
    ob_start();
    ?>
    <!-- Head Content Start -->
    <title>Administration | PHP User Manager</title>
    <!-- Head Content End -->
    <?php
    /* Store the content of the buffer for later use */
    $contentPlaceHolderHead = ob_get_contents();
    /* Clean out the buffer, but do not destroy the output buffer */
    ob_clean();
    ?>
    <!-- Body Content Start -->
    <!-- Header Element Content -->
    <h1 class="mt-3 pull-left">PHP User Manager</h1>
    <p class="lead">Administration Landing Page</p>
    <div>
        <?php
        // Get success flag from query string and check for errors
        $success = filter_input(INPUT_GET, "success", FILTER_SANITIZE_NUMBER_INT);
        if ($success == '0') {
            echo "<h2 class=\"pull-left text-danger\">Error: Cannot retrieve user data!</h2>";
        } else if ($success == '2') {
            echo "<h2 class=\"pull-left text-success\">Profile updated</h2>";
        } else if ($success == '-2') {
            echo "<h2 class=\"pull-left text-danger\">Profile not updated</h2>";
        } else if ($success == '-666') {
            echo "<h2 class=\"pull-left text-danger\">Unknown Fatal Error! Contact administrator to review log</h2>";
        }
        ?>
    </div>
    <hr>
    <?php
    /* Store the content of the buffer for later use */
    $contentPlaceHolderHeader = ob_get_contents();
    /* Clean out the buffer, but do not destroy the output buffer */
    ob_clean();
    ?>
    <!-- Main Element Content -->
    <?php echo "<div class=\"page-header text-center\"><p>Welcome back, {$_SESSION["Nickname"]}.<br>Please select one of the options below:</p></div>"; ?>
    <div class="col-md-4 mx-auto text-center">
        <div class="btn-toolbar my-3">
            <a href="EditProfile.php" class="btn btn-primary btn-block">Edit Profile</a>
            <?php if ($_SESSION["AccessLevel"] >= 6) { ?>
                <a href="UserAdminPage.php" class="btn btn-primary btn-block">User Administration</a>
                <a href="RoleAdminPage.php" class="btn btn-primary btn-block">Role Administration</a>
                <a href="ActivityLogPage.php" class="btn btn-primary btn-block">Admin Activity Log</a>
                <a href="ErrorLogPage.php" class="btn btn-primary btn-block">Error Log</a>
            <?php } ?>
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
}