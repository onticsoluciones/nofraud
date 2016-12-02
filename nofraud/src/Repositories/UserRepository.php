<?php

namespace Ontic\NoFraud\Repositories;

use Ontic\NoFraud\Model\User;
use Ontic\NoFraud\Utils;
use PDO;

class UserRepository
{
    /** @var PDO */
    private $connection;

    public function __construct()
    {
        $this->connection = Utils::openConnection();
    }

    /**
     * @param $username
     * @param $password
     * @return null|User
     */
    public function load($username, $password)
    {
        // Get the (encrypted) password for the username
        $sql = 'SELECT password FROM users where username = :username;';
        $parameters = [
            'username' => $username
        ];

        $statement = $this->connection->prepare($sql);
        $statement->execute($parameters);
        $row = $statement->fetch();
        if($row === false)
        {
            // User doesn't exist, bail out
            return null;
        }

        // Verify that the supplied password matches the stored
        // password
        $user = new User($username, $row['password']);
        if(!$user->verifyPassword($password))
        {
            // Incorrect password
            return null;
        }

        // Everything went okay...
        return $user;
    }

    /**
     * @param string $username
     * @param string $password
     * @return User
     */
    public function save($username, $password)
    {
        $user = new User($username, null);
        $user->setPassword($password);

        $sql = 'INSERT INTO users(username, password) VALUES (:username, :password);';
        $parameters = [
            'username' => $username,
            'password' => $user->getHashedPassword()
        ];
        $statement = $this->connection->prepare($sql);
        $statement->execute($parameters);

        return $user;
    }
}