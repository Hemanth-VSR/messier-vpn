<?php

class Database{

    /**
    * static variable which holds the connection for database and use it widely in code.
    */
    public static $conn = NULL;
    

    /**
    * This will fetch database connection
    * @return type void
    */
    public static function DbConnection(){
        if (Database::$conn == NULL) {
            $servername = Database::get_config('mysql_server');
            $username = Database::get_config('username');
            $password = Database::get_config('password');
            $dbname = Database::get_config('db_name');
            
            $conn = new mysqli($servername, $username, $password, $dbname);
            
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
                return 0;
            }
            else {
                Database::$conn = $conn;
                return Database::$conn;
            }
        }
        else {
            return Database::$conn;
        }
    }


    /**
    * This function will fetch the database credentials from the config file
    * @return type string or boolean
    */
    private static function get_config($request = false){
        $value = file_get_contents("/var/www/config/bfvpn.config.json");     /* -- This file path may changes if you are working on different production environment -- */
        if ($decoded_ = json_decode($value , true)){
            if (isset($decoded_[$request])){
                return $decoded_[$request];
            }
            else {
                return "Requested Resource [$request] unavailable!\n";
            }
        }
        else {
            return false;
        }
    }
    
    
}