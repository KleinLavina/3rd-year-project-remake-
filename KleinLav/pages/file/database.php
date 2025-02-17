<?php
class Database {
    public $connection;
    
    // Constructor to establish database connection
    public function __construct() {
        $this->connection = new mysqli("localhost", "root", "", "nyxify-registration");

        // Check connection
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    // Method to execute a query and return results
    public function getData($sql) {
        return $this->connection->query($sql);
    }

    // Method to prepare a SQL statement with parameters
    public function prepare($sql) {
        return $this->connection->prepare($sql);
    }

    // Method to close the database connection
    public function close() {
        $this->connection->close();
    }
}
?>
