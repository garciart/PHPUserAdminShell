<?php

/**
 * 
 */
require "UserDB.php";

// Get the class name
use UserAdmin\UserDB;

// Get and verify the posted data
$username = filter_input(INPUT_POST, "username");
$password = filter_input(INPUT_POST, "password");
if (!isset($username, $password)) {
    die("Please fill both the username and password field!");
}

// Connect to the database
$userDB = new UserDB();
$pdo = $userDB->connect();
if ($pdo != null) {
    echo "Connected to the SQLite database successfully!<br>";
    $authenticated = $userDB->authenticateUser($username, $password);

    if ($authenticated) {
        $response = "Hello $username, you have been successfully authenticated.";
    } else {
        $response = 'Incorrect credentials or user does not exist.';
    }

    echo $response;
} else {
    die("Whoops, could not connect to the SQLite database!");
}

/*
echo "{$userDB->createUser($username, $password, 1, "New user.")}<br>";
echo "{$userDB->createUser("steve@steve.com", "steve", 1, "Old user.")}<br>";
echo "{$userDB->createUser("mike@mike.com", "mike", 1, "Old user.")}<br>";
echo "{$userDB->getMaxRoleID()}<br>";
echo "{$userDB->getMaxUserID()}<br>";
*/