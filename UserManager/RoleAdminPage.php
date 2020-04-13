<?php
/**
 * Landing page for role administration.
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
    <title>Role Administration Page | PHP User Manager</title>
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
            <h2>Role Administration:&nbsp;</h2>
        </div>
        <div>
            <?php
            // Get success flag from query string and check for errors
            $success = filter_input(INPUT_GET, "success", FILTER_SANITIZE_NUMBER_INT);
            if ($success == '0') {
                echo "<h2 class=\"pull-left text-danger\">Error: Cannot retrieve role data!</h2>";
            } else if ($success == '1') {
                echo "<h2 class=\"pull-left text-success\">Role added</h2>";
            } else if ($success == '-1') {
                echo "<h2 class=\"pull-left text-danger\">Role not added</h2>";
            } else if ($success == '2') {
                echo "<h2 class=\"pull-left text-success\">Role updated</h2>";
            } else if ($success == '-2') {
                echo "<h2 class=\"pull-left text-danger\">Role not updated</h2>";
            } else if ($success == '3') {
                echo "<h2 class=\"pull-left text-success\">Role deleted</h2>";
            } else if ($success == '-3') {
                echo "<h2 class=\"pull-left text-danger\">Role not deleted</h2>";
            } else if ($success == '-666') {
                echo "<h2 class=\"pull-left text-danger\">Unknown Fatal Error! Contact administrator to review log</h2>";
            }
            ?>
        </div>
        <div class="ml-auto">
            <a href="RoleCreatePage.php" class="btn btn-primary">Add New Role</a>
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
        $result = $userDB->getAllRoles();
        if ($result) {
            ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" data-toggle="table" id="adminTable">
                    <thead>
                        <tr>
                            <th>RoleID:</th>
                            <th>Title:</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($result as $row) {
                            echo "<tr>";
                            echo "<td><a href=\"RoleViewPage.php?RoleID={$row["RoleID"]}\" title=\"View Role Details\" data-toggle=\"tooltip\">{$row["RoleID"]}</a></td>";
                            echo "<td><a href=\"RoleViewPage.php?RoleID={$row["RoleID"]}\" title=\"View Role Details\" data-toggle=\"tooltip\">{$row["Title"]}</a></td>";
                            echo "<td class=\"text-center\">";
                            echo "<a href=\"RoleViewPage.php?RoleID={$row["RoleID"]}\" title=\"View Role Details\" data-toggle=\"tooltip\"><i class=\"far fa-eye\"></i></a>&nbsp;";
                            echo "<a href=\"RoleEditPage.php?RoleID={$row["RoleID"]}\" title=\"Edit Role\" data-toggle=\"tooltip\"><i class=\"far fa-edit\"></i></a>&nbsp;";
                            echo "<a href=\"RoleDeletePage.php?RoleID={$row["RoleID"]}\" title=\"Delete Role\" data-toggle=\"tooltip\"><i class=\"far fa-trash-alt\"></i></a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <a href="MainPage.php" class="btn btn-primary pull-left">Return to Main Administration Page</a>
            <?php
            unset($result);
        } else {
            echo "<h2 class=\"text-danger\"><em>No records were found.</em></h2>";
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
