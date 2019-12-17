<?php

class DataAccess extends SQLite3 {

    const BCRYPT_COST = 14;

    function __construct() {
        $this->open('user.db');
        $this->initialize();
    }

    protected function initialize() {
        $sql = "CREATE TABLE IF NOT EXISTS user (
                    UserID integer PRIMARY KEY,
                    UserName text UNIQUE NOT NULL,
                    PasswordHash text NOT NULL
                )";

        $this->exec($sql);
        
        /*
         *                     UserID integer PRIMARY KEY,
                    UserName text UNIQUE NOT NULL,
                    Email text NOT NULL,
                    EmailConfirmed integer NOT NULL DEFAULT '0' CHECK (EmailConfirmed >= 0 OR EmailConfirmed <= 1),
                    PasswordHash text NOT NULL,
                    LegacyPasswordHash text NOT NULL,
                    IsLockedOut integer NOT NULL DEFAULT '0' CHECK (IsLockedOut >= 0 OR IsLockedOut <= 1),
                    CreateDate string NOT NULL,
                    LastLoginDate string NOT NULL,
                    Comment text
         */
    }

    /**
     * Creates a new user.
     *
     * @param $username The username to create.
     * @param $password The password of the user.
     */
    public function createUser($username, $password) {
        $createDate = date('Y-m-d H:i:s');
        echo $createDate;
        $sql = 'INSERT INTO user
                VALUES (:username, :password)';

        $options = array('cost' => self::BCRYPT_COST);
        $derivedPassword = password_hash($password, PASSWORD_BCRYPT, $options);

        $statement = $this->prepare($sql);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':password', $derivedPassword);

        $statement->execute();

        $statement->close();
    }

}

?>