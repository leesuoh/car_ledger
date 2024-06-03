<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $token = bin2hex(random_bytes(50));
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();

        $reset_link = "http://yourdomain.com/reset_password_form.php?token=$token";

        // 메일 발송
        $to = $email;
        $subject = "비밀번호 재설정 요청";
        $message = "비밀번호를 재설정하려면 다음 링크를 클릭하세요: $reset_link";
        $headers = "From: no-reply@yourdomain.com";

        if (mail($to, $subject, $message, $headers)) {
            echo "<script>alert('비밀번호 재설정 링크를 이메일로 보냈습니다.');</script>";
        } else {
            echo "<script>alert('이메일 발송에 실패했습니다. 다시 시도해주세요.');</script>";
        }
    } else {
        echo "<script>alert('입력한 이메일에 해당하는 계정이 없습니다.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>비밀번호 재설정</title>
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
        <h2>비밀번호 재설정</h2>
        <form action="reset_password.php" method="post">
            <label for="email">이메일:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">비밀번호 재설정 링크 보내기</button>
        </form>
        <div class="link">
            <p><a href="login.php">로그인</a></p>
            <p><a href="find_username.php">아이디 찾기</a> | <a href="reset_password.php">비밀번호 찾기</a></p>
        </div>
    </div>
</body>
</html>
