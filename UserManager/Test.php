<?php
/**
 * Test page.
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

// Ensure the user is authenticated and authorized
if (!isset($_SESSION["Authenticated"]) || $_SESSION["Authenticated"] == false || $_SESSION["Authenticated"] == 0) {
    header("Location: LoginPage.php");
    exit();
} else {
    echo "User is " . ($_SESSION["Authenticated"] == true ? "authenticated." : "not authenticated.") . "<br>";
    echo "User ID is " . $_SESSION["UserID"] . "<br>";
    echo "Username is " . $_SESSION["Username"] . "<br>";
    echo "User nickname is " . $_SESSION["Nickname"] . "<br>";
    echo "Role ID is " . $_SESSION["RoleID"] . "<br>";
    echo "Role level is " . $_SESSION["Level"] . "<br>";
}