CREATE TABLE your_table (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    category VARCHAR(255) NOT NULL,
    dob DATE NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    file VARCHAR(255) DEFAULT NULL,
    status ENUM('pending', 'approved', 'rejected', 'canceled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO your_table (name, gender, category, dob, image, file, status)
VALUES
('John Doe', 'Male', 'Category A', '1990-01-01', NULL, NULL, 'pending'),
('Jane Smith', 'Female', 'Category B', '1995-05-05', NULL, NULL, 'approved'),
('Alice Johnson', 'Female', 'Category C', '2000-10-10', NULL, NULL, 'rejected');
