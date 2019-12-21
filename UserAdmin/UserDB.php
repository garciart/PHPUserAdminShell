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
     * Main methods:
     * _construct
     * connect()
     * createRoleTable()
     * createUserTable()
     * create role($title, $comment)
     * create user($nickname, $username, $password, $roleID, $comment)
     * getAllRoles()
     * getRole($roleID)
     * getAllUsers()
     * getUserByUserID($userID)
     * getUserByUsername($username)
     * updateRole($title, $comment)
     * updateUser($nickname, $username, $password, $roleID, $comment)
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
     * Inserts a user role into the database.
     * @param type $title The name of the role.
     * @param type $comment Any additional comments.
     * @return type The rowid of the new role.
     */
    public function createRole($title, $comment) {
        try {
            $this->pdo = $this->connect();
            $sql = "INSERT INTO Role
                VALUES (:RoleID, :Title, :Comment)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":RoleID", $this->getNextRoleID());
            $stmt->bindValue(":Title", $title);
            $stmt->bindValue(":Comment", $comment);
            $stmt->execute();
            $lastInsertID = $this->pdo->lastInsertId();
            unset($this->pdo);
            return $lastInsertID;
        } catch (\PDOException $e) {
            echo $e->getMessage();
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
        echo "Here!";
        try {
            $this->pdo = $this->connect();
            $sql = "INSERT INTO User
                VALUES (:UserID, :Username, :Nickname, :PasswordHash, :RoleID, :Email, :IsLockedOut, :LastLoginDate, :CreateDate, :Comment)";
            // Hash the password using Key Derivation Functions (KDF)
            $options = array("cost" => self::BCRYPT_COST);
            $passwordHash = password_hash($password, PASSWORD_BCRYPT, $options);
            // Email and username are initially the same
            $email = $username;
            // Set other initial values
            $isLockedOut = 0;
            $lastLoginDate = date("Y-m-d H:i:s");
            $createDate = date("Y-m-d H:i:s");
            // Execute SQL
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":UserID", $this->getNextUserID());
            $stmt->bindValue(":Username", $username);
            $stmt->bindValue(":Nickname", $nickname);
            $stmt->bindValue(":PasswordHash", $passwordHash);
            $stmt->bindValue(":RoleID", $roleID);
            $stmt->bindValue(":Email", $email);
            $stmt->bindValue(":IsLockedOut", $isLockedOut);
            $stmt->bindValue(":LastLoginDate", $lastLoginDate);
            $stmt->bindValue(":CreateDate", $createDate);
            $stmt->bindValue(":Comment", $comment);
            $stmt->execute();
            $lastInsertID = $this->pdo->lastInsertId();
            unset($this->pdo);
            return $lastInsertID;
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getAllRoles() {
        try {
            $this->pdo = $this->connect();
            $sql = "SELECT *
                FROM Role
                ORDER BY RoleID ASC;";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            // Fetch the result set
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            unset($this->pdo);
            return $result;
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getRole($roleID) {
        try {
            $this->pdo = $this->connect();
            $sql = "SELECT *
                FROM Role
                WHERE RoleID = :RoleID";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":RoleID", $roleID);
            $stmt->execute();
            // Fetch the result set
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            unset($this->pdo);
            return $result;
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getAllUsers() {
        try {
            $this->pdo = $this->connect();
            $sql = "SELECT *
                FROM User
                ORDER BY Username ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            // Fetch the result set
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            unset($this->pdo);
            return $result;
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getUserByUserID($userID) {
        try {
            $this->pdo = $this->connect();
            $sql = "SELECT *
                FROM User
                WHERE UserID = :UserID";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":UserID", $userID);
            $stmt->execute();
            // Fetch the result set
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            unset($this->pdo);
            return $result;
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getUserByUsername($username) {
        try {
            $this->pdo = $this->connect();
            $sql = "SELECT *
                FROM User
                WHERE Username = :Username";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":Username", $username);
            $stmt->execute();
            // Fetch the result set
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            unset($this->pdo);
            return $result;
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function updateRole($roleID, $title, $comment) {
        try {
            $this->pdo = $this->connect();
            $sql = "UPDATE Tole
                SET Title = :Title,
                Comment = :Comment
                WHERE RoleID = :RoleID";
            $lastLoginDate = date("Y-m-d H:i:s");
            $stmt = $this->pdo->prepare($sql);
            // Get the highest value of UserID + 1
            $stmt->bindValue(":UserID", $userID);
            $stmt->bindValue(":LastLoginDate", $lastLoginDate);
            $stmt->execute();
            unset($this->pdo);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function updateUser($userID, $username, $nickname, $password, $roleID, $email, $isLockedOut, $comment) {
        try {
            $this->pdo = $this->connect();
            $sql = "UPDATE User
                SET Username = :Username,
                Nickname = :Nickname,
                Password = :Password,
                RoleID = :RoleID,
                Email = :Email,
                IsLockedOut = :IsLockedOut,
                Comment = :Comment
                WHERE  UserID = :UserID";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":UserID", $userID);
            $stmt->bindValue(":Username", $username);
            $stmt->bindValue(":Nickname", $nickname);
            $stmt->bindValue(":Password", $password);
            $stmt->bindValue(":RoleID", $roleID);
            $stmt->bindValue(":Email", $email);
            $stmt->bindValue(":IsLockedOut", $isLockedOut);
            $stmt->bindValue(":Comment", $comment);
            $stmt->execute();
            unset($this->pdo);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function deleteRole($roleID) {
        try {
            $this->pdo = $this->connect();
            $sql = "DELETE FROM Role
                WHERE RoleID = :RoleID";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":UserID", $roleID);
            $stmt->execute();
            unset($this->pdo);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function deleteUser($userID) {
        try {
            $this->pdo = $this->connect();
            $sql = "DELETE FROM User
                WHERE UserID = :UserID";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":UserID", $userID);
            $stmt->execute();
            unset($this->pdo);
        } catch (\PDOException $e) {
            echo $e->getMessage();
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
            $this->pdo = $this->connect();
            $sql = "CREATE TABLE IF NOT EXISTS Role (
                RoleID integer PRIMARY KEY,
                Title text UNIQUE NOT NULL,
                Comment text
            )";
            $this->pdo->exec($sql);
            // Set initial values
            $this->createRole("user", "Anonymous and unauthenticated user. Can only browse non-secured pages.");
            $this->createRole("superuser", "Authenticated user. Can browse all pages, but cannot edit information.");
            $this->createRole("admin", "Authenticated user. Can browse all pages and edit information.");
            unset($this->pdo);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Creates the database if it does not exist.
     */
    private function createUserTable() {
        // Create User table after Role table due to the foreign key constraint
        try {
            $this->pdo = $this->connect();
            $sql = "CREATE TABLE IF NOT EXISTS User (
                UserID integer PRIMARY KEY,
                Username text UNIQUE NOT NULL,
                Nickname text NOT NULL,
                PasswordHash text NOT NULL,
                RoleID integer NOT NULL,
                Email text NOT NULL,
                IsLockedOut integer NOT NULL DEFAULT '0' CHECK (IsLockedOut >= 0 OR IsLockedOut <= 1),
                LastLoginDate string NOT NULL,
                CreateDate string NOT NULL,
                Comment text,
                FOREIGN KEY(RoleID) REFERENCES Role(RoleID)
            )";
            $this->pdo->exec($sql);
            unset($this->pdo);
            // Set initial values
            $this->createUser("rob@rgprogramming.com", "Rob", "123456789", 1, "New user.");
            $this->createUser("steve@rgprogramming.com", "Steve", "abcdefghi", 2, "Old user.");
            $this->createUser("admin@rgprogramming.com", "Admin", "8675309", 3, "For test purposes only.");
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Gets the highest value of RoleID (usually the last row inserted) in the Role table.
     * @return type The max value of RoleID or 0 if there is no data.
     */
    private function getNextRoleID() {
        try {
            $this->pdo = $this->connect();
            $sql = "SELECT MAX(RoleID) as maxRoleID FROM Role";
            $result = $this->pdo->query($sql);
            $row = $result->fetch();
            $maxRoleID = $row["maxRoleID"] == "" ? 0 : $row["maxRoleID"];
            unset($this->pdo);
            return $maxRoleID + 1;
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Gets the highest value of UserID (usually the last row inserted) in the User table.
     * @return type The max value of UserID or 0 if there is no data.
     */
    private function getNextUserID() {
        try {
            $this->pdo = $this->connect();
            $sql = "SELECT MAX(UserID) as maxUserID FROM User";
            $result = $this->pdo->query($sql);
            $row = $result->fetch();
            $maxUserID = $row["maxUserID"] == "" ? 0 : $row["maxUserID"];
            unset($this->pdo);
            return $maxUserID + 1;
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
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
        /*
         * Julen Pardo came up with this. 
         * Thought about changing the method to retrieve the UserID instead,
         * but Username is supposed to be unique.
         * If the count != 1, that means there are no users or more than one,
         * which means something is wrong. This is a better method.
         */
        try {
            $this->pdo = $this->connect();
            $sql = "SELECT COUNT(*) AS Count
                FROM User
                WHERE Username = :Username";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":Username", $username);
            $stmt->execute();
            // Fetch the result set
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            unset($this->pdo);
            $exists = ($result["Count"]) == 1 ? true : false;
            return $exists;
        } catch (\PDOException $e) {
            echo $e->getMessage();
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
            $this->pdo = $this->connect();
            $sql = "SELECT PasswordHash
                FROM User
                WHERE Username = :Username";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":Username", $username);
            $stmt->execute();
            // Fetch the result set
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            unset($this->pdo);
            $passwordHash = $result["PasswordHash"];
            return $passwordHash;
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function updateLoginDate($userID) {
        try {
            $this->pdo = $this->connect();
            $sql = "UPDATE User
                SET LastLoginDate = :LastLoginDate
                WHERE UserID = :UserID";
            $lastLoginDate = date("Y-m-d H:i:s");
            $stmt = $this->pdo->prepare($sql);
            // Get the highest value of UserID + 1
            $stmt->bindValue(":UserID", $userID);
            $stmt->bindValue(":LastLoginDate", $lastLoginDate);
            $stmt->execute();
            unset($this->pdo);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

}
