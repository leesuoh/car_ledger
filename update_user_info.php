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
        $new_username = $_POST['new_username'];
        $new_email = $_POST['new_email'];
        $new_password = $_POST['new_password'];

        // 유효한 이메일 형식 확인
        if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('잘못된 이메일 형식입니다.'); window.history.back();</script>";
            exit();
        }

        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // 사용자 정보 업데이트
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE username = ?");
        $stmt->bind_param("ssss", $new_username, $new_email, $hashed_password, $_SESSION['username']);

        if ($stmt->execute()) {
            // 세션 업데이트
            $_SESSION['username'] = $new_username;

            echo "<script>
                    alert('회원 정보가 성공적으로 수정되었습니다.');
                    window.location.href = 'index.php';
                  </script>";
        } else {
            echo "<script>alert('회원 정보 수정에 실패했습니다. 다시 시도해주세요.');</script>";
        }
        $stmt->close();
    }
}

$conn->close();
?>


