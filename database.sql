-- Timnah Schools Database Schema
-- Created: 2026-04-14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- Database: timnah_schools
CREATE DATABASE IF NOT EXISTS timnah_schools;
USE timnah_schools;

-- Table: users (Admin users)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'super_admin') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, email, password, full_name, role) VALUES
('admin', 'admin@timnahschools.edu', '$2y$10$liRrqdn8QEm5rJ5p2sUPZO/tT6/UFnLKt/Gf3VEBPsMKiV7e549YS', 'System Administrator', 'super_admin');

-- Table: settings (Site settings)
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO settings (setting_key, setting_value) VALUES
('site_name', 'Timnah Schools'),
('site_tagline', 'Excellence in Education'),
('site_email', 'info@timnahschools.edu'),
('site_phone', '+1 234 567 890'),
('site_address', '123 Education Lane, Learning City'),
('site_facebook', 'https://facebook.com/timnahschools'),
('site_twitter', 'https://twitter.com/timnahschools'),
('site_instagram', 'https://instagram.com/timnahschools'),
('hero_title', 'Welcome to Timnah Schools'),
('hero_subtitle', 'Nurturing Future Leaders Through Excellence in Education'),
('about_content', 'Timnah Schools is committed to providing quality education...');

-- Table: events
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    event_time VARCHAR(20),
    location VARCHAR(200),
    image VARCHAR(255),
    is_featured TINYINT(1) DEFAULT 0,
    status ENUM('active', 'past', 'cancelled') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table: staff
CREATE TABLE IF NOT EXISTS staff (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    department VARCHAR(100),
    qualification VARCHAR(200),
    email VARCHAR(100),
    phone VARCHAR(20),
    bio TEXT,
    image VARCHAR(255),
    is_teaching_staff TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Sample Staff Data
INSERT INTO staff (full_name, position, department, qualification, email, is_teaching_staff, status) VALUES
('Mrs. Sarah Johnson', 'Head Teacher', 'Administration', 'MA in Education, 15 years experience', 'sarah.johnson@timnah.edu', 0, 'active'),
('Mr. David Williams', 'Deputy Head', 'Administration', 'MEd Leadership, 12 years experience', 'david.williams@timnah.edu', 0, 'active'),
('Mrs. Emily Chen', 'Mathematics Teacher', 'Mathematics', 'BSc Mathematics, MEd', 'emily.chen@timnah.edu', 1, 'active'),
('Mr. Michael Brown', 'Science Teacher', 'Science', 'BSc Chemistry, PGCE', 'michael.brown@timnah.edu', 1, 'active'),
('Mrs. Jennifer Davis', 'English Teacher', 'English', 'BA English Literature, MA', 'jennifer.davis@timnah.edu', 1, 'active'),
('Mr. Robert Taylor', 'History Teacher', 'Social Studies', 'BA History, PGCE', 'robert.taylor@timnah.edu', 1, 'active'),
('Mrs. Lisa Anderson', 'Art Teacher', 'Arts', 'BFA Fine Arts, MEd', 'lisa.anderson@timnah.edu', 1, 'active'),
('Mr. James Wilson', 'Physical Education', 'Sports', 'BSc Sports Science', 'james.wilson@timnah.edu', 1, 'active'),
('Mrs. Maria Garcia', 'Spanish Teacher', 'Languages', 'BA Spanish, MA Linguistics', 'maria.garcia@timnah.edu', 1, 'active'),
('Mr. Thomas Lee', 'ICT Teacher', 'Computer Science', 'BSc Computer Science', 'thomas.lee@timnah.edu', 1, 'active'),
('Mrs. Amanda White', 'School Secretary', 'Administration', 'Diploma in Office Management', 'amanda.white@timnah.edu', 0, 'active'),
('Mr. Kevin Martin', 'Maintenance Supervisor', 'Facilities', 'Certificate in Building Maintenance', 'kevin.martin@timnah.edu', 0, 'active');

-- Table: facilities
CREATE TABLE IF NOT EXISTS facilities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    image VARCHAR(255),
    sort_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table: gallery
CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200),
    category VARCHAR(50),
    image VARCHAR(255) NOT NULL,
    description TEXT,
    sort_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: news
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    excerpt TEXT,
    image VARCHAR(255),
    author VARCHAR(100),
    is_featured TINYINT(1) DEFAULT 0,
    status ENUM('published', 'draft', 'archived') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table: vacancies
CREATE TABLE IF NOT EXISTS vacancies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    department VARCHAR(100),
    job_type VARCHAR(50),
    description TEXT,
    requirements TEXT,
    salary_range VARCHAR(100),
    closing_date DATE,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Sample Vacancies Data
INSERT INTO vacancies (title, department, job_type, description, requirements, salary_range, closing_date, is_active) VALUES
('Mathematics Teacher', 'Academic', 'Full-time', 'We are seeking an experienced Mathematics Teacher to join our academic team. The ideal candidate will be passionate about mathematics and dedicated to helping students achieve their full potential.', '• BSc in Mathematics or related field\n• Teaching certification preferred\n• Minimum 3 years teaching experience\n• Strong communication skills', '$45,000 - $60,000 annually', '2026-05-30', 1),
('Science Teacher (Chemistry)', 'Academic', 'Full-time', 'Join our Science department as a Chemistry Teacher. You will be responsible for teaching chemistry concepts to students and fostering a love for scientific inquiry.', '• BSc in Chemistry or related field\n• Teaching experience preferred\n• Ability to conduct laboratory experiments\n• Patient and supportive approach', '$45,000 - $55,000 annually', '2026-05-25', 1),
('English Language Teacher', 'Academic', 'Full-time', 'We are looking for an enthusiastic English Teacher to help students develop their language and literature skills. The candidate should have excellent communication abilities.', '• BA in English or Literature\n• Teaching certification\n• Experience with diverse learning styles\n• Creative teaching methods', '$42,000 - $52,000 annually', '2026-06-01', 1),
('Physical Education Instructor', 'Sports', 'Part-time', 'Looking for a passionate Physical Education Instructor to promote fitness and healthy lifestyle among students. Must be energetic and sports-oriented.', '• Certificate in Sports Science or related field\n• Experience in sports coaching\n• First Aid certification preferred\n• Good interpersonal skills', '$25,000 - $35,000 annually', '2026-06-15', 1),
('School Counselor', 'Student Services', 'Full-time', 'We need a qualified School Counselor to provide academic and emotional support to students. The role involves counseling, guidance, and parent liaison.', '• MA in Counseling or Psychology\n• School counseling experience\n• Strong listening and empathy skills\n• Confidentiality compliance', '$50,000 - $65,000 annually', '2026-05-20', 1),
('ICT Technician', 'IT Support', 'Full-time', 'Seeking an IT Technician to maintain school computer systems, networks, and provide technical support to staff and students.', '• Diploma in IT or Computer Science\n• Experience with school networks\n• Hardware and software troubleshooting\n• Customer service skills', '$35,000 - $45,000 annually', '2026-06-10', 1);

-- Table: students (from registration form)
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    date_of_birth DATE,
    grade VARCHAR(20),
    parent_name VARCHAR(100),
    parent_phone VARCHAR(20),
    address TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: messages (contact form submissions)
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200),
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: announcements
CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table: sliders (Homepage slider)
CREATE TABLE IF NOT EXISTS sliders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200),
    subtitle TEXT,
    image VARCHAR(255) NOT NULL,
    button_text VARCHAR(50),
    button_link VARCHAR(200),
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);