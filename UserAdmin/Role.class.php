<?php

/**
 * Role Class.
 *
 * PHP version 5.3
 *
 * @author  Rob Garcia <rgarcia@rgprogramming.com>
 * @license https://opensource.org/licenses/MIT The MIT License
 * @version GIT: $Id$ In development
 * @link    https://github.com/garciart/PHPUserAdminShell GitHub Repository
 */

namespace UserAdmin;

class Role {

    private $_roleID;
    private $_title;
    private $_comment;

    public function __construct($roleID, $title, $comment) {
        $this->setRoleID($roleID);
        $this->setTitle($title);
        $this->setComment($comment);
    }

    public function getRoleID() {
        return $this->_roleID;
    }

    public function setRoleID($roleID) {
        $this->_roleID = $roleID;
    }

    function getTitle() {
        return $this->_title;
    }

    public function setTitle($title) {
        $this->_title = $title;
    }

    public function getComment() {
        return $this->_comment;
    }

    public function setComment($comment) {
        $this->_comment = $comment;
    }

}
