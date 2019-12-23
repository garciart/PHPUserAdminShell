<?php
/**
 * Landing page for user administration.
 *
 * PHP version 5.3
 *
 * @author  Rob Garcia <rgarcia@rgprogramming.com>
 * @license https://opensource.org/licenses/MIT The MIT License
 * @version GIT: $Id$ In development
 * @link    https://github.com/garciart/PHPUserAdminShell GitHub Repository
 */
session_start();

require_once "User.class.php";
require_once "AdminCommonCode.php";
require_once "UserDB.class.php";

if ($_SESSION["Authenticated"] == false) {
    header("Location: LoginPage.php");
    exit();
}
/* Start placing content into an output buffer */
ob_start();
?>
<!-- Head Content Start -->
<title>Administration | PHP User Admin Shell</title>
<!-- Head Content End -->
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolderHead = ob_get_contents();
/* Clean out the buffer, but do not destroy the output buffer */
ob_clean();
?>
<!-- Body Content Start -->
<!-- Header Element Content -->
<h1 class="mt-5">PHP User Admin Shell</h1>
<p class="lead">Administration Landing Page</p>
<hr>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolderHeader = ob_get_contents();
/* Clean out the buffer, but do not destroy the output buffer */
ob_clean();
?>
<!-- Main Element Content -->
<?php echo "<div class=\"lead text-center\"><p>Welcome back, {$_SESSION["Nickname"]}.</p><p>Please select one of the options below:</p></div>"; ?>
<div class="btn-toolbar my-5">
    <a href="UserAdminPage.php" class="btn btn-primary btn-block">User Administration</a>
    <a href="RoleAdminPage.php" class="btn btn-primary btn-block">Role Administration</a>
    <a href="ActivityLogPage.php" class="btn btn-primary btn-block">Admin Activity Log</a>
    <a href="ErrorLogPage.php" class="btn btn-primary btn-block">Error Log</a>
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
require_once "AdminMasterPage.php";