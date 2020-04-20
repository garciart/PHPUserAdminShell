<?php
/**
 * Edit role details page.
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
if ($_SESSION["Authenticated"] == false || $_SESSION["Authenticated"] == 0) {
    header("Location: LoginPage.php");
    exit();
} else if ($_SESSION["Level"] >= 1 && $_SESSION["Level"] <= 15) {
    header("Location: MainPage.php");
    exit();
} else {
    $result = "";
    $errorAlert = "";
    $roleID = $level = $title = $comment = "";
    $roleIDError = $levelError = $titleError = $commentError = "";
    /* Start placing content into an output buffer */
    ob_start();
    ?>
    <!-- Head Content Start -->
    <title>Edit Role | PHP User Manager</title>
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
            <h2>Edit Role Details:&nbsp;</h2>
            <h2 id="errorAlert" class="text-danger">
                <?php echo $errorAlert; ?>
            </h2>
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
    <?php
    // Connect to the database
    $userDB = new UserDB();
    /*
     * VALIDATE INPUT BUT DO NOT SANITIZE!
     * Sanitizing may allow incorrect data processing (e.g., 1 = 1 turns to 11.0, etc)
     * Display error if input is not valid
     */
    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == "POST") {
        $roleID = filter_input(INPUT_POST, "RoleID");
        $level = filter_input(INPUT_POST, "Level");
        $title = filter_input(INPUT_POST, "Title");
        $comment = filter_input(INPUT_POST, "Comment");

        $valid = true;

        if (validateID($roleID) != true) {
            $valid = false;
            $roleIDError = "Role ID number must be greater than 0.";
        }

        if (validateLevel($level) != true) {
            $valid = false;
            $roleIDError = "Level must be greater than 0 and less than 20.";
        }

        if (!empty($title)) {
            if (validateText($title) != true) {
                $valid = false;
                $titleError = "Title must be alphanumeric.";
            }
        }

        if (!empty($comment)) {
            if (validateText($comment) != true) {
                $valid = false;
                $commentError = "Comments must be alphanumeric.";
            }
        }

        if ($valid == true) {
            $success = $userDB->updateRole($roleID, $level, $title, $comment);
            if ($success == 1) {
                header("Location: RoleAdminPage.php?success=2");
                die();
            } else if ($success == 0) {
                header("Location: RoleAdminPage.php?success=-2");
                die();
            } else {
                header("Location: RoleAdminPage.php?success=-666");
                die();
            }
        } else {
            $errorAlert = "Format error: Check your data!";
        }
    } else if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == "GET") {
        $result = $userDB->getRole(cleanText(filter_input(INPUT_GET, "RoleID", FILTER_SANITIZE_NUMBER_INT)));
        if (!empty($result)) {
            $roleID = $result['RoleID'];
            $level = $result['Level'];
            $title = $result['Title'];
            $comment = $result['Comment'];
        } else {
            header("Location: RoleAdminPage.php?success=-2");
        }
    }
    ?>
    <div class="row">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="w-100">
            <div class="table-responsive">
                <table class='table table-bordered table-striped'>
                    <tr>
                        <th>Title:</th>
                        <td class="w-100">
                            <input type="text" name="Title" class="form-control" value="<?php echo $title; ?>" required autofocus>
                            <br>
                            <span class="text-danger">
                                <?php echo $titleError; ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Level:</th>
                        <td>
                            <?php
                            if ($roleID == $_SESSION['RoleID']) {
                                echo "<select name='Level' disabled>";
                            } else {
                                echo "<select name='Level'>";
                            }
                            for ($i = 1; $i <= 20; $i++) {
                                $selected = ($i == $level ? "selected" : "");
                                echo "<option value=\"{$i}\" {$selected}>{$i}</option>";
                            }
                            echo "</select>";
                            ?>
                            <br>
                            <span class="text-danger">
                                <?php echo $levelError; ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Comments:</th>
                        <td>
                            <textarea class="editarea w-100" maxlength="512" name="Comment" rows="4"><?php echo $comment; ?></textarea>
                            <br>
                            <span class="text-danger">
                                <?php echo $commentError; ?>
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
            <input type="hidden" name="RoleID" value="<?php echo $roleID; ?>"/>
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="RoleAdminPage.php" class="btn btn-secondary">Cancel</a>
        </form>
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
