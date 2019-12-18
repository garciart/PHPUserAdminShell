<?php

/**
 * User Class
 */

namespace UserAdmin;

class User {

    private $userID;
    private $userName;
    private $passwordHash;
    private $roleID;
    private $email;
    private $isLockedOut;
    private $lastLoginDate;
    private $createDate;
    private $comment;

    public function __construct($userID, $userName, $passwordHash, $roleID, $email, $isLockedOut, $lastLoginDate, $createDate, $comment) {
        $this->setUserID($userID);
        $this->setUserName($userName);
        $this->setPasswordHash($passwordHash);
        $this->setRoleID($roleID);
        $this->setEmail($email);
        $this->setIsLockedOut($isLockedOut);
        $this->setLastLoginDate($lastLoginDate);
        $this->setCreateDate($createDate);
        $this->setComment($comment);
    }

    public function getUserID() {
        return $this->userID;
    }

    public function setUserID($userID) {
        $this->userID = $userID;
    }

    function getUserName() {
        return $this->userName;
    }

    public function setUserName($userName) {
        $this->userName = $userName;
    }

    public function getPasswordHash() {
        return $this->passwordHash;
    }

    public function setPasswordHash($passwordHash) {
        $this->passwordHash = $passwordHash;
    }

    public function getRoleID() {
        return $this->roleID;
    }

    public function setRoleID($roleID) {
        $this->roleID = $roleID;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getIsLockedOut() {
        return $this->isLockedOut;
    }

    public function setIsLockedOut($isLockedOut) {
        $this->isLockedOut = $isLockedOut;
    }

    public function getLastLoginDate() {
        return $this->lastLoginDate;
    }

    public function setLastLoginDate($lastLoginDate) {
        $this->lastLoginDate = $lastLoginDate;
    }

    public function getCreateDate() {
        return $this->createDate;
    }

    public function setCreateDate($createDate) {
        $this->createDate = $createDate;
    }

    public function getComment() {
        return $this->comment;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

}
