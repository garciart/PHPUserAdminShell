<?php

/**
 * Authenticates email address for password reset.
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
require_once "UserDB.class.php";

// Get the class name
use UserManager\UserDB;

// Get and filter the posted data
$email = filter_input(INPUT_POST, "email");
if (!isset($email)) {
    header("Location: LoginPage.php");
    exit();
} else {
    // Connect to the database
    $userDB = new UserDB();
    $exists = $userDB->userExists($email);
    if ($exists) {
        $result = $userDB->getUserResetInformation($email);
        // Create session
        session_regenerate_id();
        $_SESSION["IsLockedOut"] = $result["IsLockedOut"];
        if ($result["IsLockedOut"]) {
            header("Location: LoginPage.php");
            exit();
        }
        $_SESSION["Exists"] = true;
        $_SESSION["Nickname"] = $result["Nickname"];
        $_SESSION["Email"] = $email;
        $_SESSION["IsActive"] = $result["IsActive"];
        $_SESSION["SecurityQuestion"] = $result["SecurityQuestion"];
        $_SESSION["SecurityAnswerHash"] = $result["SecurityAnswerHash"];
        header("Location: ResetPassword.php");
        exit();
    } else {
        $_SESSION["Exists"] = false;
        header("Location: LoginPage.php");
        exit();
    }
}
