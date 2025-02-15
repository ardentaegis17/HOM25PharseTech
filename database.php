<?php
    $db_server = "localhost";
    $db_username = "root";
    $db_password = "";
    $db_name = "techcommunitydb";
    
    try {
        $conn = new mysqli(
            $db_server, 
            $db_username, 
            $db_password, 
            $db_name
        );
    } catch (mysqli_sql_exception $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>