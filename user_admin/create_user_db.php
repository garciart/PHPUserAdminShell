<?php
require_once 'data_access.php';

$username = "Rob";
$password = "Password";

$db = new DataAccess();
$db->createUser($username, $password);

echo "User '$username' has been created successfully.";
