-- 데이터베이스 생성
CREATE DATABASE IF NOT EXISTS car_ledger;
USE car_ledger;

-- users 테이블 생성
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    UNIQUE (username) -- username 컬럼에 인덱스 추가
);

-- car_info 테이블 생성
CREATE TABLE IF NOT EXISTS car_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    car_number VARCHAR(255) NOT NULL,
    car_model VARCHAR(255) NOT NULL,
    mileage INT NOT NULL,
    FOREIGN KEY (username) REFERENCES users(username) ON DELETE CASCADE
);

-- repairs 테이블 생성
CREATE TABLE IF NOT EXISTS repairs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    car_number VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    cost INT NOT NULL,
    datetime DATE NOT NULL,
    FOREIGN KEY (car_number) REFERENCES car_info(car_number) ON DELETE CASCADE
);
