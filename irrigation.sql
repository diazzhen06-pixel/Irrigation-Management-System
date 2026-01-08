-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2024 at 09:16 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ims_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `log_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `actual_start_time` datetime DEFAULT NULL,
  `actual_end_time` datetime DEFAULT NULL,
  `area_served` decimal(10,2) DEFAULT NULL,
  `issues_encountered` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `logged_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE `alerts` (
  `alert_id` int(11) NOT NULL,
  `alert_type` enum('MISSED_IRRIGATION','REPEATED_MISSED_SERVICE','WATER_SHORTAGE') NOT NULL,
  `related_id` int(11) DEFAULT NULL COMMENT 'Can be task_id, area_id, etc.',
  `message` text NOT NULL,
  `status` enum('NEW','ACKNOWLEDGED','RESOLVED') NOT NULL DEFAULT 'NEW',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `resolved_at` datetime DEFAULT NULL,
  `resolved_by` int(11) DEFAULT NULL,
  `resolution_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `audit_log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `target_resource` varchar(255) DEFAULT NULL,
  `target_id` int(11) DEFAULT NULL,
  `change_details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `irrigation_areas`
--

CREATE TABLE `irrigation_areas` (
  `area_id` int(11) NOT NULL,
  `area_name` varchar(255) NOT NULL,
  `barangay_coverage` text DEFAULT NULL,
  `estimated_hectares` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `area_type` enum('SYSTEM','MAIN_CANAL','LATERAL_CANAL','SERVICE_AREA') NOT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `irrigation_schedules`
--

CREATE TABLE `irrigation_schedules` (
  `schedule_id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `irrigation_schedule_history`
--

CREATE TABLE `irrigation_schedule_history` (
  `history_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `changed_by` int(11) NOT NULL,
  `change_description` text NOT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `irrigation_tasks`
--

CREATE TABLE `irrigation_tasks` (
  `task_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `assigned_staff_id` int(11) NOT NULL,
  `scheduled_date` date NOT NULL,
  `scheduled_start_time` time NOT NULL,
  `scheduled_end_time` time NOT NULL,
  `status` enum('DUE','IN-PROGRESS','COMPLETED','MISSED','CANCELLED') NOT NULL DEFAULT 'DUE',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_logs`
--

CREATE TABLE `sms_logs` (
  `log_id` int(11) NOT NULL,
  `recipient_number` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `status` enum('SENT','FAILED') NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `response` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_queue`
--

CREATE TABLE `sms_queue` (
  `queue_id` int(11) NOT NULL,
  `recipient_number` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `status` enum('PENDING','SENDING','FAILED') NOT NULL DEFAULT 'PENDING',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_attempt_at` datetime DEFAULT NULL,
  `retry_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role_id` int(11) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`alert_id`),
  ADD KEY `resolved_by` (`resolved_by`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`audit_log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `irrigation_areas`
--
ALTER TABLE `irrigation_areas`
  ADD PRIMARY KEY (`area_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `irrigation_schedules`
--
ALTER TABLE `irrigation_schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `area_id` (`area_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `irrigation_schedule_history`
--
ALTER TABLE `irrigation_schedule_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `schedule_id` (`schedule_id`),
  ADD KEY `changed_by` (`changed_by`);

--
-- Indexes for table `irrigation_tasks`
--
ALTER TABLE `irrigation_tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `schedule_id` (`schedule_id`),
  ADD KEY `area_id` (`area_id`),
  ADD KEY `assigned_staff_id` (`assigned_staff_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `sms_logs`
--
ALTER TABLE `sms_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `sms_queue`
--
ALTER TABLE `sms_queue`
  ADD PRIMARY KEY (`queue_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `alert_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `audit_log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `irrigation_areas`
--
ALTER TABLE `irrigation_areas`
  MODIFY `area_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `irrigation_schedules`
--
ALTER TABLE `irrigation_schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `irrigation_schedule_history`
--
ALTER TABLE `irrigation_schedule_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `irrigation_tasks`
--
ALTER TABLE `irrigation_tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_logs`
--
ALTER TABLE `sms_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_queue`
--
ALTER TABLE `sms_queue`
  MODIFY `queue_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `irrigation_tasks` (`task_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activity_logs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `alerts`
--
ALTER TABLE `alerts`
  ADD CONSTRAINT `alerts_ibfk_1` FOREIGN KEY (`resolved_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `irrigation_areas`
--
ALTER TABLE `irrigation_areas`
  ADD CONSTRAINT `irrigation_areas_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `irrigation_areas` (`area_id`) ON DELETE CASCADE;

--
-- Constraints for table `irrigation_schedules`
--
ALTER TABLE `irrigation_schedules`
  ADD CONSTRAINT `irrigation_schedules_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `irrigation_areas` (`area_id`),
  ADD CONSTRAINT `irrigation_schedules_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `irrigation_schedule_history`
--
ALTER TABLE `irrigation_schedule_history`
  ADD CONSTRAINT `irrigation_schedule_history_ibfk_1` FOREIGN KEY (`schedule_id`) REFERENCES `irrigation_schedules` (`schedule_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `irrigation_schedule_history_ibfk_2` FOREIGN KEY (`changed_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `irrigation_tasks`
--
ALTER TABLE `irrigation_tasks`
  ADD CONSTRAINT `irrigation_tasks_ibfk_1` FOREIGN KEY (`schedule_id`) REFERENCES `irrigation_schedules` (`schedule_id`),
  ADD CONSTRAINT `irrigation_tasks_ibfk_2` FOREIGN KEY (`area_id`) REFERENCES `irrigation_areas` (`area_id`),
  ADD CONSTRAINT `irrigation_tasks_ibfk_3` FOREIGN KEY (`assigned_staff_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
