<?php

namespace Ontic\NoFraud\Model;

class User
{
    /** @var string */
    private $username;
    /** @var string */
    private $hashedPassword;

    /**
     * @param string $username
     * @param $hashedPassword
     */
    public function __construct($username, $hashedPassword)
    {
        $this->username = $username;
        $this->hashedPassword = $hashedPassword;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param $clearTextPassword
     */
    public function setPassword($clearTextPassword)
    {
        $this->hashedPassword = password_hash($clearTextPassword, PASSWORD_DEFAULT);
    }

    /**
     * @return string
     */
    public function getHashedPassword()
    {
        return $this->hashedPassword;
    }

    /**
     * @param $clearTextPassword
     * @return bool
     */
    public function verifyPassword($clearTextPassword)
    {
        return password_verify($clearTextPassword, $this->hashedPassword);
    }
}