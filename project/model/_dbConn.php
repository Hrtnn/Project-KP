<?php

function create_connection()
{
    $db = new mysqli("localhost", "root", "", "projectkp");

    if($db->connect_error)
        die("Connection failed: " . $db->connect_error);
    
    return $db;
}

?>