<?php
$servername = "localhost";
$username = "root";
$password = "yeomini1!";
$dbname = "car_ledger";
$port = 3306; // XAMPP MySQL 포트

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
    