<?php

namespace thecodeholic\phpmvc\database;

use PDO;
use PDOStatement;
use thecodeholic\phpmvc\Application;

class Database {
    public PDO $pdo;

    public function __construct($config = []) {
        $host = $config['host'] ?? '';
        $username = $config['username'] ?? '';
        $password = $config['password'] ?? '';

        $this->pdo = new PDO($host, $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }


    public function prepare($sql): PDOStatement {
        return $this->pdo->prepare($sql);
    }
}