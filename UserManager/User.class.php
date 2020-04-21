<?php

/**
 * User Class.
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

namespace UserManager;

class User {

    private $_userID;
    private $_username;
    private $_nickname;
    private $_passwordHash;
    private $_roleID;
    private $_email;
    private $_isLockedOut;
    private $_lastLoginDate;
    private $_creationDate;
    private $_comment;
    private $_isActive;
    private $_securityQuestion;
    private $_securityAnswerHash;

    public function __construct($userID, $username, $nickname, $passwordHash, $roleID, $email, $isLockedOut, $lastLoginDate, $creationDate, $comment, $isActive, $securityQuestion, $securityAnswerHash) {
        $this->setUserID($userID);
        $this->setUsername($username);
        $this->setNickname($nickname);
        $this->setPasswordHash($passwordHash);
        $this->setRoleID($roleID);
        $this->setEmail($email);
        $this->setIsLockedOut($isLockedOut);
        $this->setLastLoginDate($lastLoginDate);
        $this->setCreationDate($creationDate);
        $this->setComment($comment);
        $this->setIsActive($isActive);
        $this->setSecurityQuestion($securityQuestion);
        $this->setSecurityAnswerHash($securityAnswerHash);
    }

    public function getUserID() {
        return $this->_userID;
    }

    public function setUserID($userID) {
        $this->_userID = $userID;
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

    public function getEmail() {
        return $this->_email;
    }

    public function setEmail($email) {
        $this->_email = $email;
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

    public function getCreationDate() {
        return $this->_creationDate;
    }

    public function setCreationDate($creationDate) {
        $this->_creationDate = $creationDate;
    }

    public function getComment() {
        return $this->_comment;
    }

    public function setComment($comment) {
        $this->_comment = $comment;
    }

    public function getIsActive() {
        return $this->_isActive;
    }

    public function setIsActive($isActive) {
        $this->_isActive = ($isActive == 0 ? false : true);
    }

    public function getSecurityQuestion() {
        return $this->_securityQuestion;
    }

    public function setSecurityQuestion($securityQuestion) {
        $this->_securityQuestion = $securityQuestion;
    }

    public function getSecurityAnswerHash() {
        return $this->_securityAnswerHash;
    }

    public function setSecurityAnswerHash($securityAnswerHash) {
        $this->_securityAnswerHash = $securityAnswerHash;
    }

}
