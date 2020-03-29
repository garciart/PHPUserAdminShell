<?php

/**
 * Log out user by destroying session and redirecting to log in page.
 *
 * PHP version 5.3
 *
 * @author  Rob Garcia <rgarcia@rgprogramming.com>
 * @license https://opensource.org/licenses/MIT The MIT License
 * @version 1.0
 * @link    https://github.com/garciart/PHPUserManager GitHub Repository
 */
session_start();
session_destroy();
header("Location: UserManager/LoginPage.php");
exit();
