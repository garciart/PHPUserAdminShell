<?php
/**
 * Landing page for user administration.
 */
session_start();

if ($_SESSION["Authenticated"] == FALSE) {
    header('Location: /PHPUserAdminShell/Login.php');
    exit();
}
/* Start placing content into an output buffer */
ob_start();
?>
<!-- Head Content Start -->
<title>User Profile | PHP User Admin Shell</title>
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
<p class="lead">User Profile Page</p>
<hr>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolderHeader = ob_get_contents();
/* Clean out the buffer, but do not destroy the output buffer */
ob_clean();
?>
<!-- Main Element Content -->
<?php
require_once "UserDB.php";
require_once "User.php";

// Get the class name
use UserAdmin\UserDB;
use UserAdmin\User;

// Get and filter the posted data
$username = filter_input(INPUT_POST, "username");
if (!isset($username)) {
    die("No username submitted.");
}

// Connect to the database
$userDB = new UserDB();
$pdo = $userDB->connect();
if ($pdo != null) {
    $result = $userDB->getUserDetails($username);
    $user = new User($result["UserID"], $result["UserName"], $result["PasswordHash"], $result["RoleID"], $result["Email"], $result["IsLockedOut"], $result["LastLoginDate"], $result["CreateDate"], $result["Comment"]);
    $userRole = $userDB->getUserRole($user->getRoleID());
    /*
      echo "UserID: " . $user->getUserID() . "<br>";
      echo "UserName: " . $user->getUserName() . "<br>";
      echo "PasswordHash: " . $user->getPasswordHash() . "<br>";
      echo "RoleID: " . $user->getRoleID() . "<br>";
      echo "Email: " . $user->getEmail() . "<br>";
      echo "IsLockedOut: " . $user->getIsLockedOut() . "<br>";
      echo "LastLoginDate: " . $user->getLastLoginDate() . "<br>";
      echo "CreateDate: " . $user->getCreateDate() . "<br>";
      echo "Comment: " . $user->getComment() . "<br>";
     */
} else {
    die("Could not connect to the database.<br>");
}
?>
<table class="table table-striped">
    <tr>
        <th>User ID:</th>
        <td><?php echo $user->getUserID() ?></td>
    </tr>
    <tr>
        <th>User Name:</th>
        <td><?php echo $user->getUserName() ?></td>
    </tr>
    <tr>
        <th>Password Hash:</th>
        <td><?php echo $user->getPasswordHash() ?></td>
    </tr>
    <tr>
        <th>Role ID:</th>
        <td><?php echo $user->getRoleID() ?></td>
    </tr>
    <tr>
        <th>Title:</th>
        <td><?php echo $userRole ?></td>
    </tr>
    <tr>
        <th>Email:</th>
        <td><?php echo $user->getEmail() ?></td>
    </tr>
    <tr>
        <th>Is Locked Out:</th>
        <td><?php echo $user->getIsLockedOut() ?></td>
    </tr>
    <tr>
        <th>Last Login Date:</th>
        <td><?php echo $user->getLastLoginDate() ?></td>
    </tr>
    <tr>
        <th>Create Date:</th>
        <td><?php echo $user->getCreateDate() ?></td>
    </tr>
    <tr>
        <th>Comments:</th>
        <td><?php echo $user->getComment() ?></td>
    </tr>
</table>
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
include("../Master.php");
