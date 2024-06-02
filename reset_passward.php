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
        mail($email, "비밀번호 재설정 요청", "비밀번호를 재설정하려면 다음 링크를 클릭하세요: $reset_link");

        echo "비밀번호 재설정 링크를 이메일로 보냈습니다.";
    } else {
        echo "입력한 이메일에 해당하는 계정이 없습니다.";
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
</head>
<body>
    <div class="form-container">
        <h2>비밀번호 재설정</h2>
        <form action="reset_password.php" method="post">
            <label for="email">이메일:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">비밀번호 재설정 링크 보내기</button>
        </form>
    </div>
</body>
</html>
