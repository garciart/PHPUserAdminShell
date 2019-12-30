<?php
/**
 * Landing page for user administration.
 *
 * PHP version 5.3
 *
 * @author  Rob Garcia <rgarcia@rgprogramming.com>
 * @license https://opensource.org/licenses/MIT The MIT License
 * @version GIT: $Id$ In development
 * @link    https://github.com/garciart/PHPUserManager GitHub Repository
 */
session_start();

require_once "UMCommonCode.php";
require_once "User.class.php";
require_once "UserDB.class.php";

if ($_SESSION["Authenticated"] == false) {
    header("Location: LoginPage.php");
    exit();
}
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
<div class="row">
    <div class="col-md-9 pull-left">
        <h2>User Administration:&nbsp;</h2>
    </div>
    <div class="col-md-3 pull-right text-right">
        <a href="UserCreatePage.php" class="btn btn-primary">Add New Building</a>
    </div>
    <?php
    $success = filter_input(INPUT_GET, "success", FILTER_SANITIZE_NUMBER_INT);
    if ($success == '0') {
        echo "<h2 class=\"pull-left text-danger\">Error: Cannot retrieve building data!</h2>";
    } else if ($success == '1') {
        echo "<h2 class=\"pull-left text-success\">Building added</h2>";
    } else if ($success == '-1') {
        echo "<h2 class=\"pull-left text-danger\">Building not added</h2>";
    } else if ($success == '2') {
        echo "<h2 class=\"pull-left text-success\">Building updated</h2>";
    } else if ($success == '-2') {
        echo "<h2 class=\"pull-left text-danger\">Building not updated</h2>";
    } else if ($success == '3') {
        echo "<h2 class=\"pull-left text-success\">Building deleted</h2>";
    } else if ($success == '-3') {
        echo "<h2 class=\"pull-left text-danger\">Building not deleted</h2>";
    } else if ($success == '-666') {
        echo "<h2 class=\"pull-left text-danger\">Unknown Fatal Error! Contact administrator to review log</h2>";
    }
    ?>
</div>
<br>
<div>
    <?php
    /* Store the content of the buffer for later use */
    $contentPlaceHolderHeader = ob_get_contents();
    /* Clean out the buffer, but do not destroy the output buffer */
    ob_clean();
    ?>
    <!-- Main Element Content -->
    <?php

// Get the class name
    use UserManager\UserDB;

// Connect to the database
    $userDB = new UserDB();
    $result = $userDB->getAllUsers();
    if ($result) {
        echo "<table class=\"table table-bordered table-striped\" data-toggle=\"table\" id=\"adminTable\">";
        echo "<thead>";
        echo "<tr>";
        echo "<th>UserID:</th>";
        echo "<th>Username:</th>";
        echo "<th>Nickname:</th>";
        echo "<th>RoleID:</th>";
        echo "<th>Email:</th>";
        echo "<th class=\"text-center\">Action</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($result as $row) {
            echo "<tr>";
            echo "<td><a href=\"UserViewPage.php?UserID={$row["UserID"]}\" title=\"View User Details\" data-toggle=\"tooltip\">{$row["UserID"]}</a></td>";
            echo "<td><a href=\"UserViewPage.php?UserID={$row["UserID"]}\" title=\"View User Details\" data-toggle=\"tooltip\">{$row["Username"]}</a></td>";
            echo "<td>{$row["Nickname"]}</td>";
            echo "<td>{$row["RoleID"]}</td>";
            echo "<td>{$row["Email"]}</td>";
            echo "<td class=\"text-center\">";
            echo "<a href=\"UserViewPage.php?UserID={$row["UserID"]}\" title=\"View User Details\" data-toggle=\"tooltip\"><i class=\"far fa-eye\"></i></a>&nbsp;";
            echo "<a href=\"UserEditPage.php?UserID={$row["UserID"]}\" title=\"Edit User\" data-toggle=\"tooltip\"><i class=\"far fa-edit\"></i></a>&nbsp;";
            echo "<a href=\"UserDeletePage.php?UserID={$row["UserID"]}\" title=\"Delete User\" data-toggle=\"tooltip\"><i class=\"far fa-trash-alt\"></i></a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        unset($result);
    } else {
        echo "<p class=\"lead\"><em>No records were found.</em></p>";
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
require_once "UMMasterPage.php";
