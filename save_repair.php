<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $car_number = $data['car_number'];
    $repairs = json_decode($data['repairs'], true);

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
