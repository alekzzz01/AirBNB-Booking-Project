<?php

// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "group_3";

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
} 
?>
