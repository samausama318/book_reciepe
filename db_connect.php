<?php
// Database connection settings
$host = "localhost";
$username = "root";
$password = ""; // Set your database password if needed
$database = "recipe_book";

// Create database connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set character set to utf8
mysqli_set_charset($conn, "utf8");
?>