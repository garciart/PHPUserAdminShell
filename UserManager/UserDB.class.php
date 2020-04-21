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
    const PATH_TO_SQLITE_DB = "db" . DIRECTORY_SEPARATOR . "users.db";

    // const PATH_TO_SQLITE_DB = USERMANGER_ROOT_DIR . DIRECTORY_SEPARATOR . "db" . DIRECTORY_SEPARATOR . "users.db";

    /**
     * Main methods:
     * _construct
     * createRoleTable()
     * createUserTable()
     * connect()
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
        try {
            if (!file_exists(self::PATH_TO_SQLITE_DB)) {
                // Create tables if they do not exist with initial values
                // Make sure you create the Role table first
                $this->createRoleTable();
                $this->createRole(5, "Member", "Authenticated user, aka Member. Only authorized to view and edit his or her own profile.");
                $this->createRole(15, "Editor", "Authenticated user, aka Editor. Authorized to view, add, edit, and delete profiles and view (but not edit) the content of other User Manager pages, such as Role Administration.");
                $lastInsertId = $this->createRole(20, "Administrator", "Authenticated user, aka Administrator. Authorized to view, add, edit, and delete all profiles and roles.");
                console_log("Last Insert ID: " . $lastInsertId);
                if ($lastInsertId != 3) {
                    throw new Exception("Bad last insert ID when creating Role table: expected 3, got " . $lastInsertId . ".");
                }
                $this->createUserTable();
                $this->createUser("rob@rgprogramming.com", "Rob", "P@ssW0rd", 1, "New member.", 1, "What planet are you on?", "Earth");
                $lastInsertId = $this->createUser("admin@rgprogramming.com", "Admin", "W0rdP@ss", 3, "Administrator.", 1, "What is the answer to the ultimate question?", "42");
                if ($lastInsertId != 2) {
                    throw new ErrorException("Bad last insert ID when creating User table: expected 2, got " . $lastInsertId . ".");
                }
            }
        } catch (Exception $ex) {
            error_log($ex->getMessage());
        }
    }

    /**
     * Creates the Role table if it does not exist in the database.
     *
     * @return integer The number of rows affected.
     *                 A value less than 0 indicates an error.
     */
    private function createRoleTable() {
        $rowsAffected = null;
        try {
            $conn = $this->connect();
            $sql = "CREATE TABLE IF NOT EXISTS Role (
                RoleID integer PRIMARY KEY,
                Level integer NOT NULL,
                Title text UNIQUE NOT NULL,
                Comment text
            );";
            $rowsAffected = $conn->exec($sql);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        } finally {
            unset($conn);
        }
        return $rowsAffected;
    }

    /**
     * Creates the User table if it does not exist in the database.
     *
     * @return integer The number of rows affected.
     *                 A value less than 0 indicates an error.
     */
    private function createUserTable() {
        // Create User table after Role table due to the foreign key constraint
        $rowsAffected = null;
        try {
            $conn = $this->connect();
            $sql = "CREATE TABLE IF NOT EXISTS User (
                UserID integer PRIMARY KEY,
                Username text UNIQUE NOT NULL,
                Nickname text NOT NULL,
                PasswordHash text NOT NULL,
                RoleID integer NOT NULL,
                Email text UNIQUE NOT NULL,
                IsLockedOut integer NOT NULL DEFAULT '0' CHECK (IsLockedOut >= 0 OR IsLockedOut <= 1),
                LastLoginDate text NOT NULL,
                CreationDate text NOT NULL,
                Comment text,
                IsActive integer NOT NULL DEFAULT '0' CHECK (IsActive >= 0 OR IsActive <= 1),
                SecurityQuestion text NOT NULL,
                SecurityAnswerHash text NOT NULL,
                FOREIGN KEY(RoleID) REFERENCES Role(RoleID)
            );";
            $rowsAffected = $conn->exec($sql);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        } finally {
            unset($conn);
        }
        return $rowsAffected;
    }

    /**
     * Connects to the database.
     *
     * @return \PDO The PDO object that connects to the SQLite database
     */
    public function connect() {
        $pdo = null;
        // Check if connection does not exists
        if (!isset($pdo)) {
            try {
                // Create (connect to) SQLite database in file
                $pdo = new \PDO("sqlite:" . self::PATH_TO_SQLITE_DB);
                // Turn on foreign key constraints
                $pdo->exec("PRAGMA foreign_keys = ON;");
                // Set errormode to exceptions
                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                error_log($e->getMessage());
            }
        }
        return $pdo;
    }

    /**
     * Inserts a new role into the database.
     * 
     * @param integer $level   The role's access level.
     * @param string  $title   The name of the role.
     * @param string  $comment Any additional comments.
     * 
     * @return integer The rowid of the new role. A value of 0 indicates an error.
     */
    public function createRole($level, $title, $comment) {
        $lastRowID = 0;
        try {
            $conn = $this->connect();
            $sql = "INSERT INTO Role
                VALUES (:RoleID, :Level, :Title, :Comment);";
            // Execute SQL
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":RoleID", $this->getNextRoleID());
            $stmt->bindValue(":Level", $level);
            $stmt->bindValue(":Title", $title);
            $stmt->bindValue(":Comment", $comment);
            $stmt->execute();
            // The last insert ID should be greater than 0
            $lastRowID = $conn->lastInsertId();
        } catch (\PDOException $ex) {
            error_log($ex->getMessage());
        } finally {
            unset($conn);
        }
        return $lastRowID;
    }

    /**
     * Inserts a new user into the database.
     * 
     * @param string  $username         The username to create.
     * @param string  $nickname         The nickname of the user.
     * @param string  $password         The password of the user.
     * @param integer $roleID           The role's ID
     * @param string  $comment          Any additional comments.
     * @param string  $isActive         If the user has an active account.
     * @param string  $securityQuestion Question to verify user without password.
     * @param string  $securityAnswer   Answer to security question.
     * 
     * @return integer The rowid of the new user.
     */
    public function createUser($username, $nickname, $password, $roleID, $comment, $isActive, $securityQuestion, $securityAnswer) {
        $lastRowID = 0;
        try {
            $conn = $this->connect();
            $sql = "INSERT INTO User
                VALUES (:UserID, :Username, :Nickname, :PasswordHash, :RoleID, :Email, :IsLockedOut, :LastLoginDate, :CreationDate, :Comment, :IsActive, :SecurityQuestion, :SecurityAnswerHash);";
            // Hash the password using Key Derivation Functions (KDF) with BCRYPT_COST from CommonCode
            // $options = array("cost" => BCRYPT_COST);
            $passwordHash = getHash($password);
            $securityAnswerHash = getHash($securityAnswer);
            // Email and username are initially the same
            $email = $username;
            // Set other initial values
            $isLockedOut = 0;
            $lastLoginDate = date("Y-m-d H:i:s");
            $creationDate = date("Y-m-d H:i:s");
            // Execute SQL
            $stmt = $conn->prepare($sql);
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
            $stmt->bindValue(":IsActive", $isActive);
            $stmt->bindValue(":SecurityQuestion", $securityQuestion);
            $stmt->bindValue(":SecurityAnswerHash", $securityAnswer);
            $stmt->execute();
            // The last insert ID should be greater than 0
            $lastRowID = $conn->lastInsertId();
        } catch (\PDOException $ex) {
            error_log($ex->getMessage());
        } finally {
            unset($conn);
        }
        return $lastRowID;
    }

    /**
     * Gets all the roles in the database and their information.
     *
     * @return array An array of all the roles in the database and their
     *               information. An empty array indicates an error.
     */
    public function getAllRoles() {
        $result = null;
        try {
            $conn = $this->connect();
            $sql = "SELECT *
                FROM Role
                ORDER BY Level ASC;";
            // Returns an empty result set if not found
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
        } catch (\PDOException $ex) {
            error_log($ex->getMessage());
        } finally {
            unset($conn);
        }
        return $result;
    }

    /**
     * Returns a single role and its information.
     *
     * @param integer $roleID The role's ID.
     *
     * @return array The role's information indexed by column name or empty if the
     *               role's ID is not found.
     */
    public function getRole($roleID) {
        $result = null;
        try {
            $conn = $this->connect();
            $sql = "SELECT *
                FROM Role
                WHERE RoleID = :RoleID;";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":RoleID", $roleID);
            $stmt->execute();
            // Returns an empty result set if not found
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $ex) {
            error_log($ex->getMessage());
        } finally {
            unset($conn);
        }
        return $result;
    }

    /**
     * Gets all the users in the database and their information.
     *
     * @return array An array of all the users in the database and their
     *               information. An empty array indicates an error.
     */
    public function getAllUsers() {
        $result = null;
        try {
            $conn = $this->connect();
            $sql = "SELECT *
                FROM User
                ORDER BY Username ASC;";
            // Returns an empty result set if not found
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
        } catch (\PDOException $ex) {
            error_log($ex->getMessage());
        } finally {
            unset($conn);
        }
        return $result;
    }

    /**
     * Returns a single user and his or her information.
     *
     * @param integer $userID The user's ID.
     *
     * @return array The user's information indexed by column name or empty if the
     *               user's ID is not found.
     */
    public function getUserByUserID($userID) {
        $result = null;
        try {
            $conn = $this->connect();
            $sql = "SELECT *
                FROM User
                WHERE UserID = :UserID;";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":UserID", $userID);
            $stmt->execute();
            // Returns an empty result set if not found
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $ex) {
            error_log($ex->getMessage());
        } finally {
            unset($conn);
        }
        return $result;
    }

    /**
     * Returns a single user and his or her information.
     *
     * @param string $username The user's username/email.
     *
     * @return array The user's information indexed by column name or empty if the
     *               user's username/email is not found.
     */
    public function getUserByUsername($username) {
        $result = null;
        try {
            $conn = $this->connect();
            $sql = "SELECT *
                FROM User
                WHERE Username = :Username;";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":Username", $username);
            $stmt->execute();
            // Returns an empty result set if not found
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $ex) {
            error_log($ex->getMessage());
        } finally {
            unset($conn);
        }
        return $result;
    }

    /**
     * Updates a role's information in the database.
     * 
     * @param integer $roleID  The role's ID.
     * @param integer $level   The role's access level.
     * @param string  $title   The name of the role.
     * @param string  $comment Any additional comments.
     * 
     * @return integer The number of rows affected. A value other than 1 indicates
     *                 an error.
     */
    public function updateRole($roleID, $level, $title, $comment) {
        $rowsAffected = 0;
        try {
            $conn = $this->connect();
            $sql = "UPDATE Role
                SET Level = :Level,
                Title = :Title,
                Comment = :Comment
                WHERE RoleID = :RoleID;";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":RoleID", $roleID);
            $stmt->bindValue(":Level", $level);
            $stmt->bindValue(":Title", $title);
            $stmt->bindValue(":Comment", $comment);
            $stmt->execute();
            // Rows affected should equal 1
            $rowsAffected = $stmt->rowCount();
        } catch (\PDOException $ex) {
            error_log($ex->getMessage());
        } finally {
            unset($conn);
        }
        return $rowsAffected;
    }

    /**
     * Updates a user's information in the database.
     *
     * @param integer $userID           The user's ID.
     * @param string  $username         The username to create.
     * @param string  $nickname         The nickname of the user.
     * @param string  $passwordHash     The hash of the password of the user.
     * @param integer $roleID           The role's ID
     * @param string  $email            The email of the user.
     * @param boolean $isLockedOut      Indicates if the user is or is not locked out.
     * @param string  $comment          Any additional comments.
     * @param string  $isActive         If the user has an active account.
     * @param string  $securityQuestion Question to verify user without password.
     * @param string  $securityAnswer   Answer to security question.
     * 
     * @return integer The number of rows affected. A value other than 1 indicates
     *                 an error.
     */
    public function updateUser($userID, $username, $nickname, $passwordHash, $roleID, $email, $isLockedOut, $comment, $isActive, $securityQuestion, $securityAnswer) {
        $rowsAffected = 0;
        try {
            $conn = $this->connect();
            $sql = "UPDATE User
                SET Username = :Username,
                Nickname = :Nickname,
                PasswordHash = :PasswordHash,
                RoleID = :RoleID,
                Email = :Email,
                IsLockedOut = :IsLockedOut,
                Comment = :Comment,
                IsActive = :IsActive,
                SecurityQuestion = :SecurityQuestion,
                SecurityAnswerHash = :SecurityAnswerHash
                WHERE  UserID = :UserID;";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":UserID", $userID);
            $stmt->bindValue(":Username", $username);
            $stmt->bindValue(":Nickname", $nickname);
            $stmt->bindValue(":PasswordHash", $passwordHash);
            $stmt->bindValue(":RoleID", $roleID);
            $stmt->bindValue(":Email", $email);
            $stmt->bindValue(":IsLockedOut", $isLockedOut);
            $stmt->bindValue(":Comment", $comment);
            $stmt->bindValue(":IsActive", $isActive);
            $stmt->bindValue(":SecurityQuestion", $securityQuestion);
            $stmt->bindValue(":SecurityAnswerHash", $securityAnswer);
            $stmt->execute();
            // Rows affected should equal 1
            $rowsAffected = $stmt->rowCount();
        } catch (\PDOException $ex) {
            error_log($ex->getMessage());
        } finally {
            unset($conn);
        }
        return $rowsAffected;
    }

    /**
     * Deletes a role from the database.
     * 
     * @param integer $roleID The role's ID
     * 
     * @return integer The number of rows affected. A value other than 1 indicates
     *                 an error.
     */
    public function deleteRole($roleID) {
        $rowsAffected = 0;
        try {
            $conn = $this->connect();
            $sql = "DELETE FROM Role
                WHERE RoleID = :RoleID;";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":RoleID", $roleID);
            $stmt->execute();
            // Rows affected should equal 1
            $rowsAffected = $stmt->rowCount();
        } catch (\PDOException $ex) {
            error_log($ex->getMessage());
        } finally {
            unset($conn);
        }
        return $rowsAffected;
    }

    /**
     * Deletes a user from the database.
     * 
     * @param integer $userID The user's ID.
     * 
     * @return integer The number of rows affected. A value other than 1 indicates
     *                 an error.
     */
    public function deleteUser($userID) {
        $rowsAffected = 0;
        try {
            $conn = $this->connect();
            $sql = "DELETE FROM User
                WHERE UserID = :UserID;";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":UserID", $userID);
            $stmt->execute();
            // Rows affected should equal 1
            $rowsAffected = $stmt->rowCount();
        } catch (\PDOException $ex) {
            error_log($ex->getMessage());
        } finally {
            unset($conn);
        }
        return $rowsAffected;
    }

    /**
     * Gets the highest value of RoleID (usually the last row inserted) from the Role table.
     * 
     * @return integer The anticipated value of the next RoleID or 0 if there is no data.
     */
    private function getNextRoleID() {
        $nextRoleID = 0;
        try {
            $conn = $this->connect();
            $sql = "SELECT MAX(RoleID) as maxRoleID FROM Role;";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch();
            $nextRoleID = $row["maxRoleID"] == "" ? 0 : $row["maxRoleID"];
        } catch (\PDOException $ex) {
            error_log($ex->getMessage());
        } finally {
            unset($conn);
        }
        // Add 1 to the last role ID
        return $nextRoleID + 1;
    }

    /**
     * Gets the highest value of UserID (usually the last row inserted) from the User table.
     * 
     * @return integer The anticipated value of the next UserID or 0 if there is no data.
     */
    private function getNextUserID() {
        $nextUserID = 0;
        try {
            $conn = $this->connect();
            $sql = "SELECT MAX(UserID) as maxUserID FROM User;";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch();
            $nextUserID = $row["maxUserID"] == "" ? 0 : $row["maxUserID"];
        } catch (\PDOException $ex) {
            error_log($ex->getMessage());
        } finally {
            unset($conn);
        }
        // Add 1 to the last user ID
        return $nextUserID + 1;
    }

    /**
     * Authenticates the given user with the given password by first checking
     * if the user exists, and then using password_verify's built-in function
     * to check that the supplied password matches the derived one.
     *
     * @param $username The username to authenticate.
     * @param $password The password to authenticate the user.
     * 
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
     * but username/email is supposed to be unique.
     * If the count != 1, that means there are no users or more than one,
     * which means something is wrong. This is a better method.
     * 
     * @param $username The username to check if exists.
     * 
     * @return True if the users exists, false if not.
     */
    private function userExists($username) {
        $exists = false;
        try {
            $conn = $this->connect();
            $sql = "SELECT COUNT(*) AS Count
                FROM User
                WHERE Username = :Username;";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":Username", $username);
            $stmt->execute();
            // Fetch the result set
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            $exists = ($result["Count"]) == 1 ? true : false;
        } catch (\PDOException $ex) {
            error_log($ex->getMessage());
        } finally {
            unset($conn);
        }
        return $exists;
    }

    /**
     * Gets given users password.
     *
     * @param $username The username to get the password of.
     * 
     * @return The hash of the password of the given user.
     */
    private function getUserPassword($username) {
        $passwordHash = null;
        try {
            $conn = $this->connect();
            $sql = "SELECT PasswordHash
                FROM User
                WHERE Username = :Username;";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":Username", $username);
            $stmt->execute();
            // Fetch the result set
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            $passwordHash = $result["PasswordHash"];
        } catch (\PDOException $ex) {
            error_log($ex->getMessage());
        } finally {
            unset($conn);
        }
        return $passwordHash;
    }

    /**
     * Updates the user's login date after a successful login.
     * 
     * @param integer $userID The user's ID.
     * 
     * @return integer The number of rows affected. A value other than 1 indicates
     *                 an error.
     */
    public function updateLoginDate($userID) {
        $rowsAffected = 0;
        try {
            $conn = $this->connect();
            $sql = "UPDATE User
                SET LastLoginDate = :LastLoginDate
                WHERE UserID = :UserID;";
            $lastLoginDate = date("Y-m-d H:i:s");
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":UserID", $userID);
            $stmt->bindValue(":LastLoginDate", $lastLoginDate);
            $stmt->execute();
            // Rows affected should equal 1
            $rowsAffected = $stmt->rowCount();
        } catch (\PDOException $ex) {
            error_log($ex->getMessage());
        } finally {
            unset($conn);
        }
        return $rowsAffected;
    }
}
