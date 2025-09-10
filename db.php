<?php
// db.php - Database connection
// Replace with your own Mercury credentials
$host = "feenix-mariadb.swin.edu.au";
$user = "yourusername";
$pass = "yourpassword";
$dbname = "yourusername_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
