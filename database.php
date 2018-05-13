<?php

function getDatabaseConnection()
{
    $host = "us-cdbr-iron-east-04.cleardb.net";
    $username = "b0c32ef507eb8b";
    $password = "6835662b";
    $dbname="heroku_a60cc9c28ffb14a";

// Create connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    return $conn;
    
  }

?>