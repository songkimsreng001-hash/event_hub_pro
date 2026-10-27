-- Event Management System Database Schema
-- Database: event_mgt

CREATE DATABASE IF NOT EXISTS `event_mgt` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `event_mgt`;

-- --------------------------------------------------------
-- Table structure for `tbl_users`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `passwd` VARCHAR(255) NOT NULL,
  `level` ENUM('admin', 'user') NOT NULL DEFAULT 'user',
  `photo` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `tbl_events`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_events` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT NOT NULL,
  `location` VARCHAR(150) NOT NULL,
  `event_date` DATETIME NOT NULL,
  `capacity` INT NOT NULL DEFAULT 50,
  `image` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `tbl_registrations`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_registrations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `event_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `note` TEXT DEFAULT NULL,
  `status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_reg_event` FOREIGN KEY (`event_id`) REFERENCES `tbl_events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_reg_user` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Seed Data: Default Admin and Standard User
-- Note: Default password is 'admin123' (both hashed and fallback plain text supported)
-- --------------------------------------------------------
INSERT INTO `tbl_users` (`id`, `name`, `username`, `passwd`, `level`, `photo`, `created_at`) 
VALUES 
(1, 'System Administrator', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', './assets/images/Profile_PNG.png', NOW()),
(2, 'Jane Doe', 'janedoe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', './assets/images/Profile_PNG.png', NOW())
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- Seed Data: Sample Events
INSERT INTO `tbl_events` (`id`, `title`, `description`, `location`, `event_date`, `capacity`, `image`, `created_at`)
VALUES
(1, 'Tech Innovations Summit 2026', 'Explore the latest breakthroughs in AI, web development, and cloud computing with industry-leading keynote speakers and hands-on workshops.', 'Grand Tech Hall, City Center', DATE_ADD(NOW(), INTERVAL 14 DAY), 100, './assets/images/loading.png', NOW()),
(2, 'Community Networking & Workshop', 'Join developers, designers, and tech enthusiasts for an evening of networking, lightning talks, collaborative problem solving, and refreshing drinks.', 'Co-Work Space Hub, Floor 3', DATE_ADD(NOW(), INTERVAL 21 DAY), 45, './assets/images/loading.png', NOW()),
(3, 'Design Systems & UI/UX Masterclass', 'A comprehensive masterclass on building modern, accessible, and scalable design systems with Tailwind CSS, Figma, and component architecture.', 'Creative Studio B', DATE_ADD(NOW(), INTERVAL 30 DAY), 30, './assets/images/loading.png', NOW())
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`);

-- Seed Data: Sample Registrations
INSERT INTO `tbl_registrations` (`id`, `event_id`, `user_id`, `note`, `status`, `created_at`)
VALUES
(1, 1, 2, 'Excited to attend the keynote and AI workshops!', 'approved', NOW()),
(2, 2, 2, 'Looking forward to meeting other developers.', 'pending', NOW())
ON DUPLICATE KEY UPDATE `status`=VALUES(`status`);

