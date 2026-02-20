<?php
// Start session safely (only once)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "edigal_pharmacy");

// Check connection
if (!$conn) {
    die("Database connection failed");
}
?>
