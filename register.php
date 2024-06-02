<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    if (empty($username) || empty($password) || empty($email)) {
        echo "모든 필드를 입력해주세요.";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // 중복 사용자명 확인
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "이미 사용 중인 사용자명입니다.";
    } else {
        // 이메일을 포함하여 데이터를 저장
        $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $email);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error = "회원가입에 실패했습니다. 다시 시도해주세요.";
        }
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원가입</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #f4f4f9;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .form-container {
            width: 100%;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .form-container h2 {
            margin-bottom: 20px;
            font-size: 2em;
            color: #333;
            text-align: center;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
        }

        .form-container label {
            margin-bottom: 10px;
            font-weight: bold;
        }

        .form-container input {
            margin-bottom: 20px;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-container button {
            padding: 10px;
            font-size: 1.2em;
            color: #fff;
            background-color: #000;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #555;
        }

        .form-container .error {
            color: red;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-container .link {
            margin-top: 10px;
            text-align: center;
        }

        .form-container .link a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .form-container .link a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>회원가입</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="register.php" method="post">
            <label for="username">사용자명:</label>
            <input type="text" id="username" name="username" required><br><br>
            <label for="password">비밀번호:</label>
            <input type="password" id="password" name="password" required><br><br>
            <label for="email">이메일:</label>
            <input type="email" id="email" name="email" required><br><br>
            <button type="submit">회원가입</button>
        </form>
        <div class="link">
            <p>이미 계정이 있으신가요? <a href="login.php">로그인</a></p>
        </div>
    </div>
</body>
</html>
