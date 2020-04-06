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
/* Check if a session is already active */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$ROOT_DIR = "PHPUserManager\Public";

/**
 * Computational cost for Key Derivation Functions (KDF)
 */
const BCRYPT_COST = 14;

// Report all PHP errors
error_reporting(-1);
// Log errors in ErrorLog.txt
ini_set('log_errors', 1);
ini_set("error_log", "ErrorLog.txt");

/*
 * FOR DEVELOPMENT ERROR REPORTING
 * Uncomment ini_set('display_errors', 1) and comment set_error_handler for development
 * FOR PRODUCTION ERROR REPORTING
 * Uncomment set_error_handler and comment ini_set('display_errors', 1) for production
 */
ini_set('display_errors', 1);

/*
 * Set UserManagerError(error_level, error_message) to handle all errors and warnings.
 * Use 32767 (equivalent to E_ALL) which will log all errors and warnings, except of level E_STRICT prior to PHP 5.4.0.
 */
// set_error_handler("UserManagerErrorHandler", 32767);
// set_exception_handler("UserManagerExceptionHandler");

/**
 * Error handler to redirect user to error page
 * 
 * @param int $errno Specifies the error report level for the user-defined error
 * @param string $errstr Specifies the error message for the user-defined error
 */
function UserManagerErrorHandler($errno, $errstr, $errfile, $errline) {
    $_SESSION["Error"] = "Type {$errno} Error: {$errstr} in {$errfile} at line {$errline}.";
    header("Location: ErrorPage.php");
    // Do not die. ini_set("error_log", "..." must capture error info in log
}

function UserManagerExceptionHandler($exception) {
    $_SESSION["Error"] = $exception;
    error_log($exception);
    header("Location: ErrorPage.php");
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
    if (empty($id) || $id < 0 || !filter_var($id, FILTER_VALIDATE_INT)) {
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
    if (empty(trim($text)) || (!preg_match("/^[A-Za-z0-9\s\-._~:\/?#\[\]@!$&'()*+,;=]*$/", trim($text)))) {
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
    if (empty($email) || (!filter_var($email, FILTER_VALIDATE_EMAIL))) {
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

function getPasswordHash($password) {
    // Hash the password using Key Derivation Functions (KDF)
    $options = array("cost" => BCRYPT_COST);
    $passwordHash = password_hash($password, PASSWORD_BCRYPT, $options);
    return $passwordHash;
}
