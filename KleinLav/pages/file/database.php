<?php
class Database {
    public $connection;
    public function__construct() {
            $this->connection = new mysqli ("localhost", "root","","nyxify-registration");
    }
    public function getData($sql) {
        return $this->connection->query($sql);
    }
}
