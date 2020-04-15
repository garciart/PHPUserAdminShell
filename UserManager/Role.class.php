<?php

/**
 * Role Class.
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

namespace UserManager;

class Role {

    private $_roleID;
    private $_level;
    private $_title;
    private $_comment;

    public function __construct($roleID, $level, $title, $comment) {
        $this->setRoleID($roleID);
        $this->setLevel($level);
        $this->setTitle($title);
        $this->setComment($comment);
    }

    public function getRoleID() {
        return $this->_roleID;
    }

    public function setRoleID($roleID) {
        $this->_roleID = $roleID;
    }

    public function getLevel() {
        return $this->_level;
    }

    public function setLevel($level) {
        $this->_level = $level;
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
