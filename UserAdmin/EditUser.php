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
<title>Edit User | PHP User Admin Shell</title>
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
<p class="lead">Edit User Page</p>
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
$result = $userDB->getUserByUsername($username);
$user = new User($result["UserID"], $result["Username"], $result["Nickname"], $result["PasswordHash"], $result["RoleID"], $result["Email"], $result["IsLockedOut"], $result["LastLoginDate"], $result["CreateDate"], $result["Comment"]);
?>
<form action="" method="post">
    <div class="container">
        <div class="row">
            <div class="col"><label for="username">Username:</label></div>
            <div class="col"><input type="email" name="username" class="form-control" placeholder="<?php echo $user->getUsername() ?>" id="username" required autofocus></div>
        </div>
        <br>
        <div class="row">
            <div class="col"><label for="email">Email:</label></div>
            <div class="col"><input type="email" name="email" class="form-control" placeholder="<?php echo $user->getEmail() ?>" id="username" required autofocus></div>
        </div>
    </div>
</form>
<?php
echo "UserID: " . $user->getUserID() . "<br>";
echo "Username: " . $user->getUsername() . "<br>";
echo "Nickname: " . $user->getNickname() . "<br>";
echo "PasswordHash: " . $user->getPasswordHash() . "<br>";
echo "RoleID: " . $user->getRoleID() . "<br>";
echo "Email: " . $user->getEmail() . "<br>";
echo "IsLockedOut: " . $user->getIsLockedOut() . "<br>";
echo "LastLoginDate: " . $user->getLastLoginDate() . "<br>";
echo "CreateDate: " . $user->getCreateDate() . "<br>";
echo "Comment: " . $user->getComment() . "<br>";
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
include("../Master.php");
