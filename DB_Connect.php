<?php
 
class DB_Connect {
 
    // constructor
    function __construct() {
    }
 
    // destructor
    function __destruct() {
    }
 
    // Connecting to database
    public function connect() {
        require_once 'config.php';

        // connecting to mysql
        $conn = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
        
        // selecting database
        mysql_select_db(DB_DATABASE);
 
        // return database handler
        return $conn;
    }
 
    // Closing database connection
    public function close() {
        mysql_close();
    }
 
}
 
?>