<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];

// 수리 내역을 저장하는 함수
function saveRepair($conn, $car_number, $description, $cost, $repair_date) {
    $stmt = $conn->prepare("INSERT INTO repairs (car_number, description, cost, datetime) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $car_number, $description, $cost, $repair_date);
    $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalize_repair'])) {
    $car_number = $_POST['car_number_hidden'];
    $repairs = json_decode($_POST['repairs'], true);
    $total_cost = 0;

    foreach ($repairs as $repair) {
        saveRepair($conn, $car_number, $repair['description'], $repair['cost'], $repair['date']);
        $total_cost += $repair['cost'];
    }

    echo "<script>
            alert('저장되었습니다.');
            window.location.href = 'index.php';
          </script>";
    exit();
}

// 금액 형식 변환 함수
function formatCurrency($amount) {
    return number_format($amount) . '원';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>차계부 대시보드</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let repairs = [];

            $("#addRepair").click(function() {
                const description = $("#description").val();
                const cost = parseInt($("#cost").val());
                const date = $("#repair_date").val();

                if (description && cost && date) {
                    const index = repairs.length;
                    repairs.push({ description, cost, date });
                    $("#repair_list").append(
                        `<tr id="repair_${index}">
                            <td>${date}</td>
                            <td>${description}</td>
                            <td>${cost.toLocaleString()}원</td>
                            <td><button class="delete-repair" data-index="${index}">삭제</button></td>
                        </tr>`
                    );

                    $("#description").val('');
                    $("#cost").val('');
                    $("#repair_date").val('');
                } else {
                    alert("모든 필드를 입력해주세요.");
                }
            });

            $(document).on('click', '.delete-repair', function() {
                const index = $(this).data('index');
                repairs.splice(index, 1);
                $(`#repair_${index}`).remove();
            });

            $("#finalizeRepair").click(function() {
                const carNumber = $("#car_number_hidden").val();

                const totalCost = repairs.reduce((sum, repair) => sum + repair.cost, 0);
                const confirmMessage = `${repairs.map(repair => `${repair.cost.toLocaleString()}원`).join('과 ')}이 추가된 ${totalCost.toLocaleString()}원이 총 금액 맞습니까?`;

                if (confirm(confirmMessage)) {
                    $.ajax({
                        url: "dashboard.php",
                        type: "POST",
                        data: {
                            finalize_repair: true,
                            car_number_hidden: carNumber,
                            repairs: JSON.stringify(repairs)
                        },
                        success: function(response) {
                            alert('저장되었습니다.');
                            window.location.href = 'index.php';
                        },
                        error: function() {
                            alert('저장 중 오류가 발생했습니다.');
                        }
                    });
                }
            });

            function updateRepairList(carNumber) {
                $.get("get_repairs.php", { car_number: carNumber }, function(data) {
                    const repairs = JSON.parse(data);
                    $("#repair_list").empty();
                    repairs.forEach(repair => {
                        $("#repair_list").append(
                            `<tr>
                                <td>${repair.datetime}</td>
                                <td>${repair.description}</td>
                                <td>${repair.cost.toLocaleString()}원</td>
                            </tr>`
                        );
                    });
                });
            }

            const carNumber = $("#car_number").val();
            if (carNumber) {
                updateRepairList(carNumber);
            }
        });
    </script>
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

        .dashboard-container {
            max-width: 800px;
            margin: auto;
            background: #f9f9f9;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .dashboard-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .dashboard-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .dashboard-container label {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .dashboard-container input[type="text"],
        .dashboard-container input[type="date"],
        .dashboard-container input[type="number"],
        .dashboard-container textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .dashboard-container .submit-btn {
            padding: 10px 20px;
            border: none;
            background-color: #000;
            color: #fff;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .dashboard-container .submit-btn:hover {
            background-color: #333;
        }

        .small-input {
            width: 100px;
        }

        .large-textarea {
            width: 300px;
            height: 100px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f4f4f9;
            text-align: left;
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
            <form action="dashboard.php" method="GET">
                <label for="car_number">차번호 입력:</label>
                <input type="text" id="car_number" name="car_number" required>
                <button type="submit" class="submit-btn">조회</button>
            </form>
            
            <?php
            $car_number = $_GET['car_number'] ?? null;
            if ($car_number) {
                // 차량 정보 조회
                $stmt = $conn->prepare("SELECT * FROM car_info WHERE car_number = ? AND username = ?");
                $stmt->bind_param("ss", $car_number, $username);
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
                            echo "<tr>
                                    <td>" . htmlspecialchars($repair['datetime']) . "</td>
                                    <td>" . htmlspecialchars($repair['description']) . "</td>
                                    <td>" . formatCurrency(htmlspecialchars($repair['cost'])) . "</td>
                                  </tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>수리 내역이 없습니다.</p>";
                    }
                    ?>

                    <h3>수리 내역 입력</h3>
                    <form id="repairForm" method="POST">
                        <input type="hidden" name="car_number_hidden" id="car_number_hidden" value="<?= htmlspecialchars($car_number) ?>">
                        <label for="repair_date">수리 날짜:</label>
                        <input type="date" id="repair_date" name="repair_date" class="small-input" required><br><br>
                        <label for="description">수리 내역:</label>
                        <textarea id="description" name="description" class="large-textarea" placeholder="수리 내역을 입력하세요" required></textarea>
                        <label for="cost">수리 비용:</label>
                        <input type="number" id="cost" name="cost" class="small-input" required>
                        <br><br>
                        <button type="button" id="addRepair" class="submit-btn">수리 내역 추가</button>
                    </form>
                    
                    <h3>입력된 수리 내역</h3>
                    <table id="repair_list">
                        <tr><th>날짜</th><th>수리 내역</th><th>수리 가격</th><th>삭제</th></tr>
                    </table><br>
                    <button type="button" id="finalizeRepair" class="submit-btn">최종 수리 내역 저장</button>
                    <?php
                } else {
                    echo "<p>올바르지 않은 차량 번호입니다.</p>";
                }
            }
            ?>
        </div>
    </main>
</body>
</html>
