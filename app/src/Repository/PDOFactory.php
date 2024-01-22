<?php

namespace App\Repository;

class PDOFactory
{
    public function createPDO(string $dsn, string $username, string $password, array $options = []): \PDO
    {
        return new \PDO($dsn, $username, $password, $options);
    }
}
