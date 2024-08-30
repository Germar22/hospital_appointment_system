-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 30, 2024 at 02:44 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hospital_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `appointment_date` datetime NOT NULL,
  `status` enum('Pending','Completed','Cancelled','Approved') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `patient_name` varchar(255) DEFAULT NULL,
  `doctor_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `appointment_date`, `status`, `created_at`, `updated_at`, `patient_name`, `doctor_name`) VALUES
(31, 15, 11, '2024-08-29 23:47:00', 'Completed', '2024-08-28 15:47:07', '2024-08-29 23:43:20', 'pat', 'doc'),
(32, 15, 11, '2024-09-05 06:46:00', 'Completed', '2024-08-28 22:46:29', '2024-08-29 23:43:22', 'pat', 'doc'),
(34, 15, 11, '2024-09-06 07:01:00', 'Completed', '2024-08-28 23:02:02', '2024-08-29 23:43:22', 'pat', 'doc'),
(35, 15, 11, '2024-09-04 07:04:00', 'Completed', '2024-08-28 23:04:10', '2024-08-29 23:43:23', 'pat', 'doc'),
(36, 15, 12, '2024-08-29 07:48:00', 'Approved', '2024-08-28 23:48:43', '2024-08-29 04:12:14', 'pat', 'doc2'),
(37, 17, 11, '2024-09-03 08:28:00', 'Completed', '2024-08-29 00:28:22', '2024-08-29 23:43:24', 'patpat', 'doc'),
(38, 15, 12, '2024-09-03 08:40:00', 'Approved', '2024-08-29 00:40:15', '2024-08-29 04:12:12', 'pat', 'doc2'),
(39, 15, 12, '2024-08-29 08:40:00', 'Approved', '2024-08-29 00:40:27', '2024-08-29 04:12:13', 'pat', 'doc2'),
(49, 15, 11, '2024-09-09 08:58:00', 'Completed', '2024-08-29 00:58:12', '2024-08-29 23:45:08', 'pat', 'doc'),
(58, 15, 11, '2024-08-29 11:54:00', 'Completed', '2024-08-29 03:54:19', '2024-08-29 23:48:00', 'pat', 'doc'),
(59, 15, 11, '2024-09-04 11:57:00', 'Approved', '2024-08-29 03:57:49', '2024-08-29 03:58:01', 'pat', 'doc'),
(60, 15, 11, '2024-09-04 12:01:00', 'Approved', '2024-08-29 04:01:33', '2024-08-29 04:01:44', 'pat', 'doc'),
(61, 15, 11, '2024-09-01 12:02:00', 'Approved', '2024-08-29 04:02:13', '2024-08-29 04:02:29', 'pat', 'doc'),
(62, 15, 11, '2024-09-02 12:03:00', 'Approved', '2024-08-29 04:03:34', '2024-08-29 04:03:46', 'pat', 'doc'),
(63, 15, 11, '2024-09-10 10:00:00', 'Approved', '2024-08-29 08:51:44', '2024-08-29 09:18:28', 'pat', 'doc'),
(64, 15, 12, '2024-09-11 11:00:00', 'Approved', '2024-08-29 08:51:44', '2024-08-29 09:04:32', 'pat', 'doc2'),
(65, 15, 11, '2024-09-12 12:00:00', 'Approved', '2024-08-29 08:51:44', '2024-08-29 09:18:29', 'pat', 'doc'),
(66, 15, 12, '2024-09-13 13:00:00', 'Approved', '2024-08-29 08:51:44', '2024-08-29 09:12:10', 'pat', 'doc2'),
(67, 15, 11, '2024-09-14 14:00:00', 'Approved', '2024-08-29 08:52:58', '2024-08-29 09:18:29', 'pat', 'doc'),
(68, 15, 12, '2024-09-15 15:00:00', 'Approved', '2024-08-29 08:52:58', '2024-08-29 09:17:16', 'pat', 'doc2'),
(69, 15, 11, '2024-09-16 16:00:00', 'Approved', '2024-08-29 08:52:58', '2024-08-29 09:18:30', 'pat', 'doc'),
(70, 15, 12, '2024-09-17 17:00:00', 'Approved', '2024-08-29 08:52:58', '2024-08-29 09:17:16', 'pat', 'doc2'),
(71, 15, 11, '2024-09-18 18:00:00', 'Approved', '2024-08-29 08:52:58', '2024-08-29 09:18:31', 'pat', 'doc'),
(72, 15, 12, '2024-09-19 19:00:00', 'Approved', '2024-08-29 08:52:58', '2024-08-29 09:17:17', 'pat', 'doc2'),
(73, 15, 11, '2024-09-20 20:00:00', 'Approved', '2024-08-29 08:52:58', '2024-08-29 09:21:56', 'pat', 'doc'),
(74, 15, 12, '2024-09-21 21:00:00', 'Approved', '2024-08-29 08:52:58', '2024-08-29 09:17:18', 'pat', 'doc2'),
(75, 15, 11, '2024-09-22 22:00:00', 'Approved', '2024-08-29 08:52:58', '2024-08-29 09:21:53', 'pat', 'doc'),
(76, 15, 12, '2024-09-23 23:00:00', 'Approved', '2024-08-29 08:52:58', '2024-08-29 09:17:18', 'pat', 'doc2'),
(77, 15, 12, '2024-09-14 16:59:00', 'Approved', '2024-08-29 08:59:40', '2024-08-29 09:17:02', 'pat', 'doc2'),
(78, 15, 12, '2024-09-14 16:59:00', 'Approved', '2024-08-29 09:00:22', '2024-08-29 09:12:14', 'pat', 'doc2'),
(79, 15, 12, '2024-10-17 17:00:00', 'Approved', '2024-08-29 09:00:56', '2024-08-29 09:17:28', 'pat', 'doc2'),
(80, 15, 11, '2024-09-01 20:23:00', 'Approved', '2024-08-29 09:23:20', '2024-08-29 09:23:38', 'pat', 'doc'),
(85, 15, 11, '2024-10-02 17:00:00', 'Approved', '2024-08-29 09:37:19', '2024-08-29 09:37:35', 'pat', 'doc'),
(86, 15, 12, '2024-10-03 18:00:00', 'Approved', '2024-08-29 09:37:19', '2024-08-29 09:40:46', 'pat', 'doc2'),
(97, 15, 11, '2024-09-11 09:00:00', 'Approved', '2024-08-29 10:00:59', '2024-08-29 10:01:29', NULL, NULL),
(98, 15, 11, '2024-09-12 10:00:00', 'Approved', '2024-08-29 10:00:59', '2024-08-29 10:01:29', NULL, NULL),
(99, 15, 11, '2024-09-13 11:00:00', 'Approved', '2024-08-29 10:00:59', '2024-08-29 10:01:28', NULL, NULL),
(100, 15, 12, '2024-09-11 10:00:00', 'Approved', '2024-08-29 10:01:07', '2024-08-29 10:01:43', NULL, NULL),
(101, 15, 12, '2024-09-12 11:00:00', 'Approved', '2024-08-29 10:01:07', '2024-08-29 10:01:42', NULL, NULL),
(102, 15, 12, '2024-09-13 12:00:00', 'Approved', '2024-08-29 10:01:07', '2024-08-29 10:01:42', NULL, NULL),
(103, 15, 13, '2024-09-06 18:09:00', 'Pending', '2024-08-29 10:09:58', '2024-08-29 10:09:58', 'pat', 'doc3'),
(105, 15, 13, '2024-09-10 10:00:00', 'Pending', '2024-08-29 10:15:31', '2024-08-29 10:15:31', NULL, NULL),
(106, 15, 13, '2024-09-11 11:00:00', 'Pending', '2024-08-29 10:15:31', '2024-08-29 10:15:31', NULL, NULL),
(107, 15, 13, '2024-09-12 12:00:00', 'Pending', '2024-08-29 10:15:31', '2024-08-29 10:15:31', NULL, NULL),
(108, 15, 13, '2024-09-13 13:00:00', 'Pending', '2024-08-29 10:15:31', '2024-08-29 10:15:31', NULL, NULL),
(109, 15, 13, '2024-09-14 14:00:00', 'Pending', '2024-08-29 10:15:31', '2024-08-29 10:15:31', NULL, NULL),
(110, 15, 13, '2024-09-15 15:00:00', 'Pending', '2024-08-29 10:15:31', '2024-08-29 10:15:31', NULL, NULL),
(111, 15, 13, '2024-09-16 16:00:00', 'Pending', '2024-08-29 10:15:31', '2024-08-29 10:15:31', NULL, NULL),
(112, 15, 13, '2024-09-17 17:00:00', 'Pending', '2024-08-29 10:15:31', '2024-08-29 10:15:31', NULL, NULL),
(113, 15, 13, '2024-09-18 18:00:00', 'Pending', '2024-08-29 10:15:31', '2024-08-29 10:15:31', NULL, NULL),
(114, 15, 13, '2024-09-19 19:00:00', 'Cancelled', '2024-08-29 10:15:31', '2024-08-29 23:26:36', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `availability_schedule` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `user_id`, `specialization`, `availability_schedule`, `created_at`, `updated_at`, `name`) VALUES
(11, 26, 'orthpedics', '11:45 to 15:45', '2024-08-28 15:45:31', '2024-08-28 15:45:31', 'doc'),
(12, 27, 'cardiology', '20:50 to 19:46', '2024-08-28 15:46:08', '2024-08-28 15:46:08', 'doc2'),
(13, 29, 'gastroenterologist', '10:09 to 13:12', '2024-08-29 10:09:36', '2024-08-29 10:09:36', 'doc3');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `user_id`, `name`, `email`) VALUES
(15, 24, 'pat', 'pat@gmail.com'),
(16, 25, 'pat2', 'pat2@gmail.com'),
(17, 28, 'patpat', 'pat3@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('admin','doctor','patient') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`, `created_at`, `updated_at`) VALUES
(23, 'admin', 'admin@gmail.com', '$2y$10$76BKDZcmD2xEMBXcLGaC/up1oi3OQ4mWF/fSxYsX9ErOo9ZsXrA7q', 'admin', '2024-08-28 15:44:24', '2024-08-28 15:44:24'),
(24, 'pat', 'pat@gmail.com', '$2y$10$.AWNTXtzlu9aAmaL87S9qeRK8vgg.tbjeZxq1jZxRtbtL93y6lnue', 'patient', '2024-08-28 15:44:46', '2024-08-28 15:44:46'),
(25, 'pat2', 'pat2@gmail.com', '$2y$10$lwp9JQABBaTG3zXtD3QVG.AvDlOptzP0vaO2KrSCSYyzZHgJwK52e', 'patient', '2024-08-28 15:45:00', '2024-08-28 15:45:00'),
(26, 'doc', 'doc@gmail.com', '$2y$10$cCt5eo8h5Xx0xYl6UDmwReIAZ0rnIJSoGHdI5lDk773ljfB4LTepO', 'doctor', '2024-08-28 15:45:31', '2024-08-28 15:45:31'),
(27, 'doc2', 'doc2@gmail.com', '$2y$10$2uDwLCw8bM0PCpzHvh8AAusASRz7xIsMyyl1ceCqQq5EDMTvNJ5C.', 'doctor', '2024-08-28 15:46:08', '2024-08-28 15:46:08'),
(28, 'patpat', 'pat3@gmail.com', '$2y$10$hqjYYmbhVFEWeDgUiUGLcetJ3A4SBmGxVoZ7IeBoPXSQos/rmbipy', 'patient', '2024-08-29 00:28:04', '2024-08-29 00:28:04'),
(29, 'doc3', 'doc3@gmail.com', '$2y$10$F8sF/jrkC2fky8u7rUPXGu4HjD/L9U7etOs/IC9/vtAONKz.joAEa', 'doctor', '2024-08-29 10:09:36', '2024-08-29 10:09:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
