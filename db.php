<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";
$port = 3307; // XAMPP MySQL 포트

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>