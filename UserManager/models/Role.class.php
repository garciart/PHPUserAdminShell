<?php

/**
 * Role class.
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
 * Role class.
 *
 * @category  PHPUserManager
 * @package   UserManager
 * @author    Rob Garcia <rgarcia@rgprogramming.com>
 * @copyright 2019-2020 Rob Garcia
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @link      https://github.com/garciart/PHPUserManager
 */
final class Role {

    /**
     *  Class properties.
     */
    private $_roleID;
    private $_title;
    private $_comment;

    /**
     * 
     * @return type
     */
    public function getRoleID() {
        return $this->_roleID;
    }

    /**
     * 
     * @param type $roleID
     */
    public function setRoleID($roleID) {
        $this->_roleID = $roleID;
    }

    /**
     * 
     * @return type
     */
    function getTitle() {
        return $this->_title;
    }

    /**
     * 
     * @param type $title
     */
    public function setTitle($title) {
        $this->_title = $title;
    }

    /**
     * 
     * @return type
     */
    public function getComment() {
        return $this->_comment;
    }

    /**
     * 
     * @param type $comment
     */
    public function setComment($comment) {
        $this->_comment = $comment;
    }

    /**
     * 
     * @param type $roleID
     * @param type $title
     * @param type $comment
     */
    public function __construct($roleID, $title, $comment) {
        $this->setRoleID($roleID);
        $this->setTitle($title);
        $this->setComment($comment);
    }

}
