<?php

/**
 * Database class. Handles all database calls.
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

// Include this file to access common functions and variables
require_once "CommonCode.php";

class UserDB {

    /**
     * Path to database
     */
    const PATH_TO_SQLITE_DB = "db/users.db";

    //const PATH_TO_SQLITE_DB = USERMANGER_ROOT_DIR . DIRECTORY_SEPARATOR. "db" . DIRECTORY_SEPARATOR . "users.db";

    /**
     * Computational cost for Key Derivation Functions (KDF)
     */
    // const BCRYPT_COST = 14;

    /**
     * PDO instance
     * @var type
     */
    private $_pdo;

    /**
     * Main methods:
     * _construct
     * connect()
     * createRoleTable()
     * createUserTable()
     * createRole($title, $comment)
     * createUser($nickname, $username, $password, $roleID, $comment)
     * getAllRoles()
     * getRole($roleID)
     * getAllUsers()
     * getUserByUserID($userID)
     * getUserByUsername($username)
     * updateRole($title, $comment)
     * updateUser($nickname, $username, $passwordHash, $roleID, $comment)
     * deleteRole($roleID)
     * deleteUser($userID)
     */

    /**
     * Constructor. If the database is not found, it creates it.
     */
    public function __construct() {
        if (!file_exists(self::PATH_TO_SQLITE_DB)) {
            // Create tables if they do not exist
            $this->createRoleTable();
            $this->createUserTable();
        }
    }

    /**
     * Connects to the database.
     * @return \PDO The PDO object that connects to the SQLite database
     */
    public function connect() {
        // Check if connection does not exists
        if (!isset($this->_pdo)) {
            try {
                // Create (connect to) SQLite database in file
                $this->_pdo = new \PDO("sqlite:" . self::PATH_TO_SQLITE_DB);
                // Turn on foreign key constraints
                $this->_pdo->exec("PRAGMA foreign_keys = ON;");
                // Set errormode to exceptions
                $this->_pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                error_log($e->getMessage());
            }
        }
        return $this->_pdo;
    }

    /**
     * Inserts a user role into the database.
     * @param type $title The name of the role.
     * @param type $comment Any additional comments.
     * @return type The rowid of the new role.
     */
    public function createRole($title, $comment) {
        try {
            $this->_pdo = $this->connect();
            $sql = "INSERT INTO Role
                VALUES (:RoleID, :Title, :Comment);";
            $stmt = $this->_pdo->prepare($sql);
            $stmt->bindValue(":RoleID", $this->getNextRoleID());
            $stmt->bindValue(":Title", $title);
            $stmt->bindValue(":Comment", $comment);
            $stmt->execute();
            $lastInsertId = $this->_pdo->lastInsertId();
            // unset($this->_pdo);
            return $lastInsertId;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * Inserts a new user into the database.
     * @param type $username The username to create.
     * @param type $password The password of the user.
     * @param type $comment Any additional comments.
     * @return type The rowid of the new user.
     */
    public function createUser($username, $nickname, $password, $roleID, $comment) {
        try {
            $this->_pdo = $this->connect();
            $sql = "INSERT INTO User
                VALUES (:UserID, :Username, :Nickname, :PasswordHash, :RoleID, :Email, :IsLockedOut, :LastLoginDate, :CreationDate, :Comment);";
            // Hash the password using Key Derivation Functions (KDF)
            $options = array("cost" => BCRYPT_COST);
            $passwordHash = password_hash($password, PASSWORD_BCRYPT, $options);
            // Email and username are initially the same
            $email = $username;
            // Set other initial values
            $isLockedOut = 0;
            $lastLoginDate = date("Y-m-d H:i:s");
            $creationDate = date("Y-m-d H:i:s");
            // Execute SQL
            $stmt = $this->_pdo->prepare($sql);
            $stmt->bindValue(":UserID", $this->getNextUserID());
            $stmt->bindValue(":Username", $username);
            $stmt->bindValue(":Nickname", $nickname);
            $stmt->bindValue(":PasswordHash", $passwordHash);
            $stmt->bindValue(":RoleID", $roleID);
            $stmt->bindValue(":Email", $email);
            $stmt->bindValue(":IsLockedOut", $isLockedOut);
            $stmt->bindValue(":LastLoginDate", $lastLoginDate);
            $stmt->bindValue(":CreationDate", $creationDate);
            $stmt->bindValue(":Comment", $comment);
            $stmt->execute();
            $lastInsertId = $this->_pdo->lastInsertId();
            // unset($this->_pdo);
            return $lastInsertId;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    public function getAllRoles() {
        $result = null;
        try {
            $this->_pdo = $this->connect();
            $sql = "SELECT *
                FROM Role
                ORDER BY RoleID ASC;";
            $stmt = $this->_pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result->fetchAll();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    public function getRole($roleID) {
        try {
            $this->_pdo = $this->connect();
            $sql = "SELECT *
                FROM Role
                WHERE RoleID = :RoleID;";
            $stmt = $this->_pdo->prepare($sql);
            $stmt->bindValue(":RoleID", $roleID);
            $stmt->execute();
            // Fetch the result set
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            // unset($this->_pdo);
            return $result;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * Gets all the users in the database and their information.
     * @return array An array of all the users in the database and their information. An empty array indicates an error.
     */
    public function getAllUsers() {
        try {
            $this->_pdo = $this->connect();
            $sql = "SELECT *
                FROM User
                ORDER BY Username ASC;";
            $result = $this->_pdo->query($sql);
            // unset($this->_pdo);
            return $result->fetchAll();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * Returns a single user and his or her information.
     * @param integer $userID The user's ID.
     * @return array The user's information indexed by column name or empty if the user's ID is not found.
     */
    public function getUserByUserID($userID) {
        try {
            $this->_pdo = $this->connect();
            $sql = "SELECT *
                FROM User
                WHERE UserID = :UserID;";
            $stmt = $this->_pdo->prepare($sql);
            $stmt->bindValue(":UserID", $userID);
            $stmt->execute();
            // Fetch the result set
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            // unset($this->_pdo);
            return $result;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * Returns a single user and his or her information.
     * @param string $username The user's email.
     * @return array The user's information indexed by column name or empty if the user's username is not found.
     */
    public function getUserByUsername($username) {
        try {
            $this->_pdo = $this->connect();
            $sql = "SELECT *
                FROM User
                WHERE Username = :Username;";
            $stmt = $this->_pdo->prepare($sql);
            $stmt->bindValue(":Username", $username);
            $stmt->execute();
            // Fetch the result set
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            /*
             * Look into $result = $stmt->fetch(\PDO::FETCH_OBJ);?
             */
            // unset($this->_pdo);
            return $result;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    public function updateRole($roleID, $title, $comment) {
        try {
            $this->_pdo = $this->connect();
            $sql = "UPDATE Role
                SET Title = :Title,
                Comment = :Comment
                WHERE RoleID = :RoleID;";
            $lastLoginDate = date("Y-m-d H:i:s");
            $stmt = $this->_pdo->prepare($sql);
            // Get the highest value of UserID + 1
            $stmt->bindValue(":UserID", $userID);
            $stmt->bindValue(":LastLoginDate", $lastLoginDate);
            $stmt->execute();
            $rowsAffected = $stmt->rowCount();
            // unset($this->_pdo);
            return $rowsAffected;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    public function updateUser($userID, $username, $nickname, $passwordHash, $roleID, $email, $isLockedOut, $comment) {
        try {
            $this->_pdo = $this->connect();
            $sql = "UPDATE User
                SET Username = :Username,
                Nickname = :Nickname,
                PasswordHash = :PasswordHash,
                RoleID = :RoleID,
                Email = :Email,
                IsLockedOut = :IsLockedOut,
                Comment = :Comment
                WHERE  UserID = :UserID;";
            $stmt = $this->_pdo->prepare($sql);
            $stmt->bindValue(":UserID", $userID);
            $stmt->bindValue(":Username", $username);
            $stmt->bindValue(":Nickname", $nickname);
            $stmt->bindValue(":PasswordHash", $passwordHash);
            $stmt->bindValue(":RoleID", $roleID);
            $stmt->bindValue(":Email", $email);
            $stmt->bindValue(":IsLockedOut", $isLockedOut);
            $stmt->bindValue(":Comment", $comment);
            $stmt->execute();
            $rowsAffected = $stmt->rowCount();
            // unset($this->_pdo);
            return $rowsAffected;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    public function deleteRole($roleID) {
        try {
            $this->_pdo = $this->connect();
            $sql = "DELETE FROM Role
                WHERE RoleID = :RoleID;";
            $stmt = $this->_pdo->prepare($sql);
            $stmt->bindValue(":UserID", $roleID);
            $stmt->execute();
            $rowsAffected = $stmt->rowCount();
            // unset($this->_pdo);
            return $rowsAffected;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * Deletes a user from the database.
     * @param integer $userID The user's ID.
     * @return integer The number of rows affected. A value other than 1 indicates an error.
     */
    public function deleteUser($userID) {
        try {
            $this->_pdo = $this->connect();
            $sql = "DELETE FROM User
                WHERE UserID = :UserID;";
            $stmt = $this->_pdo->prepare($sql);
            $stmt->bindValue(":UserID", $userID);
            $stmt->execute();
            $rowsAffected = $stmt->rowCount();
            // unset($this->_pdo);
            return $rowsAffected;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * Utility calls
     */

    /**
     * Creates the database if it does not exist.
     */
    private function createRoleTable() {
        // Make sure you create the Role table first
        try {
            $this->_pdo = $this->connect();
            $sql = "CREATE TABLE IF NOT EXISTS Role (
                RoleID integer PRIMARY KEY,
                Title text UNIQUE NOT NULL,
                Comment text
            );";
            $this->_pdo->exec($sql);
            // Set initial values
            $this->createRole("User", "Anonymous and unauthenticated user. Can only browse non-secured pages.");
            $this->createRole("Superuser", "Authenticated user. Can browse all pages, but cannot edit information.");
            $lastInsertId = $this->createRole("Administrator", "Authenticated user. Can browse all pages and edit information.");
            // unset($this->_pdo);
            return $lastInsertId;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * Creates the database if it does not exist.
     */
    private function createUserTable() {
        // Create User table after Role table due to the foreign key constraint
        try {
            $this->_pdo = $this->connect();
            $sql = "CREATE TABLE IF NOT EXISTS User (
                UserID integer PRIMARY KEY,
                Username text UNIQUE NOT NULL,
                Nickname text NOT NULL,
                PasswordHash text NOT NULL,
                RoleID integer NOT NULL,
                Email text NOT NULL,
                IsLockedOut integer NOT NULL DEFAULT '0' CHECK (IsLockedOut >= 0 OR IsLockedOut <= 1),
                LastLoginDate text NOT NULL,
                CreationDate text NOT NULL,
                Comment text,
                FOREIGN KEY(RoleID) REFERENCES Role(RoleID)
            );";
            $this->_pdo->exec($sql);
            // Set initial values
            $this->createUser("rob@rgprogramming.com", "Rob", "123456789", 1, "New user.");
            $this->createUser("steve@rgprogramming.com", "Steve", "abcdefghi", 2, "Old user.");
            $lastInsertId = $this->createUser("admin@rgprogramming.com", "Admin", "8675309", 3, "For test purposes only.");
            // unset($this->_pdo);
            return $lastInsertId;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * Gets the highest value of RoleID (usually the last row inserted) from the Role table.
     * @return integer The anticipated value of the next RoleID or 0 if there is no data.
     */
    private function getNextRoleID() {
        try {
            $this->_pdo = $this->connect();
            $sql = "SELECT MAX(RoleID) as maxRoleID FROM Role;";
            $stmt = $this->_pdo->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch();
            $maxRoleID = $row["maxRoleID"] == "" ? 0 : $row["maxRoleID"];
            // unset($this->_pdo);
            return $maxRoleID + 1;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * Gets the highest value of UserID (usually the last row inserted) from the User table.
     * @return integer The anticipated value of the next UserID or 0 if there is no data.
     */
    private function getNextUserID() {
        try {
            $this->_pdo = $this->connect();
            $sql = "SELECT MAX(UserID) as maxUserID FROM User;";
            $result = $this->_pdo->query($sql);
            $row = $result->fetch();
            $maxUserID = $row["maxUserID"] == "" ? 0 : $row["maxUserID"];
            // unset($this->_pdo);
            return $maxUserID + 1;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * Authenticates the given user with the given password by first checking
     * if the user exists, and then using password_verify's built-in function
     * to check that the supplied password matches the derived one.
     *
     * @param $username The username to authenticate.
     * @param $password The password to authenticate the user.
     * @return True if the password matches for the username, false if not.
     */
    public function AuthenticateUser($username, $password) {
        $authenticated = false;
        if ($this->userExists($username)) {
            $storedPassword = $this->getUserPassword($username);
            $authenticated = password_verify($password, $storedPassword) ? true : false;
        }
        return $authenticated;
    }

    /**
     * Checks if the given users exists in the database.
     * Julen Pardo came up with this. 
     * Thought about changing the method to retrieve the UserID instead,
     * but Email is supposed to be unique.
     * If the count != 1, that means there are no users or more than one,
     * which means something is wrong. This is a better method.
     * @param $username The username to check if exists.
     * @return True if the users exists, false if not.
     */
    private function userExists($username) {
        try {
            $this->_pdo = $this->connect();
            $sql = "SELECT COUNT(*) AS Count
                FROM User
                WHERE Username = :Username;";
            $stmt = $this->_pdo->prepare($sql);
            $stmt->bindValue(":Username", $username);
            $stmt->execute();
            // Fetch the result set
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            // unset($this->_pdo);
            $exists = ($result["Count"]) == 1 ? true : false;
            return $exists;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * Gets given users password.
     *
     * @param $username The username to get the password of.
     * @return The password of the given user.
     */
    private function getUserPassword($username) {
        try {
            $this->_pdo = $this->connect();
            $sql = "SELECT PasswordHash
                FROM User
                WHERE Username = :Username;";
            $stmt = $this->_pdo->prepare($sql);
            $stmt->bindValue(":Username", $username);
            $stmt->execute();
            // Fetch the result set
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            // unset($this->_pdo);
            $passwordHash = $result["PasswordHash"];
            return $passwordHash;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    public function updateLoginDate($userID) {
        try {
            $this->_pdo = $this->connect();
            $sql = "UPDATE User
                SET LastLoginDate = :LastLoginDate
                WHERE UserID = :UserID;";
            $lastLoginDate = date("Y-m-d H:i:s");
            $stmt = $this->_pdo->prepare($sql);
            // Get the highest value of UserID + 1
            $stmt->bindValue(":UserID", $userID);
            $stmt->bindValue(":LastLoginDate", $lastLoginDate);
            $stmt->execute();
            $rowsAffected = $stmt->rowCount();
            // unset($this->_pdo);
            return $rowsAffected;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

}
