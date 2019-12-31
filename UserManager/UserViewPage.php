<?php
/**
 * View user details page.
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
<title>View User Details | PHP User Manager</title>
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
        <h2>View User Details:&nbsp;</h2>
    </div>

    <hr>
    <?php
    /* Store the content of the buffer for later use */
    $contentPlaceHolderHeader = ob_get_contents();
    /* Clean out the buffer, but do not destroy the output buffer */
    ob_clean();
    ?>
    <!-- Main Element Content -->
    <div>
        <?php

// Get the class name
        use UserManager\UserDB;

// Connect to the database
        $userDB = new UserDB();
        
        $result = $userDB->getUserByUserID(cleanText(filter_input(INPUT_GET, "UserID", FILTER_SANITIZE_NUMBER_INT)));
        if (!empty($result)) {
            echo "<table class='table table-bordered table-striped'>";
            echo "<tr><th nowrap>User ID:</th><td>{$result['UserID']}</td></tr>";
            echo "<tr><th nowrap>User Name:</th><td>{$result['Username']}</td></tr>";
            echo "<tr><th nowrap>Nickname:</th><td>{$result['Nickname']}</td></tr>";
            // echo "<tr><th>Role ID:</th><td>{$result['RoleID']}</td></tr>";
            $role = $userDB->getRole($result['RoleID']);
            echo "<tr><th nowrap>Role:</th><td>" . $role['Title'] . "</td></tr>";
            echo "<tr><th nowrap>Email:</th><td>{$result['Email']}</td></tr>";
            $lockedOut = $result['IsLockedOut'] == 0 ? "No" : "<span class=\"text-danger\">Yes</span>";
            echo "<tr><th nowrap>Locked Out:</th><td>{$lockedOut}</td></tr>";
            echo "<tr><th nowrap>Last Login Date:</th><td>{$result['LastLoginDate']}</td></tr>";
            echo "<tr><th nowrap>Account Creation Date:</th><td>{$result['CreateDate']}</td></tr>";
            echo "<tr><th nowrap>Comments:</th><td>{$result['Comment']}</td></tr>";
            echo "</table>";
            ?>
            <div class='btn-toolbar'>
                <a href="UserAdminPage.php" class="btn btn-primary pull-left">Return to User Administration</a>
            </div>
            <?php
            unset($result);
        } else {
            header("Location: UserAdminPage.php?success=0");
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
    