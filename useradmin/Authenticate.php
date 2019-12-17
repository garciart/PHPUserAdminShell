<?php

require 'UserDB.php';
// require 'vendor/autoload.php';
/*
  $username = "Rob";
  $password = "Password";

  $db = new DataAccess();
  $db->createUser($username, $password);

  echo "User '$username' has been created successfully.";
 */

use UserAdmin\UserDB;

$userDB = new UserDB();
$pdo = $userDB->connect();
if ($pdo != null) {
    echo 'Connected to the SQLite database successfully!';
} else {
    echo 'Whoops, could not connect to the SQLite database!';
}
// echo $userDB->createUser("mike@mike.com", "mike");