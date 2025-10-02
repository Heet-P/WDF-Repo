-- Create database
CREATE DATABASE IF NOT EXISTS student_db;
USE student_db;

-- Create students table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15),
    course VARCHAR(100),
    enrollment_date DATE DEFAULT CURRENT_DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO students (first_name, last_name, email, phone, course) VALUES
('John', 'Doe', 'john.doe@email.com', '1234567890', 'Computer Science'),
('Jane', 'Smith', 'jane.smith@email.com', '0987654321', 'Information Technology'),
('Mike', 'Johnson', 'mike.johnson@email.com', '5555555555', 'Web Development');
