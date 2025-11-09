-- Database creation
CREATE DATABASE IF NOT EXISTS student_events_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE student_events_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('student','admin') NOT NULL DEFAULT 'student',
  student_id VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Events table
CREATE TABLE IF NOT EXISTS events (
  event_id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  date DATE NOT NULL,
  time TIME NULL,
  venue VARCHAR(200) NOT NULL,
  organizer VARCHAR(150) DEFAULT 'Student Union',
  description TEXT,
  created_by INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Registrations table
CREATE TABLE IF NOT EXISTS registrations (
  reg_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  event_id INT NOT NULL,
  student_id VARCHAR(50),
  contact_no VARCHAR(20),
  registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reg_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  CONSTRAINT fk_reg_event FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE,
  CONSTRAINT uq_user_event UNIQUE (user_id, event_id)
);

-- Sample admin user (password: Admin123)
INSERT INTO users (name, email, password, role)
VALUES (
  'Administrator',
  'admin@example.com',
  '$2y$10$3i8t8gQ0mXbW3T0p6y3kNuey8w1jYh7w7nqM9bXG8kqkN4v7c7iN6',
  'admin'
)
ON DUPLICATE KEY UPDATE email = email;

-- Sample events
INSERT INTO events (title, date, time, venue, organizer, description)
VALUES
('AI Seminar', '2025-12-10', '10:00:00', 'Dept. of IT Auditorium', 'Dept. of IT',
 'Intro to LLMs and prompt engineering.'),
('Hackathon 2025', '2025-12-15', '09:00:00', 'Main Hall', 'CS Society',
 '24-hour coding challenge for student teams.')
ON DUPLICATE KEY UPDATE title = title;
