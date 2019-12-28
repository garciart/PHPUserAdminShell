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
require_once "Role.class.php";
require_once "UserDB.class.php";

if ($_SESSION["Authenticated"] == false) {
    header("Location: LoginPage.php");
    exit();
}
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
<h1 class="mt-5">PHP User Manager</h1>
<p class="lead">Role Administration Page</p>
<hr>
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
$result = $userDB->getAllRoles();
if ($result) {
    echo "<table class=\"table table-bordered table-striped\" data-toggle=\"table\" id=\"adminTable\">";
    echo "<thead>";
    echo "<tr>";
    echo "<th>RoleID:</th>";
    echo "<th>Title:</th>";
    echo "<th class=\"text-center\">Action</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($result as $row) {
        echo "<tr>";
        echo "<td><a href=\"ViewRolePage.php?RoleID={$row["RoleID"]}\" title=\"View Role Details\" data-toggle=\"tooltip\">{$row["RoleID"]}</a></td>";
        echo "<td><a href=\"ViewRolePage.php?RoleID={$row["RoleID"]}\" title=\"View Role Details\" data-toggle=\"tooltip\">{$row["Title"]}</a></td>";
        echo "<td class=\"text-center\">";
        echo "<a href=\"ViewRolePage.php?RoleID={$row["RoleID"]}\" title=\"View Role Details\" data-toggle=\"tooltip\"><i class=\"far fa-eye\"></i></a>&nbsp;";
        echo "<a href=\"EditRolePage.php?RoleID={$row["RoleID"]}\" title=\"Edit Role\" data-toggle=\"tooltip\"><i class=\"far fa-edit\"></i></a>&nbsp;";
        echo "<a href=\"DeleteRolePage.php?RoleID={$row["RoleID"]}\" title=\"Delete Role\" data-toggle=\"tooltip\"><i class=\"far fa-trash-alt\"></i></a>";
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
