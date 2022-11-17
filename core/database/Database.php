<?php

namespace app\core\database;

class Database {

    public \PDO $pdo;

    public function __construct(array $config) {
        $host = $config['host'] ?? '';
        $username = $config['username'] ?? '';
        $password = $config['password'] ?? '';

        $this->pdo = new \PDO($host, $username, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); // Throws exception if it can't connect to the database
    }

    public function prepare($sql): bool|\PDOStatement {
        return $this->pdo->prepare($sql);
    }
}