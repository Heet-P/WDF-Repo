-- Event Management System Database
-- Create database
CREATE DATABASE IF NOT EXISTS event_management;
USE event_management;

-- Create admins table for authentication
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create events table
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    event_time TIME NOT NULL,
    location VARCHAR(255) NOT NULL,
    status ENUM('open', 'closed') DEFAULT 'open',
    max_participants INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO admins (username, password, email, full_name) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@eventmanagement.com', 'System Administrator'),
('manager', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'manager@eventmanagement.com', 'Event Manager');

INSERT INTO events (title, description, event_date, event_time, location, status, max_participants) VALUES
('Web Development Workshop', 'Learn the basics of HTML, CSS, and JavaScript', '2025-10-15', '10:00:00', 'Conference Room A', 'open', 30),
('Database Design Seminar', 'Advanced database design principles and best practices', '2025-10-20', '14:00:00', 'Auditorium', 'open', 50),
('PHP Programming Bootcamp', 'Intensive PHP programming course for beginners', '2025-11-01', '09:00:00', 'Computer Lab 1', 'closed', 25),
('Project Management Workshop', 'Learn agile project management methodologies', '2025-11-10', '13:30:00', 'Meeting Room B', 'open', 20);
