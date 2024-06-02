<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// 수리 내역을 저장하는 함수
function saveRepair($conn, $car_number, $description, $cost, $repair_date) {
    $stmt = $conn->prepare("INSERT INTO repairs (car_number, description, cost, datetime) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        die('prepare() failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("ssis", $car_number, $description, $cost, $repair_date);
    if ($stmt->execute() === false) {
        die('execute() failed: ' . htmlspecialchars($stmt->error));
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['finalize_repair'])) {
        $car_number = $_POST['car_number'];
        $description = $_POST['description'];
        $cost = $_POST['cost'];
        $repair_date = $_POST['repair_date'];

        // 날짜 형식 유효성 검사
        if (DateTime::createFromFormat('Y-m-d', $repair_date) === false) {
            echo "<script>alert('유효한 날짜 형식을 입력하세요.'); window.history.back();</script>";
            exit();
        }

        saveRepair($conn, $car_number, $description, $cost, $repair_date);

        echo "<script>alert('최종 저장되었습니다.'); window.location.href = 'dashboard.php?car_number=" . $car_number . "';</script>";
        exit();
    }
}

// 금액 형식 변환 함수
function formatCurrency($amount) {
    return number_format($amount) . '원';
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>차계부 대시보드</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        .form-group textarea {
            resize: vertical;
        }

        .submit-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            text-align: center;
        }

        .submit-btn:hover {
            background-color: #0056b3;
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
        <div class="dashboard-container">
            <h2>차계부 대시보드</h2>
            <form action="dashboard.php" method="POST">
                <label for="car_number">차번호 입력:</label>
                <input type="text" id="car_number" name="car_number" required>
                <button type="submit" class="submit-btn">조회</button>
            </form>
            
            <?php
            $car_number = $_POST['car_number'] ?? $_GET['car_number'] ?? null;
            if ($car_number) {
                // 차량 정보 조회
                $stmt = $conn->prepare("SELECT * FROM car_info WHERE car_number = ?");
                $stmt->bind_param("s", $car_number);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $car_data = $result->fetch_assoc();
                    ?>
                    <h3>차량 정보</h3>
                    <p><strong>회원 아이디:</strong> <?= htmlspecialchars($_SESSION['username']) ?></p>
                    <p><strong>차번호:</strong> <?= htmlspecialchars($car_data['car_number']) ?></p>
                    <p><strong>차종:</strong> <?= htmlspecialchars($car_data['car_model']) ?></p>
                    <p><strong>주행거리:</strong> <?= htmlspecialchars($car_data['mileage']) ?> km</p>
                    
                    <h3>기존 수리 내역</h3>
                    <?php
                    // 수리 내역 조회
                    $stmt = $conn->prepare("SELECT * FROM repairs WHERE car_number = ? ORDER BY datetime ASC");
                    $stmt->bind_param("s", $car_number);
                    $stmt->execute();
                    $repair_result = $stmt->get_result();
                    if ($repair_result->num_rows > 0) {
                        echo "<table><tr><th>날짜</th><th>수리 내역</th><th>수리 가격</th></tr>";
                        while ($repair = $repair_result->fetch_assoc()) {
                            echo "<tr><td>" . htmlspecialchars($repair['datetime']) . "</td><td>" . htmlspecialchars($repair['description']) . "</td><td>" . formatCurrency(htmlspecialchars($repair['cost'])) . "</td></tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>수리 내역이 없습니다.</p>";
                    }
                    ?>

                    <h3>수리 내역 입력</h3>
                    <form method="POST">
                        <input type="hidden" name="car_number" id="car_number" value="<?= htmlspecialchars($car_number) ?>">
                        <div class="form-group">
                            <label for="repair_date">수리 날짜:</label>
                            <input type="date" id="repair_date" name="repair_date" class="small-input" required>
                        </div>
                        <div class="form-group">
                            <label for="description">수리 내역:</label>
                            <textarea id="description" name="description" class="large-textarea" placeholder="수리 내역을 입력하세요" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="cost">수리 비용:</label>
                            <input type="number" id="cost" name="cost" class="small-input" required>
                        </div>
                        <button type="submit" name="finalize_repair" class="submit-btn">최종 수리 내역 저장</button>
                    </form>
                    <?php
                } else {
                    echo "<p>차량 정보를 찾을 수 없습니다.</p>";
                }
            }
            ?>
        </div>
    </main>
</body>
</html>