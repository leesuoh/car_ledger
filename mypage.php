<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];

// 사용자의 차량 정보 가져오기
$stmt = $conn->prepare("SELECT * FROM car_info WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$car_info = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.body.classList.add('fade-in');

    // 페이지를 떠날 때 페이드 아웃 효과 적용
    document.querySelectorAll('a').forEach(function(link) {
        link.addEventListener('click', function(e) {
            if (link.getAttribute('href') !== '#') {
                e.preventDefault();
                document.body.classList.add('fade-out');
                setTimeout(function() {
                    window.location.href = link.href;
                }, 3000); // 애니메이션 지속 시간 (3초)에 맞춰 조정
            }
        });
    });
});
</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>마이페이지</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #f4f4f9;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #000;
            color: #fff;
            padding: 10px 0;
        }

        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }

        .logo {
            float: left;
            width: 150px;
        }

        .navigation {
            float: right;
        }

        .navigation .menu {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .navigation .menu .menu__item {
            display: inline;
            margin-left: 15px;
        }

        .navigation .menu .menu__link {
            color: #fff;
            text-decoration: none;
            font-size: 1.2em;
        }

        main {
            padding: 50px 0;
            background: #fff;
            text-align: center;
        }

        .profile-container {
            max-width: 800px;
            margin: auto;
            background: #f9f9f9;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .profile-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .profile-buttons {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }

        .profile-buttons button {
            padding: 15px 30px;
            background-color: #000;
            color: #fff;
            border: 2px solid #000;
            border-radius: 10px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
        }

        .profile-buttons button:hover {
            background-color: #fff;
            color: #000;
        }

        .profile-section {
            display: none;
            margin-top: 20px;
            text-align: left;
        }

        .profile-section h3 {
            margin-bottom: 15px;
        }

        .profile-section label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .profile-section input {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .profile-section button {
            display: block;
            width: 100%;
            padding: 10px;
            border: none;
            background-color: #000;
            color: #fff;
            font-size: 1em;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .profile-section button:hover {
            background-color: #333;
        }

        .success {
            color: green;
            margin-top: 10px;
        }

        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <a href="index.php">
                <img src="Engine.png" alt="차량 관리 시스템 로고" class="logo">
            </a>
            <nav class="navigation">
                <ul class="menu">
                    <li class="menu__item"><a href="index.php" class="menu__link">홈</a></li>
                    <li class="menu__item"><a href="logout.php" class="menu__link">로그아웃</a></li>
                    <li class="menu__item"><a href="mypage.php" class="menu__link">마이페이지</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main>
        <div class="profile-container">
            <h2>마이페이지</h2>
            <div class="profile-buttons">
                <button onclick="showSection('carInfo')">내 차량 정보 보기</button>
                <button onclick="showSection('registerCar')">신규 차량 등록</button>
                <button onclick="showSection('updateCar')">차량 정보 수정</button>
                <button onclick="showSection('updateUser')">회원 정보 수정</button>
            </div>

            <!-- 내 차량 정보 표시 -->
            <div id="carInfo" class="profile-section">
                <h3>내 차량 정보</h3>
                <?php if ($car_info): ?>
                    <p>차량 번호: <?= htmlspecialchars($car_info['car_number']) ?></p>
                    <p>차종: <?= htmlspecialchars($car_info['car_model']) ?></p>
                    <p>주행거리: <?= htmlspecialchars($car_info['mileage']) ?> km</p>
                <?php else: ?>
                    <p>차량 정보가 없습니다.</p>
                <?php endif; ?>
            </div>

            <!-- 신규 차량 등록 폼 -->
            <div id="registerCar" class="profile-section">
                <h3>신규 차량 등록</h3>
                <form action="update_user_info.php" method="POST">
                    <label for="new_car_number">차량 번호:</label>
                    <input type="text" id="new_car_number" name="new_car_number" required>
                    <label for="car_model">차종:</label>
                    <input type="text" id="car_model" name="car_model" required>
                    <label for="mileage">주행거리:</label>
                    <input type="number" id="mileage" name="mileage" required>
                    <button type="submit" name="register_car">등록</button>
                    <?php if (isset($car_register_success)): ?>
                        <p class="success">차량이 성공적으로 등록되었습니다.</p>
                    <?php endif; ?>
                </form>
            </div>

            <!-- 차량 정보 수정 폼 -->
            <div id="updateCar" class="profile-section">
                <h3>차량 정보 수정</h3>
                <form action="update_user_info.php" method="POST">
                    <label for="existing_car_number">기존 차량 번호:</label>
                    <input type="text" id="existing_car_number" name="existing_car_number" required>
                    <label for="new_car_number">새 차량 번호:</label>
                    <input type="text" id="new_car_number" name="new_car_number" required>
                    <label for="new_car_model">새 차종:</label>
                    <input type="text" id="new_car_model" name="new_car_model" required>
                    <button type="submit" name="update_car_info">수정</button>
                    <?php if (isset($car_update_success)): ?>
                        <p class="success">차량 정보가 성공적으로 수정되었습니다.</p>
                    <?php endif; ?>
                </form>
            </div>

            <!-- 회원 정보 수정 폼 -->
            <div id="updateUser" class="profile-section">
                <h3>회원 정보 수정</h3>
                <form action="update_user_info.php" method="POST">
                    <label for="new_username">새 사용자명:</label>
                    <input type="text" id="new_username" name="new_username" required>
                    <label for="new_email">새 이메일:</label>
                    <input type="email" id="new_email" name="new_email" required>
                    <label for="new_password">새 비밀번호:</label>
                    <input type="password" id="new_password" name="new_password" required>
                    <button type="submit" name="update_user_info">수정</button>
                    <?php if (isset($user_update_success)): ?>
                        <p class="success">회원 정보가 성공적으로 수정되었습니다.</p>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </main>

    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.profile-section').forEach(section => {
                section.style.display = 'none';
            });
            document.getElementById(sectionId).style.display = 'block';
        }
    </script>
</body>
</html>
