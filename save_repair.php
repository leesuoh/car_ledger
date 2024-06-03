<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $car_number = $_POST['car_number'];
    $repairs = json_decode($_POST['repairs'], true);

    foreach ($repairs as $repair) {
        $description = $repair['description'];
        $cost = $repair['cost'];
        $repair_date = $repair['date'];
        saveRepair($conn, $car_number, $description, $cost, $repair_date);
    }

    echo json_encode(["status" => "success"]);
}

function saveRepair($conn, $car_number, $description, $cost, $repair_date) {
    $stmt = $conn->prepare("INSERT INTO repairs (car_number, description, cost, datetime) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $car_number, $description, $cost, $repair_date);
    $stmt->execute();
}
?>
