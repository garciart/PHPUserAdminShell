<?php

require "Common.php";
require "UserDB.php";

use UserAdmin\UserDB;

// Connect to the database
$username = filter_input(INPUT_POST, "username");
$password = filter_input(INPUT_POST, "password");

if (!isset($username, $password)) {
    // Could not get the data that should have been sent.
    die("Please fill both the username and password field!");
}

$userDB = new UserDB();

$pdo = $userDB->connect();
if ($pdo != null) {
    echo "Connected to the SQLite database successfully!<br>";
} else {
    // If there is an error with the connection, stop the script and display the error.
    die("Whoops, could not connect to the SQLite database!");
}
/*
echo "{$userDB->getNextUserID()}<br>";
echo "{$userDB->createUser(10, $username, $password, 1, "New user.")}<br>";
echo "{$userDB->createUser(11, "steve@steve.com", "steve", 1, "Old user.")}<br>";
echo "{$userDB->createUser(12, "mike@mike.com", "mike", 1, "Old user.")}<br>";
echo "{$userDB->getNextUserID()}<br>";
*/
