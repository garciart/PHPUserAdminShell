<?php

/**
 * User class.
 *
 * PHP version used: 5.6
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

namespace Models;

// Include this file to access common functions and variables
require_once "CommonFunctions.php";

/**
 * User class.
 *
 * @category  PHPUserManager
 * @package   UserManager
 * @author    Rob Garcia <rgarcia@rgprogramming.com>
 * @copyright 2019-2020 Rob Garcia
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @link      https://github.com/garciart/PHPUserManager
 */
final class User {

    /**
     *  Class properties.
     */
    private $_userID;
    private $_firstName;
    private $_lastName;
    private $_username;
    private $_nickname;
    private $_passwordHash;
    private $_roleID;
    private $_email;
    private $_isLockedOut;
    private $_lastLoginDate;
    private $_creationDate;
    private $_comment;

    /**
     * User ID getter.
     *
     * @return integer The user ID property.
     */
    public function getUserID() {
        return $this->_userID;
    }

    /**
     * User ID setter.
     *
     * @param integer $userID The user's ID.
     *
     * @return void
     */
    public function setUserID($userID) {
        if (validateUserID($userID)) {
            $this->_userID = $userID;
        } else {
            throw new \InvalidArgumentException(
            "User ID cannot be empty, 0, NULL, or FALSE."
            );
        }
    }

    /**
     * First name getter.
     *
     * @return string The first name property.
     */
    public function getFirstName() {
        return $this->_firstName;
    }

    /**
     * First name setter.
     *
     * @param string $firstName The user's first name.
     *
     * @return void
     */
    public function setFirstName($firstName) {
        $firstName = trim($firstName);
        if (validateText($firstName)) {
            $this->_firstName = $firstName;
        } else {
            throw new \InvalidArgumentException(
            "First name cannot be empty or contain illegal characters."
            );
        }
    }

    /**
     * Last name getter.
     *
     * @return string The last name property.
     */
    public function getLastName() {
        return $this->_lastName;
    }

    /**
     * Last name setter.
     *
     * @param string $lastName The user's last name.
     *
     * @return void
     */
    public function setLastName($lastName) {
        $lastName = trim($lastName);
        if (validateText($lastName)) {
            $this->_lastName = $lastName;
        } else {
            throw new \InvalidArgumentException(
            "Last name cannot be empty or contain illegal characters."
            );
        }
    }

    function getUsername() {
        return $this->_username;
    }

    public function setUsername($username) {
        $this->_username = $username;
    }

    function getNickname() {
        return $this->_nickname;
    }

    public function setNickname($nickname) {
        $this->_nickname = $nickname;
    }

    public function getPasswordHash() {
        return $this->_passwordHash;
    }

    public function setPasswordHash($passwordHash) {
        $this->_passwordHash = $passwordHash;
    }

    public function getRoleID() {
        return $this->_roleID;
    }

    public function setRoleID($roleID) {
        $this->_roleID = $roleID;
    }

    /**
     * Email getter.
     *
     * @return string The email property.
     */
    public function getEmail() {
        return $this->_email;
    }

    /**
     * Email setter.
     *
     * @param string $email The user's email address (can be used as a user name).
     *
     * @return void
     */
    public function setEmail($email) {
        $email = trim($email);
        if (validateEmail($email)) {
            $this->_email = $email;
        } else {
            throw new \InvalidArgumentException(
            "Email cannot be empty, incorrectly formatted, or contain " .
            "illegal characters."
            );
        }
    }

    public function getIsLockedOut() {
        return $this->_isLockedOut;
    }

    public function setIsLockedOut($isLockedOut) {
        $this->_isLockedOut = ($isLockedOut == 0 ? false : true);
    }

    public function getLastLoginDate() {
        return $this->_lastLoginDate;
    }

    public function setLastLoginDate($lastLoginDate) {
        $this->_lastLoginDate = $lastLoginDate;
    }

    /**
     * Creation date getter.
     *
     * @return string The creation date property.
     */
    public function getCreationDate() {
        return $this->_creationDate;
    }

    /**
     * Creation date setter.
     *
     * @param string $creationDate The date the user was added to the database.
     *
     * @return void
     */
    public function setCreationDate($creationDate) {
        if (validateDate($creationDate)) {
            $this->_creationDate = $creationDate;
        } else {
            throw new \InvalidArgumentException(
            "Creation date cannot be empty or incorrectly formatted."
            );
        }
    }

    /**
     * Comment getter.
     *
     * @return string The comment property.
     */
    public function getComment() {
        return $this->_comment;
    }

    /**
     * Comment setter.
     *
     * @param string $comment Any additional comments.
     *
     * @return void
     */
    public function setComment($comment) {
        $this->_comment = $comment;
    }

    /**
     * Class constructor.
     *
     * @param integer $userID        The user's ID.
     * @param string  $firstName     The user's first name.
     * @param string  $lastName      The user's last name.
     * @param string  $userName      The username.
     * @param string  $nickName      The user's nickname or display name
     * @param string  $passwordHash  The user's password in hashed form.
     * @param integer $roleID        The user's last name.
     * @param string  $email         The user's email address.
     * @param boolean $isLockedOut   If the user is locked out of the system.
     * @param string  $lastLoginDate The last time and date the user was authenticated.
     * @param string  $creationDate  The date the user was added to the database.
     * @param string  $comment       Any additional comments.
     *
     * @return void
     */
    public function __construct($userID, $firstName, $lastName, $username, $nickname, $passwordHash, $roleID, $email, $isLockedOut, $lastLoginDate, $creationDate, $comment) {
        $this->setUserID($userID);
        $this->setFirstName($firstName);
        $this->setLastName($lastName);
        $this->setUsername($username);
        $this->setNickname($nickname);
        $this->setPasswordHash($passwordHash);
        $this->setRoleID($roleID);
        $this->setEmail($email);
        $this->setIsLockedOut($isLockedOut);
        $this->setLastLoginDate($lastLoginDate);
        $this->setCreationDate($creationDate);
        $this->setComment($comment);
    }

}
