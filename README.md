# 차량 관리 시스템

## 개요

차량 관리 시스템은 차량 유지 보수 및 수리 기록을 효율적으로 관리하기 위한 웹 기반 애플리케이션입니다. 이 애플리케이션을 통해 사용자는 차량을 등록하고, 로그인하며, 차량 정보를 확인하고, 수리 내역을 기록하고, 유지 보수 이력을 추적할 수 있습니다.

## 기능

- **사용자 인증**: 안전한 사용자 등록 및 로그인 시스템.
- **대시보드**: 차량 정보와 수리 내역을 조회하고 관리할 수 있는 직관적인 대시보드.
- **수리 기록**: 차량의 수리 내역을 추가, 조회 및 업데이트할 수 있는 기능.
- **사용자 프로필 관리**: 사용자 정보(사용자명, 이메일, 비밀번호)를 업데이트할 수 있는 기능.
- **반응형 디자인**: 고급 자동차 브랜드에서 영감을 받은 깔끔하고 반응형 디자인으로 프리미엄 사용자 경험 제공.

## 시작하기

### 필수 조건

- PHP 7.x 이상
- MySQL 또는 MariaDB
- 웹 서버 (예: Apache 또는 Nginx)

## 설치 방법

1. 레포지토리 클론:
   ```sh
   git clone https://github.com/yourusername/car-ledger-management.git

2. 데이터베이스 설정:

db.php 파일을 열어 데이터베이스 연결 정보를 입력합니다.
아래 SQL 스크립트를 사용하여 데이터베이스 테이블을 생성합니다:

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS car_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    car_number VARCHAR(255) NOT NULL,
    car_model VARCHAR(255) NOT NULL,
    mileage INT NOT NULL
);

CREATE TABLE IF NOT EXISTS repairs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    car_number VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    cost INT NOT NULL,
    datetime DATE NOT NULL
);

3. 웹 서버 설정:

웹 서버의 루트 디렉토리에 클론한 레포지토리를 배포합니다.
Apache 또는 Nginx 설정 파일을 수정하여 해당 디렉토리를 가리키도록 설정합니다.

## 주요 파일 설명
index.php: 시스템 개요와 로그인/회원가입 링크가 있는 랜딩 페이지.
login.php: 사용자 로그인 페이지.
register.php: 사용자 등록 페이지.
dashboard.php: 사용자 대시보드로, 차량 정보와 수리 내역을 조회하고 추가할 수 있습니다.
mypage.php: 사용자 프로필 페이지로, 차량 정보 및 사용자 정보를 조회하고 수정할 수 있습니다.

## 기여하기
기여를 원하신다면, 포크를 뜨고 풀 리퀘스트를 제출해 주세요. 이 프로젝트를 개선하기 위한 모든 제안과 버그 수정을 환영합니다.

## 라이센스

이 프로젝트는 MIT 라이선스 하에 배포됩니다. 자세한 내용은 LICENSE 파일을 참조하세요.


