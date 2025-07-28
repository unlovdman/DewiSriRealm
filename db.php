<?php
// Database configuration with environment variable support
$servername = $_ENV['DB_HOST'] ?? "localhost";
$username = $_ENV['DB_USER'] ?? "root";
$password = $_ENV['DB_PASS'] ?? "";
$dbname = $_ENV['DB_NAME'] ?? "village_services";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
