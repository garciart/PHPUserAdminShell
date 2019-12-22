<?php

/**
 * Record all errors, notices, and warnings in error_log.txt
 *
 * PHP version 5.3
 *
 * @author  Rob Garcia <rgarcia@rgprogramming.com>
 * @license https://opensource.org/licenses/MIT The MIT License
 * @version GIT: $Id$ In development
 * @link    https://github.com/garciart/PHPUserAdminShell GitHub Repository
 */
session_start();

$ROOT_URL = "PHPUserAdminShell";

error_reporting(-1);
ini_set('log_errors', 1);
ini_set("error_log", "ErrorLog.txt");

/*
 * FOR DEVELOPMENT ERROR REPORTING
 * Uncomment ini_set('display_errors', 1) and comment out set_error_handler for development
 * FOR PRODUCTION ERROR REPORTING
 * Uncomment set_error_handler and comment out ini_set('display_errors', 1) for production
 */
// ini_set('display_errors', 1);

/*
 * Set userAdminError(error_level, error_message) to handle all errors and warnings.
 * Use 32767 (equivalent to E_ALL) which will log all errors and warnings, except of level E_STRICT prior to PHP 5.4.0.
 */
set_error_handler("userAdminErrorHandler", 32767);
set_exception_handler("userAdminExceptionHandler");

/**
 * Error handler to redirect user to error page
 * 
 * @param int $errno Specifies the error report level for the user-defined error
 * @param string $errstr Specifies the error message for the user-defined error
 */
function userAdminErrorHandler($errno, $errstr, $errfile, $errline) {
    $_SESSION["ERROR"] = "Yo mamma!";
    header("Location: Error.php");
    // Do not die. ini_set("error_log", "..." must capture error info in log
}

function userAdminExceptionHandler($exception) {
    $_SESSION["ERROR"] = "Yo mamma too!";
    header("Location: Error.php");
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
