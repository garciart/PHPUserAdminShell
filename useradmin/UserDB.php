<?php

namespace UserAdmin;

class UserDB {

    const BCRYPT_COST = 14;

    /**
     * path to the sqlite file
     */
    const PATH_TO_SQLITE_FILE = 'db/users.db';

    /**
     * PDO instance
     * @var type
     */
    private $pdo;
    private $nextID = 1;

    public function __construct() {
        if (!file_exists(self::PATH_TO_SQLITE_FILE)) {
            $this->connect();
            // Create tables if they do not exist
            $this->initialize();
            echo "Database created.";
            echo ($nextID = $this->createUser("steve@steve.com", "steve"));
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
        // Do not add a ID primary key column. SQLite autogenerates the rowID column.
        $sql = "CREATE TABLE IF NOT EXISTS user (
                    UserName text UNIQUE NOT NULL,
                    PasswordHash text NOT NULL,
                    Email text NOT NULL,
                    IsLockedOut integer NOT NULL DEFAULT '0' CHECK (IsLockedOut >= 0 OR IsLockedOut <= 1),
                    LastLoginDate string NOT NULL,
                    CreateDate string NOT NULL,
                    Comment text
                )";

        $this->pdo->exec($sql);
    }

    protected function getNextID() {
        try {
            return ($nextID = ((int) ($this->pdo->query("SELECT * FROM SQLITE_SEQUENCE WHERE name='TABLE';"))) + 1);
        } catch (\PDOException $e) {
            unset($e);
            return 1;
        }
    }

    /**
     * Creates a new user.
     *
     * @param $username The username to create.
     * @param $password The password of the user.
     */
    public function createUser($username, $password) {
        $sql = 'INSERT INTO user
                VALUES (:UserName, :PasswordHash, :Email, :IsLockedOut, :LastLoginDate, :CreateDate, :Comment)';

        $options = array('cost' => self::BCRYPT_COST);
        $passwordHash = password_hash($password, PASSWORD_BCRYPT, $options);

        $email = $username;
        $isLockedOut = 0;
        $lastLoginDate = date('Y-m-d H:i:s');
        $createDate = date('Y-m-d H:i:s');
        $comment = "New user.";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':UserName', $username);
        $stmt->bindValue(':PasswordHash', $passwordHash);
        $stmt->bindValue(':Email', $email);
        $stmt->bindValue(':IsLockedOut', $isLockedOut);
        $stmt->bindValue(':LastLoginDate', $lastLoginDate);
        $stmt->bindValue(':CreateDate', $createDate);
        $stmt->bindValue(':Comment', $comment);

        $stmt->execute();

        return $this->pdo->lastInsertId();
    }

}
