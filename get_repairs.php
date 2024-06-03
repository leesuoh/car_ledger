<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['car_number'])) {
    $car_number = $_GET['car_number'];
    $stmt = $conn->prepare("SELECT * FROM repairs WHERE car_number = ? ORDER BY datetime ASC");
    $stmt->bind_param("s", $car_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $repairs = [];
    while ($row = $result->fetch_assoc()) {
        $repairs[] = $row;
    }
    echo json_encode($repairs);
}
?>
