<?php
// Connect PHP to the Sun Son Solar MySQL database.
$host = "localhost";
$username = "root";
$password = "";
$database = "sun_son_solar";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    error_log("Sun Son Solar database connection failed: " . $conn->connect_error);
    die("Could not connect to the database. Please check that MySQL is running and that the database setup is complete.");
}

$conn->set_charset("utf8mb4");
?>
