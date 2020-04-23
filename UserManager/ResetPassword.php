<?php
/**
 * Recover password page.
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

require_once "CommonCode.php";

if (!isset($_SESSION["Exists"]) || $_SESSION["Exists"] == false || $_SESSION["Exists"] == 0) {
    header("Location: LoginPage.php");
    exit();
} else {
// Unset so back buttons work        
// unset($_SESSION["Exists"]);

    /* Start placing content into an output buffer */
    ob_start();
    ?>
    <!-- Head Content Start -->
    <title>Recover Password | PHP User Manager</title>
    <!-- Head Content End -->
    <?php
    /* Store the content of the buffer for later use */
    $contentPlaceHolderHead = ob_get_contents();
    /* Clean out the buffer, but do not destroy the output buffer */
    ob_clean();
    ?>
    <!-- Body Content Start -->
    <!-- Header Element Content -->
    <h1 class="mt-3">PHP User Manager</h1>
    <p class="lead">Reset Password</p>
    <hr>
    <?php
    /* Store the content of the buffer for later use */
    $contentPlaceHolderHeader = ob_get_contents();
    /* Clean out the buffer, but do not destroy the output buffer */
    ob_clean();
    ?>
    <!-- Main Element Content -->
    <div class="col-md-6 mx-auto text-center">
        <form class="form-signin" action="" method="post">
            <img src="img/logo.png" alt="" width="100" height="100">
            <h1 class="h3 my-3"><?php echo $_SESSION["SecurityQuestion"] ?></h1>
            <br>
            <label for="answer" class="sr-only">Answer</label>
            <input id="answer" name="answer" type="password" autocomplete="off" class="form-control" placeholder="Answer" required />
            <br>
            <button class="btn btn-lg btn-warning btn-block" type="submit"><i class="fas fa-unlock-alt"></i> Reset Password</button>
            <a href="DestroySession.php" class="btn btn-lg btn-primary btn-block"><i class="fas fa-arrow-left"></i> Return to Login Page</a>
            <?php
            if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == "POST") {
                $answer = filter_input(INPUT_POST, "answer");
                $correct = password_verify($answer, $_SESSION["SecurityAnswerHash"]) ? true : false;
                if ($correct) {
                    /**
                     * PHP SMTP CODE GOES HERE
                     */
                    echo '<script type="text/javascript">';
                    echo ' alert("You are good to go!")';
                    echo '</script>';
                }
                session_destroy();
                header("Location: LoginPage.php?reset=" . ($correct ? "true" : "false"));
                die();
            }
            ?>
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
