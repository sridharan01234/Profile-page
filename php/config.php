<?php
class Database {
    private $host = "127.0.0.1";
    private $username = "root";
    private $password = "password";
    private $database = "TestDb";
    private $conn;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}

// Create an instance of the Database class
$database = new Database();

// Get the database connection
$conn = $database->getConnection();
