<?php

/**
 * Authenticates credentials and creates user session.
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
require_once "Role.class.php";
require_once "UserDB.class.php";
require_once "User.class.php";

// Get the class name
use UserManager\Role;
use UserManager\UserDB;
use UserManager\User;

// Get and filter the posted data
$username = filter_input(INPUT_POST, "username");
$password = filter_input(INPUT_POST, "password");
if (!isset($username, $password)) {
    header("Location: LoginPage.php");
    exit();
} else {
    // Connect to the database
    $userDB = new UserDB();
    $authenticated = $userDB->authenticateUser($username, $password);
    if ($authenticated) {
        $userResult = $userDB->getUserByUsername($username);
        $user = new User($userResult["UserID"], $userResult["Username"], $userResult["Nickname"], $userResult["PasswordHash"], $userResult["RoleID"], $userResult["Email"], $userResult["IsLockedOut"], $userResult["LastLoginDate"], $userResult["CreationDate"], $userResult["Comment"], $userResult["IsActive"], $userResult["SecurityQuestion"], $userResult["SecurityAnswerHash"]);
        $roleResult = $userDB->getRole($userResult["RoleID"]);
        $currentRole = new Role($roleResult["RoleID"], $roleResult["Level"], $roleResult["Title"], $roleResult["Comment"]);
        // Create session
        session_regenerate_id();
        $_SESSION["IsLockedOut"] = $user->getIsLockedOut();
        if ($user->getIsLockedOut()) {
            header("Location: LoginPage.php");
            exit();
        } else {
            $userDB->updateLoginDate($user->getUserID());
        }
        $_SESSION["Authenticated"] = true;
        $_SESSION["UserID"] = $user->getUserID();
        $_SESSION["Username"] = $user->getUsername();
        $_SESSION["Nickname"] = $user->getNickname();
        $_SESSION["RoleID"] = $user->getRoleID();
        $_SESSION["Level"] = $currentRole->getLevel();
        header("Location: MainPage.php");
        exit();
    } else {
        $_SESSION["Authenticated"] = false;
        header("Location: LoginPage.php");
        exit();
    }
}
