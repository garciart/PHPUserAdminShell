<?php
/**
 * Landing page for user administration.
 *
 * PHP version 5.3
 *
 * @author  Rob Garcia <rgarcia@rgprogramming.com>
 * @license https://opensource.org/licenses/MIT The MIT License
 * @version 1.0
 * @link    https://github.com/garciart/PHPUserManager GitHub Repository
 */
// Start session if not started. Must be started by page, not Master
if (!isset($_SESSION)) {
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
    <title>User Administration Page | PHP User Manager</title>
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
            <h2>User Administration:&nbsp;</h2>
        </div>
        <div>
            <?php
            // Get success flag from query string and check for errors
            $success = filter_input(INPUT_GET, "success", FILTER_SANITIZE_NUMBER_INT);
            if ($success == '0') {
                echo "<h2 class=\"pull-left text-danger\">Error: Cannot retrieve user data!</h2>";
            } else if ($success == '1') {
                echo "<h2 class=\"pull-left text-success\">User added</h2>";
            } else if ($success == '-1') {
                echo "<h2 class=\"pull-left text-danger\">User not added</h2>";
            } else if ($success == '2') {
                echo "<h2 class=\"pull-left text-success\">User updated</h2>";
            } else if ($success == '-2') {
                echo "<h2 class=\"pull-left text-danger\">User not updated</h2>";
            } else if ($success == '3') {
                echo "<h2 class=\"pull-left text-success\">User deleted</h2>";
            } else if ($success == '-3') {
                echo "<h2 class=\"pull-left text-danger\">User not deleted</h2>";
            } else if ($success == '-666') {
                echo "<h2 class=\"pull-left text-danger\">Unknown Fatal Error! Contact administrator to review log</h2>";
            }
            ?>
        </div>
        <div class="ml-auto">
            <a href="UserCreatePage.php" class="btn btn-primary">Add New User</a>
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
        $result = $userDB->getAllUsers();
        if ($result) {
            ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" data-toggle="table" id="adminTable">
                    <thead>
                        <tr>
                            <th>UserID:</th>
                            <th>Username:</th>
                            <th>Nickname:</th>
                            <th>Role:</th>
                            <th>Locked Out?</th>
                            <th>Last Login:</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($result as $row) {
                            echo "<tr>";
                            echo "<td><a href=\"UserViewPage.php?UserID={$row["UserID"]}\" title=\"View User Details\" data-toggle=\"tooltip\">{$row["UserID"]}</a></td>";
                            echo "<td><a href=\"UserViewPage.php?UserID={$row["UserID"]}\" title=\"View User Details\" data-toggle=\"tooltip\">{$row["Username"]}</a></td>";
                            echo "<td>{$row["Nickname"]}</td>";
                            $role = $userDB->getRole($row["RoleID"]);
                            echo "<td>{$role["Title"]}</td>";
                            $lockedOut = $row['IsLockedOut'] == 0 ? "No" : "<span class=\"text-danger\"><strong>Yes</strong></span>";
                            echo "<td>{$lockedOut}</td>";
                            echo "<td>{$row['LastLoginDate']}</td>";
                            echo "<td class=\"text-center\">";
                            echo "<a href=\"UserViewPage.php?UserID={$row["UserID"]}\" title=\"View User Details\" data-toggle=\"tooltip\"><i class=\"far fa-eye\"></i></a>&nbsp;";
                            echo "<a href=\"UserEditPage.php?UserID={$row["UserID"]}\" title=\"Edit User\" data-toggle=\"tooltip\"><i class=\"far fa-edit\"></i></a>&nbsp;";
                            echo "<a href=\"UserDeletePage.php?UserID={$row["UserID"]}\" title=\"Delete User\" data-toggle=\"tooltip\"><i class=\"far fa-trash-alt\"></i></a>";
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
    require_once "MainPage.php";
}