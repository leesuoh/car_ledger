<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>차량 관리 시스템</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* 주요 기능 박스 애니메이션 */
        .slide-in-left {
            animation: slideInLeft 2.5s ease-out forwards;
        }

        @keyframes slideInLeft {
            0% {
                transform: translateX(-100%);
                opacity: 0;
            }
            100% {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* 기타 스타일 */
        body {
            background-color: #f4f4f9;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
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
            width: 195px;
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
            font-size: 1.3em;
        }

        .hero {
            position: relative;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('car.PNG') no-repeat center center/cover;
            height: 70vh;
            color: #fff;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hero .text-box {
            max-width: 600px;
            padding: 20px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            opacity: 0;
            transform: translateX(-100%);
            transition: opacity 2.5s ease-out, transform 2.5s ease-out;
        }

        .start-btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 1.2em;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin-top: 20px;
        }

        .start-btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .features {
            padding: 50px 0;
            background-color: #fff;
            text-align: center;
        }

        .features h2 {
            margin-bottom: 40px;
        }

        .feature-box {
            padding: 20px;
            margin: 20px 0;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            opacity: 0;
            transform: translateX(-100%);
            transition: opacity 2.5s ease-out, transform 2.5s ease-out;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }

        .car-animation {
            width: 50%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .moving-car {
            position: relative;
            animation: moveCar 5s infinite linear;
        }

        @keyframes moveCar {
            0% { left: -50%; }
            50% { left: 50%; }
            100% { left: 100%; }
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
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="menu__item"><span class="menu__link">환영합니다 <?= htmlspecialchars($_SESSION['username']) ?>님!</span></li>
                    <li class="menu__item"><a href="mypage.php" class="menu__link">마이페이지</a></li>
                    <li class="menu__item"><a href="logout.php" class="menu__link">로그아웃</a></li>
                <?php else: ?>
                    <li class="menu__item"><a href="register.php" class="menu__link">회원가입</a></li>
                    <li class="menu__item"><a href="login.php" class="menu__link">로그인</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<main>
    <section class="hero">
        <div class="container">
            <div class="text-box">
                <h2>최신 기술을 활용하여 차량의 유지 보수 및 수리 기록을 쉽고 빠르게 관리하세요.</h2>
                <a href="dashboard.php" class="start-btn">시작하기</a>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <h2>주요 기능</h2>
            <div class="feature-box">
                <h3>차량 정보 관리</h3>
                <p>차량 등록, 차종, 주행거리 등의 정보를 체계적으로 관리합니다. 모든 차량 정보를 한 눈에 확인하고, 필요한 경우 쉽게 업데이트할 수 있습니다.</p>
            </div>
            <div class="feature-box">
                <h3>수리 내역 기록</h3>
                <p>차량의 수리 내역과 비용을 정확하게 기록하고 관리합니다. 수리 이력을 통해 차량 상태를 지속적으로 파악하고, 향후 수리 및 유지 보수 계획을 세울 수 있습니다.</p>
            </div>
            <div class="feature-box">
                <h3>연료 사용 기록</h3>
                <p>연료 사용 내역을 기록하여 연비를 계산하고, 연료비를 절감할 수 있는 방법을 모색합니다. 주유 기록을 통해 연비 향상 및 경제적인 운전을 도와줍니다.</p>
            </div>
            <div class="feature-box">
                <h3>정기 점검 알림</h3>
                <p>정기적인 차량 점검 일정을 관리하고, 점검 시기를 놓치지 않도록 알림 기능을 제공합니다. 차량의 최적 상태 유지를 위해 필요한 모든 정보를 제공합니다.</p>
            </div>
        </div>
    </section>
</main>

<footer>
    <div class="container">
        <p>&copy; 2024 차량 관리 시스템</p>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    
$(document).ready(function(){
    // Hero 텍스트 박스 애니메이션
    setTimeout(function(){
        $(".hero .text-box").css('opacity', '1').css('transform', 'translateX(0)');
    }, 500);

    // 기능 박스 스크롤 애니메이션
    $(window).on('scroll', function() {
        $('.feature-box').each(function() {
            var boxTop = $(this).offset().top;
            var windowBottom = $(window).scrollTop() + $(window).height();
            if (boxTop < windowBottom) {
                $(this).css('opacity', '1').css('transform', 'translateX(0)');
            }
        });
    });

    // 기능 박스 호버 효과
    $('.feature-box').hover(function() {
        $(this).css({
            'box-shadow': '1px 1px #53a7ea, 2px 2px #53a7ea, 3px 3px #53a7ea',
            'transform': 'translateX(-3px)'
        });
    }, function() {
        $(this).css({
            'box-shadow': 'none',
            'transform': 'translateX(0)'
        });
    });
});
</script>
</body>
</html>