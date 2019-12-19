<?php

/**
 * Database class. Handles all database calls.
 */

namespace UserAdmin;

class UserDB {

    /**
     * Path to database
     */
    const PATH_TO_SQLITE_DB = "db/users.db";

    /**
     * Computational cost for Key Derivation Functions (KDF)
     */
    const BCRYPT_COST = 14;

    /**
     * PDO instance
     * @var type
     */
    private $pdo;

    /**
     * Constructor. If the database is not found, it creates it.
     */
    public function __construct() {
        if (!file_exists(self::PATH_TO_SQLITE_DB)) {
            $this->connect();
            // Create tables if they do not exist
            $this->initialize();
        }
    }

    /**
     * Connects to the database.
     * @return \PDO The PDO object that connects to the SQLite database
     */
    public function connect() {
        if ($this->pdo == null) {
            try {
                // Create (connect to) SQLite database in file
                $this->pdo = new \PDO("sqlite:" . self::PATH_TO_SQLITE_DB);
                // Turn on foreign key constraints
                $this->pdo->exec("PRAGMA foreign_keys = ON;");
                // Set errormode to exceptions
                $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                echo ($e->getMessage());
            }
        }
        return $this->pdo;
    }

    /**
     * Creates the database if it does not exist.
     */
    private function initialize() {
        // Create Role table first
        $sql = "CREATE TABLE IF NOT EXISTS Role (
            RoleID integer PRIMARY KEY,
            Title text UNIQUE NOT NULL,
            Comment text
        )";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        // Create User table next due to the foreign key constraint
        $sql = "CREATE TABLE IF NOT EXISTS User (
            UserID integer PRIMARY KEY,
            UserName text UNIQUE NOT NULL,
            PasswordHash text NOT NULL,
            RoleID integer NOT NULL,
            Email text NOT NULL,
            IsLockedOut integer NOT NULL DEFAULT '0' CHECK (IsLockedOut >= 0 OR IsLockedOut <= 1),
            LastLoginDate string NOT NULL,
            CreateDate string NOT NULL,
            Comment text,
            FOREIGN KEY(RoleID) REFERENCES Role(RoleID)
        )";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        // Set initial values
        $this->createRole("user", "Anonymous and unauthenticated user. Can only browse non-secured pages.");
        $this->createRole("superuser", "Authenticated user. Can browse all pages, but cannot edit information.");
        $this->createRole("admin", "Authenticated user. Can browse all pages and edit information.");
        $this->createUser("admin@rgprogramming.com", "P@ssW0rd", "3", "For test purposes only.");
    }

    /**
     * Gets the highest value of RoleID (usually the last row inserted) in the Role table.
     * @return type The max value of RoleID or 0 if there is no data.
     */
    private function getMaxRoleID() {
        $sql = "SELECT MAX(RoleID) as maxRoleID FROM Role";
        $result = $this->pdo->query($sql);
        $row = $result->fetch();
        $maxRoleID = $row["maxRoleID"] == "" ? 0 : $row["maxRoleID"];
        return $maxRoleID;
    }

    /**
     * Gets the highest value of UserID (usually the last row inserted) in the User table.
     * @return type The max value of UserID or 0 if there is no data.
     */
    private function getMaxUserID() {
        $sql = "SELECT MAX(UserID) as maxUserID FROM User";
        $result = $this->pdo->query($sql);
        $row = $result->fetch();
        $maxUserID = $row["maxUserID"] == "" ? 0 : $row["maxUserID"];
        return $maxUserID;
    }

    /**
     * Inserts a user role into the database.
     * @param type $title The name of the role.
     * @param type $comment Any additional comments.
     * @return type The rowid of the new role.
     */
    public function createRole($title, $comment) {
        $sql = "INSERT INTO Role
                VALUES (:RoleID, :Title, :Comment)";
        $stmt = $this->pdo->prepare($sql);
        // Get the highest value of RoleID + 1
        $stmt->bindValue(":RoleID", $this->getMaxRoleID() + 1);
        $stmt->bindValue(":Title", $title);
        $stmt->bindValue(":Comment", $comment);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    /**
     * Inserts a new user into the database.
     * @param type $username The username to create.
     * @param type $password The password of the user.
     * @param type $comment Any additional comments.
     * @return type The rowid of the new user.
     */
    public function createUser($username, $password, $roleID, $comment) {
        $sql = "INSERT INTO User
                VALUES (:UserID, :UserName, :PasswordHash, :RoleID, :Email, :IsLockedOut, :LastLoginDate, :CreateDate, :Comment)";
        // Hash the password using Key Derivation Functions (KDF)
        $options = array("cost" => self::BCRYPT_COST);
        $passwordHash = password_hash($password, PASSWORD_BCRYPT, $options);
        // Email and username are initially the same
        $email = $username;
        // Set other initial values
        $isLockedOut = 0;
        $lastLoginDate = date("Y-m-d H:i:s");
        $createDate = date("Y-m-d H:i:s");

        $stmt = $this->pdo->prepare($sql);
        // Get the highest value of UserID + 1
        $stmt->bindValue(":UserID", $this->getMaxUserID() + 1);
        $stmt->bindValue(":UserName", $username);
        $stmt->bindValue(":PasswordHash", $passwordHash);
        $stmt->bindValue(":RoleID", $roleID);
        $stmt->bindValue(":Email", $email);
        $stmt->bindValue(":IsLockedOut", $isLockedOut);
        $stmt->bindValue(":LastLoginDate", $lastLoginDate);
        $stmt->bindValue(":CreateDate", $createDate);
        $stmt->bindValue(":Comment", $comment);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    /**
     * Authenticates the given user with the given password. If the user does not exist, any action
     * is performed. If it exists, its stored password is retrieved, and then password_verify
     * built-in function will check that the supplied password matches the derived one.
     *
     * @param $username The username to authenticate.
     * @param $password The password to authenticate the user.
     * @return True if the password matches for the username, false if not.
     */
    public function authenticateUser($username, $password) {
        $authenticated = false;
        if ($this->userExists($username)) {
            $storedPassword = $this->getUserPassword($username);
            $authenticated = password_verify($password, $storedPassword) ? true : false;
        }
        return $authenticated;
    }

    /**
     * Checks if the given users exists in the database.
     *
     * @param $username The username to check if exists.
     * @return True if the users exists, false if not.
     */
    private function userExists($username) {
        $sql = "SELECT COUNT(*) AS Count
                FROM   User
                WHERE  UserName = :UserName";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":UserName", $username);
        $stmt->execute();
        // Fetch the result set
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $exists = ($result["Count"]) == 1 ? true : false;
        return $exists;
    }

    /**
     * Gets given users password.
     *
     * @param $username The username to get the password of.
     * @return The password of the given user.
     */
    private function getUserPassword($username) {
        $sql = "SELECT PasswordHash
                FROM   User
                WHERE  UserName = :UserName";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":UserName", $username);
        $stmt->execute();
        // Fetch the result set
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $passwordHash = $result["PasswordHash"];
        return $passwordHash;
    }

    public function getUserDetails($username) {
        $sql = "SELECT *
                FROM   User
                WHERE  UserName = :UserName";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":UserName", $username);
        $stmt->execute();
        // Fetch the result set
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function getUserRole($roleID) {
        $sql = "SELECT Title
                FROM   Role
                WHERE  RoleID = :RoleID";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":RoleID", $roleID);
        $stmt->execute();
        // Fetch the result set
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $roleTitle = $result["Title"];
        return $roleTitle;
    }

    public function updateLoginDate($userID) {
        $sql = "UPDATE User
                SET    LastLoginDate = :LastLoginDate
                WHERE  UserID = :UserID";
        $lastLoginDate = date("Y-m-d H:i:s");
        $stmt = $this->pdo->prepare($sql);
        // Get the highest value of UserID + 1
        $stmt->bindValue(":UserID", $userID);
        $stmt->bindValue(":LastLoginDate", $lastLoginDate);
        $stmt->execute();
    }

}
