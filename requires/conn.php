<?php
// Database credentials
$host = 'localhost'; // Change to your host (e.g., IP or domain) if not localhost
$username = 'root'; // Replace with your MySQL username
$password = ''; // Replace with your MySQL password
$database = 'projektiweb'; // Name of your database

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Uncomment to verify connection (optional)
// echo "Connected successfully";
?>
