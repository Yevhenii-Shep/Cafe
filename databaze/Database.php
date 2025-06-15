<?php
// Connect to database
class Database {
    private $host = "localhost";
    private $dbname = "cafe_db";
    private $port = "3306";
    private $user = "root";
    private $password = "";
    private $pdo;
    public function __construct() {
        $this->connect();
    }
    private function connect() {
    try {
        $this->pdo = new PDO(
            "mysql:host={$this->host};dbname={$this->dbname};port={$this->port}",
            $this->user,
            $this->password,
            [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
        );

    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
        }
        }
        public function getConnection() {
        return $this->pdo;
        }
    }
