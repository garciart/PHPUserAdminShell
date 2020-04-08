<?php

/**
 * Code common to one or more files.
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
declare(strict_types = 1);

/* Check if a session is already active */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once "Config.php";

// Key folders. "define" allows constants to be shared with other files
define("ROOT_DIR", $ROOT_DIR);
define("APPLICATION_ROOT_DIR", $APPLICATION_ROOT_DIR);
define("USERMANGER_ROOT_DIR", $USERMANGER_ROOT_DIR);

// Error reporting: DEV or PROD
$ERROR_REPORTING = "PROD";
/**
 * Computational cost for Key Derivation Functions (KDF)
 */
const BCRYPT_COST = 14;

// Report all PHP errors
error_reporting(-1);
// Log errors in ErrorLog.txt
ini_set("log_errors", "1");
ini_set("error_log", "ErrorLog.txt");


if ($ERROR_REPORTING == "PROD") {
    /*
     * Production error reporting
     * Use "32767" instead of "E_ALL" and make sure to set "DISPLAY_ERRORS = On"
     * in php.ini
     */
    set_error_handler("UserManagerErrorHandler", 32767);
    set_exception_handler("UserManagerExceptionHandler");
} else {
    // Development error reporting
    ini_set("DISPLAY_ERRORS", "1");
}

/**
 * Error handler to redirect user to error page
 * 
 * @param integer $errno   The error report level.
 * @param string  $errstr  The error message.
 * @param string  $errfile The filename with the error.
 * @param integer $errline The line number of the error.
 * @return void
 */
function UserManagerErrorHandler($errno, $errstr, $errfile, $errline) {
    $error = "Type {$errno} Error: {$errstr} in {$errfile} at line {$errline}.";
    $_SESSION["Error"] = $error;
    error_log($error);
    header("Location: ErrorPage.php");
    // Do not die. ini_set("error_log", "..." must capture error info in log
}

/**
 * Exception handler. Can be used to redirect users to exception page.
 * @param string $ex Exception class object.
 * @return void
 */
function UserManagerExceptionHandler($ex) {
    $exception = "Type {$ex->getCode()} Exception: {$ex->getMessage()} " .
            "in {$ex->getFile()} at line {$ex->getLine()}.\n";
    $_SESSION["Error"] = $exception;
    error_log($exception);
    header("location: ErrorPage.php");
    // Do not die. ini_set("error_log", "..." must capture error info in log
}

/**
 * Trim, strip slashes, and escape special characters from text
 * @param string $data The text to be cleaned
 * @return string The cleaned text
 */
function cleanText($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

/**
 * Validate database ID
 * @param int $id The database ID
 * @return boolean True if the database ID is valid, false if not
 */
function validateID($id) {
    if (empty($id) || $id < 1 || !filter_var($id, FILTER_VALIDATE_INT)) {
        return false;
    } else {
        return true;
    }
}

/**
 * Validate database input
 * @param string $text The text that will be entered into the database
 * @return boolean True if the text is valid, false if not
 */
function validateText($text) {
    if (empty(trim($text)) || (!preg_match("/^[A-Za-z0-9\s\-._~:\/?#\[\]@!$&'()*+,;=]*$/", $text))
    ) {
        return false;
    } else {
        return true;
    }
}

/**
 * Validate Email
 * @param string $email The email that will be entered into the database
 * @return boolean True if the email is valid, false if not
 */
function validateEmail($email) {
    if (empty(trim($email)) || (!filter_var($email, FILTER_VALIDATE_EMAIL))) {
        return false;
    } else {
        return true;
    }
}

/**
 * Validate URL input
 * @param string $url The URL that will be entered into the database
 * @return boolean True if the URL is valid, false if not
 */
function validateURL($url) {
    if (empty($url) || (!preg_match("/(https?:\/\/)?([\w\-])+\.{1}([a-zA-Z]{2,63})([\/\w-]*)*\/?\??([^#\n\r]*)?#?([^\n\r]*)/", $url))) {
        return false;
    } else {
        return true;
    }
}

/**
 * Validate password input
 * @param string $pword The password that will be encrypted and entered into the database
 * @return boolean True if the password is valid, false if not
 */
function validatePassword($pword) {
    if (empty($pword) || strlen($pword) < 8 || (!preg_match("/^[A-Za-z0-9\s\-._~:\/?#\[\]@!$&'()*+,;=]*$/", trim($text)))) {
        return false;
    } else {
        return true;
    }
}

/**
 * Convert input into hash
 * @param type $password The inputted password
 * @return string The hash of the password
 */
function getPasswordHash($password) {
    // Hash the password using Key Derivation Functions (KDF)
    $options = array("cost" => BCRYPT_COST);
    $passwordHash = password_hash($password, PASSWORD_BCRYPT, $options);
    return $passwordHash;
}

/**
 * Validate date format.
 * @param string $date The date that will be entered into the database.
 * @return boolean True if the date format is valid, false if not.
 */
function validateDate($date) {
    if (empty(trim($date)) || (!preg_match(
                    "/^([0-9]){4}-([0-9]){2}-([0-9]){2} ([0-9]){2}:([0-9]){2}:([0-9]){2}$/", $date
            ) ) || strlen($date) != 19
    ) {
        return false;
    } else {
        return true;
    }
}
