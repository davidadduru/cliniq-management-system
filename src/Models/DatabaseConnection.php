<?php

namespace App\Models;

use PDO;
use PDOException;

class DatabaseConnection
{
    private $db_type;
    private $host;
    private $port;
    private $database;
    private $username;
    private $password;

    public function __construct($db_type, $host, $port, $database, $username, $password)
    {
        $this->db_type = $db_type;
        $this->host = $host;
        $this->port = $port;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
    }

    public function connect()
    {
        try {
            $dsn = "{$this->db_type}:host={$this->host};port={$this->port};dbname={$this->database}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            return new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
}
