<?php

// Database_connection.php 

class DatabaseConnection
{
    function connect()
    {
        $connect = new PDO("mysql:host=mysql; dbname=chatApplication", "devuser", "devpass");
        return $connect;
    }
}


?>