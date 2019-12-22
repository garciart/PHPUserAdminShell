<?php
/**
 * Site landing page.
 *
 * PHP version 5.3
 *
 * @author  Rob Garcia <rgarcia@rgprogramming.com>
 * @license https://opensource.org/licenses/MIT The MIT License
 * @version GIT: $Id$ In development
 * @link    https://github.com/garciart/PHPUserAdminShell GitHub Repository
 */
session_start();
/* Start placing content into an output buffer */
ob_start();
?>
<!-- Head Content Start -->
<title>Home | PHP User Admin Shell</title>
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
<p class="lead">Landing Page</p>
<hr>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolderHeader = ob_get_contents();
/* Clean out the buffer, but do not destroy the output buffer */
ob_clean();
?>
<!-- Main Element Content -->
<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed nonummy nibh euismod tincidunt ut laoreet dolore magna aliat volutpat. Ut wisi enim ad minim veniam, quis nostrud exercita ullamcorper suscipit lobortis nisl ut aliquip ex consequat.</p>
<!-- Notice we can include code in the buffer as well -->
<?php
for ($x = 10; $x >= 1; $x--) {
    echo "T-$x and counting...<br>";
}
echo "Lift-off!<br>"
?>
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
require_once "Master.php";
