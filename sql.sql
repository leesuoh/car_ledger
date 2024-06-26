-- 데이터베이스 및 테이블 생성
CREATE DATABASE IF NOT EXISTS car_ledger;

-- car_ledger 데이터베이스 사용
USE car_ledger;
describe repairs;

-- users 테이블 생성
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL
);

-- car_info 테이블 생성
CREATE TABLE IF NOT EXISTS car_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    car_number VARCHAR(255) NOT NULL,
    car_model VARCHAR(255) NOT NULL,
    mileage INT NOT NULL,
    FOREIGN KEY (username) REFERENCES users(username)
);
ALTER TABLE car_info ADD INDEX (car_number); -- 이 인덱스는 데이터베이스가 car_number 열에서 데이터를 더 빠르게 검색하고 조회할 수 있도록 도와준다.


-- repairs 테이블 생성
CREATE TABLE IF NOT EXISTS repairs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    car_number VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    cost INT NOT NULL,
    datetime DATE NOT NULL,
    FOREIGN KEY (car_number) REFERENCES car_info(car_number)
);

SELECT * FROM users;
SELECT * FROM car_info;
SELECT * FROM repairs;

DELETE FROM users;
DELETE FROM car_info;
DELETE FROM repairs;



