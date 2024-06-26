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
$car_info = [];
while ($row = $result->fetch_assoc()) {
    $car_info[] = $row;
}
$stmt->close();

// 사용자의 이메일 정보 가져오기
$stmt = $conn->prepare("SELECT email FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$email_result = $stmt->get_result();
$user_email = $email_result->fetch_assoc()['email'];
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
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

        .car-info {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            background: #fff;
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
                    <?php foreach ($car_info as $car): ?>
                        <div class="car-info">
                            <p><strong>차량 번호:</strong> <?= htmlspecialchars($car['car_number']) ?></p>
                            <p><strong>차종:</strong> <?= htmlspecialchars($car['car_model']) ?></p>
                            <p><strong>주행거리:</strong> <?= htmlspecialchars($car['mileage']) ?> km</p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>차량 정보가 없습니다.</p>
                <?php endif; ?>
            </div>

            <!-- 신규 차량 등록 폼 -->
            <div id="registerCar" class="profile-section">
                <h3>신규 차량 등록</h3>
                <form action="update_user_info.php" method="POST">
                    <input type="hidden" name="username" value="<?= htmlspecialchars($username) ?>">
                    <label for="new_car_number">차량 번호:</label>
                    <input type="text" id="new_car_number" name="new_car_number" required>
                    <label for="car_model">차종:</label>
                    <input type="text" id="car_model" name="car_model" required>
                    <label for="mileage">주행거리:</label>
                    <input type="number" id="mileage" name="mileage" required>
                    <button type="submit" name="register_car">등록</button>
                </form>
            </div>

            <!-- 차량 정보 수정 폼 -->
            <div id="updateCar" class="profile-section">
                <h3>차량 정보 수정</h3>
                <?php if ($car_info): ?>
                    <?php foreach ($car_info as $car): ?>
                        <div class="car-info">
                            <p><strong>기존 차량 번호:</strong> <?= htmlspecialchars($car['car_number']) ?></p>
                            <form action="update_user_info.php" method="POST">
                                <input type="hidden" name="existing_car_number" value="<?= htmlspecialchars($car['car_number']) ?>">
                                <label for="new_car_number">새 차량 번호:</label>
                                <input type="text" id="new_car_number" name="new_car_number" required>
                                <label for="new_car_model">새 차종:</label>
                                <input type="text" id="new_car_model" name="new_car_model" required>
                                <button type="submit" name="update_car_info">수정</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>차량 정보가 없습니다.</p>
                <?php endif; ?>
            </div>

            <!-- 회원 정보 수정 폼 -->
            <div id="updateUser" class="profile-section">
                <h3>회원 정보 수정</h3>
                <form action="update_user_info.php" method="POST">
                    <p><strong>기존 사용자명:</strong> <?= htmlspecialchars($username) ?></p>
                    <label for="new_username">새 사용자명:</label>
                    <input type="text" id="new_username" name="new_username" required>
                    <p><strong>기존 이메일:</strong> <?= htmlspecialchars($user_email) ?></p>
                    <label for="new_email">새 이메일:</label>
                    <input type="email" id="new_email" name="new_email" required>
                    <label for="new_password">새 비밀번호:</label>
                    <input type="password" id="new_password" name="new_password" required>
                    <button type="submit" name="update_user_info">수정</button>
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
