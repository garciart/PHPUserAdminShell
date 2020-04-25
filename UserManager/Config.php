<?php

/**
 * Constants and variables common to one or more files.
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

// Project folders
$ROOT_DIR = "PHPUsermanager";
$APPLICATION_ROOT_DIR = $ROOT_DIR . DIRECTORY_SEPARATOR . "Public";
$APPLICATION_NAME = "PHP User Manager";
$CANONICAL_URL = "http://localhost:8080/PHPUsermanager";
$CONTACT_EMAIL = "rgarcia@rgprogramming.com";

// User Manager folders
// $USERMANGER_ROOT_DIR = $ROOT_DIR . DIRECTORY_SEPARATOR . "UserManager";
$USERMANGER_ROOT_DIR = dirname(__FILE__);

// Error reporting: DEV or PROD
$ERROR_REPORTING = "PROD";
