CREATE DATABASE IF NOT EXISTS car_ledger;
USE car_ledger;

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  UNIQUE (`username`),
  UNIQUE (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `car_info` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL,
  `car_number` VARCHAR(20) NOT NULL,
  `car_model` VARCHAR(50) NOT NULL,
  `mileage` INT NOT NULL,
  FOREIGN KEY (`username`) REFERENCES `users`(`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `repairs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `car_number` VARCHAR(20) NOT NULL,
  `description` TEXT NOT NULL,
  `cost` INT NOT NULL,
  `datetime` DATETIME NOT NULL,
  FOREIGN KEY (`car_number`) REFERENCES `car_info`(`car_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `password_resets` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(100) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`username`, `password`, `email`) VALUES
('testuser', '$2y$10$E6h3sS/V7/zyMPl/iHV5iOrqT/DX6NlS1O4gPjC.yOti5f1MGq4aW', 'testuser@example.com');  -- 비밀번호: 'password'

INSERT INTO `car_info` (`username`, `car_number`, `car_model`, `mileage`) VALUES
('testuser', '123ABC', 'Toyota Camry', 50000);

INSERT INTO `repairs` (`car_number`, `description`, `cost`, `datetime`) VALUES
('123ABC', 'Oil change', 30000, '2023-01-01 10:00:00');
