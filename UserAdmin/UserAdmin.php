<?php
/**
 * Landing page for user administration.
 */
session_start();

if ($_SESSION["Authenticated"] == FALSE) {
    header("Location: /PHPUserAdminShell/Login.php");
    exit();
}
/* Start placing content into an output buffer */
ob_start();
?>
<!-- Head Content Start -->
<title>User Administration | PHP User Admin Shell</title>
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
<p class="lead">User Administration Page</p>
<hr>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolderHeader = ob_get_contents();
/* Clean out the buffer, but do not destroy the output buffer */
ob_clean();
?>
<!-- Main Element Content -->
<?php
echo "Hello, {$_SESSION["Nickname"]}, you have been successfully authenticated.<br>";

require_once "UserDB.php";
require_once "User.php";

// Get the class name
use UserAdmin\UserDB;
use UserAdmin\User;

// Connect to the database
$userDB = new UserDB();
$pdo = $userDB->connect();
if ($pdo != null) {
    $result = $userDB->getAllUsers();
    foreach ($result as $r) {
        foreach ($r as $c) {
            echo "$c | ";
        }
        echo "<br>";
    }
} else {
    die("Could not connect to the database.<br>");
}

echo "Authenticated: " . $_SESSION["Authenticated"] . "<br>";
// echo "UserID: " . $_SESSION["UserID"] . "<br>";
echo "Username: " . $_SESSION["Username"] . "<br>";
// echo "PasswordHash: " . $_SESSION["PasswordHash"] . "<br>";
echo "RoleID: " . $_SESSION["RoleID"] . "<br>";
// echo "Email: " . $_SESSION["Email"] . "<br>";
// echo "IsLockedOut: " . $_SESSION["IsLockedOut"] . "<br>";
// echo "LastLoginDate: " . $_SESSION["LastLoginDate"] . "<br>";
// echo "CreateDate: " . $_SESSION["CreateDate"] . "<br>";
// echo "Comment: " . $_SESSION["Comment"] . "<br>";
?>
<br>
<form action="EditUser.php" method="post">
    <input name="username" value="<?php echo $_SESSION["Username"] ?>" hidden />
    <button class="btn btn-lg btn-primary btn-block" type="submit">Edit User</button>
</form>
<br>
<form action="UserProfile.php" method="post">
    <input name="username" value="<?php echo $_SESSION["Username"] ?>" hidden />
    <button class="btn btn-lg btn-primary btn-block" type="submit">View Profile</button>
</form>
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
