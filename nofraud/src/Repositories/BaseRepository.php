<?php

namespace Ontic\NoFraud\Repositories;

use Ontic\NoFraud\Utils;

abstract class BaseRepository
{
    /** @var \PDO */
    private $connection;

    public function __construct()
    {
        $this->connection = Utils::openConnection();
    }

    /**
     * @return \PDO
     */
    protected function getConnection()
    {
        return $this->connection;
    }
}