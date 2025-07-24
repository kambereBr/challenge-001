<?php
namespace Core;

use PDO;
use PDOException;

class Database
{
    private $pdo;

    private function __construct(array $config)
    {
        try {
            if ($config['driver'] === 'sqlite') {
                $dsn = "sqlite:" . $config['database'];
                $this->pdo = new PDO($dsn);
            } else {
                $dsn = sprintf(
                    "%s:host=%s;dbname=%s;charset=%s",
                    $config['driver'],
                    $config['host'],
                    $config['database'],
                    $config['charset']
                );
                $this->pdo = new PDO($dsn, $config['username'], $config['password']);
            }
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
        }
    }
}