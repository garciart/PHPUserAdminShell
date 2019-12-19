<?php

/**
 * Log out user by destroying session and redirecting to log in page.
 */
session_start();
session_destroy();
header("Location: /PHPUserAdminShell/Login.php");
exit();
