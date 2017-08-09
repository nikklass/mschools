<?php
 
/**
 * Handling database connection
 *
 */
class DbConnect {
 
    private $conn;
 
    function __construct() {        
    }
 
    /**
     * Establishing database connection
     * @return database connection handler
     */
    function connect() {
        include_once dirname(__FILE__) . '/Config.php';
 
        // Connecting to mysql database
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
 
        // Check for database connection error
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
		
		$this->conn->query("SET timezone = '+0:00'");
		$this->conn->query("SET timezone = 'EAT'");
 
        // returing connection resource
        return $this->conn;
    }
}
?>