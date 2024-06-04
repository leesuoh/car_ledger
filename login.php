<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "모든 필드를 입력해주세요.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $username;
                header("Location: index.php"); // 로그인 후 홈 페이지로 이동
                exit();
            } else {
                $error = "비밀번호가 올바르지 않습니다.";
            }
        } else {
            $error = "존재하지 않는 사용자입니다.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인</title>
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
        <h2>로그인</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <label for="username">사용자명:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">비밀번호:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">로그인</button>
        </form>
        <div class="link">
            <p>계정이 없으신가요? <a href="register.php">회원가입</a></p>
            <p><a href="find_username.php">아이디 찾기</a> | <a href="reset_password.php">비밀번호 찾기</a></p>
        </div>
    </div>
</body>
</html>
