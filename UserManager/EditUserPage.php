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

require_once "User.class.php";
require_once "UMCommonCode.php";
require_once "UserDB.class.php";

if ($_SESSION["Authenticated"] == false) {
    header("Location: LoginPage.php");
    exit();
}
/* Start placing content into an output buffer */
ob_start();
?>
<!-- Head Content Start -->
<title>Edit User | PHP User Manager</title>
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
<p class="lead">Edit User</p>
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
use UserManager\User;

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
require_once "UMMasterPage.php";
