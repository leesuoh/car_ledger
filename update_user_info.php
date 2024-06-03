<?php
session_start();
include 'db.php';

// 세션이 없으면 로그인 페이지로 리다이렉트
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// POST 요청을 받았을 때만 실행
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];

    // 회원 등록 - 신규 차량 등록 처리
    if (isset($_POST['register_car'])) {
        $car_number = $_POST['new_car_number'];
        $car_model = $_POST['car_model'];
        $mileage = $_POST['mileage'];

        // 데이터베이스에 새로운 차량 정보 추가
        $stmt = $conn->prepare("INSERT INTO car_info (username, car_number, car_model, mileage) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $username, $car_number, $car_model, $mileage);
        $stmt->execute();
        $stmt->close();

        // 추가한 차량 정보에 대한 성공 메시지 설정
        $car_register_success = true;
    }

    // 회원 등록 - 차량 정보 수정 처리
    if (isset($_POST['update_car_info'])) {
        $old_car_number = $_POST['existing_car_number'];
        $new_car_number = $_POST['new_car_number'];
        $new_car_model = $_POST['new_car_model'];

        // 기존 차량 정보를 새로운 정보로 업데이트
        $stmt = $conn->prepare("UPDATE car_info SET car_number = ?, car_model = ? WHERE username = ? AND car_number = ?");
        $stmt->bind_param("ssss", $new_car_number, $new_car_model, $username, $old_car_number);
        $stmt->execute();
        $stmt->close();

        // 업데이트된 차량 정보에 대한 성공 메시지 설정
        $car_update_success = true;
    }

    // 회원 정보 수정 처리
    if (isset($_POST['update_user_info'])) {
        $new_username = $_POST['new_username'];
        $new_email = $_POST['new_email'];
        $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

        // 사용자 정보를 새로운 정보로 업데이트
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE username = ?");
        $stmt->bind_param("ssss", $new_username, $new_email, $new_password, $username);
        $stmt->execute();
        $stmt->close();

        // 세션 업데이트
        $_SESSION['username'] = $new_username;

        // 업데이트된 사용자 정보에 대한 성공 메시지 설정
        $user_update_success = true;
    }
}

// 마이페이지로 리다이렉트
header('Location: mypage.php');
exit();
?>
