<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $repair_id = $_POST['repair_id'];

    $stmt = $conn->prepare("DELETE FROM repairs WHERE id = ?");
    $stmt->bind_param("i", $repair_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }

    $stmt->close();
}

$conn->close();
?>
