CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    image VARCHAR(255),
    UNIQUE(username)
);

ALTER TABLE `users`
ADD COLUMN `otp` INT(6) NULL,
ADD COLUMN `is_active` TINYINT(1) DEFAULT 0;


ALTER TABLE `users`
ADD COLUMN `role` ENUM('siswa', 'guru', 'admin', 'kepala sekolah') NOT NULL DEFAULT 'siswa';
