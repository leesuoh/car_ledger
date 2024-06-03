<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register_car'])) {
        $username = $_POST['username'];
        $car_number = $_POST['new_car_number'];
        $car_model = $_POST['car_model'];
        $mileage = $_POST['mileage'];

        $stmt = $conn->prepare("INSERT INTO car_info (username, car_number, car_model, mileage) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $username, $car_number, $car_model, $mileage);

        if ($stmt->execute()) {
            echo "<script>
                    alert('차량이 성공적으로 등록되었습니다.');
                    window.location.href = 'mypage.php';
                  </script>";
        } else {
            echo "<script>alert('차량 등록에 실패했습니다. 다시 시도해주세요.');</script>";
        }
        $stmt->close();
    }

    if (isset($_POST['update_car_info'])) {
        $existing_car_number = $_POST['existing_car_number'];
        $new_car_number = $_POST['new_car_number'];
        $new_car_model = $_POST['new_car_model'];

        $stmt = $conn->prepare("UPDATE car_info SET car_number = ?, car_model = ? WHERE car_number = ?");
        $stmt->bind_param("sss", $new_car_number, $new_car_model, $existing_car_number);

        if ($stmt->execute()) {
            echo "<script>
                    alert('차량 정보가 성공적으로 수정되었습니다.');
                    window.location.href = 'mypage.php';
                  </script>";
        } else {
            echo "<script>alert('차량 정보 수정에 실패했습니다. 다시 시도해주세요.');</script>";
        }
        $stmt->close();
    }

    if (isset($_POST['update_user_info'])) {
        $current_username = $_POST['current_username'];
        $new_username = $_POST['new_username'];
        $new_email = $_POST['new_email'];
        $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE username = ?");
        $stmt->bind_param("ssss", $new_username, $new_email, $new_password, $current_username);

        if ($stmt->execute()) {
            $_SESSION['username'] = $new_username;
            echo "<script>
                    alert('회원 정보가 성공적으로 수정되었습니다.');
                    window.location.href = 'mypage.php';
                  </script>";
        } else {
            echo "<script>alert('회원 정보 수정에 실패했습니다. 다시 시도해주세요.');</script>";
        }
        $stmt->close();
    }
}

$conn->close();
?>
