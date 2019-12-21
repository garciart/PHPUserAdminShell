<?php

/**
 * Authenticates credentials and creates user session.
 *
 * PHP version 5.3
 *
 * @author  Rob Garcia <rgarcia@rgprogramming.com>
 * @license https://opensource.org/licenses/MIT The MIT License
 * @version GIT: $Id$ In development
 * @link    https://github.com/garciart/PHPUserAdminShell GitHub Repository
 */
session_start();

require_once "Common.php";
require_once "User.class.php";
require_once "UserDB.class.php";

// Get the class name
use UserAdmin\UserDB;
use UserAdmin\User;

// Get and filter the posted data
$username = filter_input(INPUT_POST, "username");
$password = filter_input(INPUT_POST, "password");
if (!isset($username, $password)) {
    die("Please fill both the username and password field.");
}

// Connect to the database
$userDB = new UserDB();
$authenticated = $userDB->authenticateUser($username, $password);
if ($authenticated) {
    $result = $userDB->getUserByUsername($username);
    $user = new User($result["UserID"], $result["Username"], $result["Nickname"], $result["PasswordHash"], $result["RoleID"], $result["Email"], $result["IsLockedOut"], $result["LastLoginDate"], $result["CreateDate"], $result["Comment"]);
    // Create session
    session_regenerate_id();
    $_SESSION["IsLockedOut"] = $user->getIsLockedOut();
    if ($user->getIsLockedOut()) {
        header("Location: /PHPUserAdminShell/Login.php");
        exit();
    } else {
        $userDB->updateLoginDate($user->getUserID());
    }
    $_SESSION["Authenticated"] = true;
    $_SESSION["UserID"] = $user->getUserID();
    $_SESSION["Username"] = $user->getUsername();
    $_SESSION["Nickname"] = $user->getNickname();
    $_SESSION["RoleID"] = $user->getRoleID();
    header("Location: UserAdmin.php");
    exit();
} else {
    $_SESSION["Authenticated"] = false;
    header("Location: /PHPUserAdminShell/Login.php");
    exit();
}
