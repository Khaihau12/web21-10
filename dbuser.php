<?php
class dbuser {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "webthethao";
    protected $conn;
    
    // Constructor - Tự động kết nối
    public function __construct() {
        $this->connect();
    }
    
    private function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
        
        if ($this->conn->connect_error) {
            die("Kết nối thất bại: " . $this->conn->connect_error);
        }
        
        $this->conn->set_charset("utf8mb4");
    }
    
    // Getter for database connection
    public function getConnection() {
        return $this->conn;
    }
    
    

}
?>
