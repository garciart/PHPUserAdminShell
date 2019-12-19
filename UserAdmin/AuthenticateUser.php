<?php

/**
 * Authenticates credentials and creates user session.
 */
session_start();

require_once "UserDB.php";
require_once "User.php";

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
$pdo = $userDB->connect();
if ($pdo != null) {
    $authenticated = $userDB->authenticateUser($username, $password);
    if ($authenticated) {
        $result = $userDB->getUserDetails($username);
        if ($result["IsLockedOut"]) {
            $response = "We are sorry, but your account is locked. Please contact your administrator.";
        } else {
            $user = new User($result["UserID"], $result["UserName"], $result["PasswordHash"], $result["RoleID"], $result["Email"], $result["IsLockedOut"], $result["LastLoginDate"], $result["CreateDate"], $result["Comment"]);
            session_regenerate_id();
            $_SESSION["Authenticated"] = TRUE;
            $_SESSION["UserName"] = $user->getUserName();
            $_SESSION["RoleID"] = $user->getRoleID();
            $response = "Hello " . $_SESSION["UserName"] . ", you have been successfully authenticated.";
            $userDB->updateLoginDate($user->getUserID());
            header("Location: UserAdmin.php");
            exit();
        }
    } else {
        $_SESSION["Authenticated"] = FALSE;
        $response = "Incorrect credentials or user does not exist.";
        header("Location: /PHPUserAdminShell/Login.php");
        exit();
    }

    echo $response. "<br>";
} else {
    die("Could not connect to the database.<br>");
}

/*
echo "{$userDB->createUser($username, $password, 1, "New user.")}<br>";
echo "{$userDB->createUser("steve@steve.com", "steve", 1, "Old user.")}<br>";
echo "{$userDB->createUser("mike@mike.com", "mike", 1, "Old user.")}<br>";
echo "{$userDB->getMaxRoleID()}<br>";
echo "{$userDB->getMaxUserID()}<br>";
*/