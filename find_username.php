<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT username FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $username = $user['username'];
        echo "아이디는: $username";
    } else {
        echo "입력한 이메일에 해당하는 아이디가 없습니다.";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>아이디 찾기</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2>아이디 찾기</h2>
        <form action="find_username.php" method="post">
            <label for="email">이메일:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">아이디 찾기</button>
        </form>
    </div>
</body>
</html>
