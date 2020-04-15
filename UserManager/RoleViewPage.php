<?php
/**
 * View role details page.
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

// Include this file to access common functions and variables
require_once "CommonCode.php";
// Include database class to access database methods
require_once "UserDB.class.php";

// Get the class name. Must be declared in the global scope of the file: see https://www.php.net/manual/en/language.namespaces.importing.php 
use UserManager\UserDB;

// Ensure the user is authenticated and authorized
if ($_SESSION["Authenticated"] == false) {
    header("Location: LoginPage.php");
    exit();
} else {
    /* Start placing content into an output buffer */
    ob_start();
    ?>
    <!-- Head Content Start -->
    <title>View Role Details | PHP User Manager</title>
    <!-- Head Content End -->
    <?php
    /* Store the content of the buffer for later use */
    $contentPlaceHolderHead = ob_get_contents();
    /* Clean out the buffer, but do not destroy the output buffer */
    ob_clean();
    ?>
    <!-- Body Content Start -->
    <!-- Header Element Content -->
    <div class="mt-3 row">
        <div>
            <h2>View Role Details:</h2>
        </div>
    </div>
    <hr>
    <?php
    /* Store the content of the buffer for later use */
    $contentPlaceHolderHeader = ob_get_contents();
    /* Clean out the buffer, but do not destroy the output buffer */
    ob_clean();
    ?>
    <!-- Main Element Content -->
    <div class="row">
        <?php
        // Connect to the database
        $userDB = new UserDB();

        // Get RoleID from query string and query database
        $result = $userDB->getRole(cleanText(filter_input(INPUT_GET, "RoleID", FILTER_SANITIZE_NUMBER_INT)));
        if (!empty($result)) {
            echo "<div class=\"table-responsive\">";
            echo "<table class='table table-bordered table-striped'>";
            echo "<tr><th>Role ID:</th><td style=\"width: 100%;\">{$result['RoleID']}</td></tr>";
            echo "<tr><th>Title:</th><td>{$result['Title']}</td></tr>";
            echo "<tr><th>Level:</th><td>{$result['Level']}</td></tr>";
            echo "<tr><th>Comments:</th>";
            echo "<td><textarea rows=\"4\" class=\"w-100\" readonly>{$result['Comment']}</textarea></td></tr>";
            echo "</table>";
            echo "</div>";
            ?>
            <a href="RoleAdminPage.php" class="btn btn-primary pull-left">Return to Role Administration</a>
            <?php
            unset($result);
        } else {
            header("Location: RoleAdminPage.php?success=0");
        }
        ?>
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