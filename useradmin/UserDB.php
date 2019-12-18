<?php

namespace UserAdmin;

class UserDB {

    const BCRYPT_COST = 14;

    /**
     * path to the sqlite file
     */
    const PATH_TO_SQLITE_FILE = "db/users.db";

    /**
     * PDO instance
     * @var type
     */
    private $pdo;
    private $nextRoleID = 1;
    private $nextUserID = 1;

    public function __construct() {
        if (!file_exists(self::PATH_TO_SQLITE_FILE)) {
            $this->connect();
            // Create tables if they do not exist
            $this->initialize();
        }
    }

    /**
     * return in instance of the PDO object that connects to the SQLite database
     * @return \PDO
     */
    public function connect() {
        if ($this->pdo == null) {
            try {
                // Create (connect to) SQLite database in file
                $this->pdo = new \PDO("sqlite:" . self::PATH_TO_SQLITE_FILE);
                // Set errormode to exceptions
                $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                echo ($e->getMessage());
            }
        }
        return $this->pdo;
    }

    protected function initialize() {
        $sql = "CREATE TABLE IF NOT EXISTS Role (
            RoleID integer PRIMARY KEY,
            Title text UNIQUE NOT NULL,
            Comment text
        )";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
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
        unset($stmt);
        unset($sql);
        // $this->createRole(1, "user", "Anonymous and unauthenticated user. Can only browse non-secured pages.");
        // $this->createRole(2, "superuser", "Authenticated user. Can browse all pages, but cannot edit information.");
        // $this->createRole(3, "admin", "Authenticated user. Can browse all pages and edit information.");
        // $this->createUser(1, "admin@rgprogramming.com", "P@ssW0rd", "3", "For test purposes only.");
        $this->setNextRoleID();
        $this->setNextUserID();
    }

    public function getNextRoleID() {
        return $this->nextRoleID;
    }

    private function setNextRoleID() {
        $sql = "SELECT MAX(RoleID) as maxRoleID FROM Role";
        $result = $this->pdo->query($sql);
        $row = $result->fetch();
        echo "Max RoleID: {$row["maxRoleID"]}<br>";
    }

    public function getNextUserID() {
        return $this->nextUserID;
    }

    private function setNextUserID() {
        $sql = "SELECT MAX(UserID) as maxUserID FROM User";
        $result = $this->pdo->query($sql);
        $row = $result->fetch();
        echo "Max UserID: {$row["maxUserID"]}<br>";
    }

    /**
     * 
     * @param type $title The name of the role.
     * @param type $comment Any additional comments.
     * @return type The last rowid used.
     */
    public function createRole($roleID, $title, $comment) {
        $sql = "INSERT INTO Role
                VALUES (:RoleID, :Title, :Comment)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":RoleID", $roleID);
        $stmt->bindValue(":Title", $title);
        $stmt->bindValue(":Comment", $comment);

        $stmt->execute();
        $this->nextRoleID = ($this->pdo->lastInsertId()) + 1;
        return $this->pdo->lastInsertId();
    }

    /**
     * Creates a new user.
     * @param type $username The username to create.
     * @param type $password The password of the user.
     * @param type $comment Any additional comments.
     * @return type The last rowid used.
     */
    public function createUser($userID, $username, $password, $roleID, $comment) {
        $sql = "INSERT INTO User
                VALUES (:UserID, :UserName, :PasswordHash, :RoleID, :Email, :IsLockedOut, :LastLoginDate, :CreateDate, :Comment)";

        $options = array("cost" => self::BCRYPT_COST);
        $passwordHash = password_hash($password, PASSWORD_BCRYPT, $options);

        $email = $username;
        $isLockedOut = 0;
        $lastLoginDate = date("Y-m-d H:i:s");
        $createDate = date("Y-m-d H:i:s");

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":UserID", $userID);
        $stmt->bindValue(":UserName", $username);
        $stmt->bindValue(":PasswordHash", $passwordHash);
        $stmt->bindValue(":RoleID", $roleID);
        $stmt->bindValue(":Email", $email);
        $stmt->bindValue(":IsLockedOut", $isLockedOut);
        $stmt->bindValue(":LastLoginDate", $lastLoginDate);
        $stmt->bindValue(":CreateDate", $createDate);
        $stmt->bindValue(":Comment", $comment);

        $stmt->execute();
        $this->nextUserID = ($this->pdo->lastInsertId()) + 1;
        return $this->pdo->lastInsertId();
    }

}
