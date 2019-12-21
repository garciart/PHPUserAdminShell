<?php

/**
 * User Class.
 *
 * PHP version 5.3
 *
 * @author  Rob Garcia <rgarcia@rgprogramming.com>
 * @license https://opensource.org/licenses/MIT The MIT License
 * @version GIT: $Id$ In development
 * @link    https://github.com/garciart/PHPUserAdminShell GitHub Repository
 */

namespace UserAdmin;

class User {

    private $_userID;
    private $_username;
    private $_nickname;
    private $_passwordHash;
    private $_roleID;
    private $_email;
    private $_isLockedOut;
    private $_lastLoginDate;
    private $_createDate;
    private $_comment;

    public function __construct($userID, $username, $nickname, $passwordHash, $roleID, $email, $isLockedOut, $lastLoginDate, $createDate, $comment) {
        $this->setUserID($userID);
        $this->setUsername($username);
        $this->setNickname($nickname);
        $this->setPasswordHash($passwordHash);
        $this->setRoleID($roleID);
        $this->setEmail($email);
        $this->setIsLockedOut($isLockedOut);
        $this->setLastLoginDate($lastLoginDate);
        $this->setCreateDate($createDate);
        $this->setComment($comment);
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

    public function getCreateDate() {
        return $this->_createDate;
    }

    public function setCreateDate($createDate) {
        $this->_createDate = $createDate;
    }

    public function getComment() {
        return $this->_comment;
    }

    public function setComment($comment) {
        $this->_comment = $comment;
    }

}
