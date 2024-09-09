-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2024 at 10:21 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.3.11

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
(73, 15, 11, '2024-09-20 20:00:00', 'Completed', '2024-08-29 08:52:58', '2024-08-30 14:22:55', 'pat', 'doc'),
(74, 15, 12, '2024-09-21 21:00:00', 'Approved', '2024-08-29 08:52:58', '2024-08-29 09:17:18', 'pat', 'doc2'),
(75, 15, 11, '2024-09-22 22:00:00', 'Completed', '2024-08-29 08:52:58', '2024-08-30 14:22:55', 'pat', 'doc'),
(76, 15, 12, '2024-09-23 23:00:00', 'Approved', '2024-08-29 08:52:58', '2024-08-29 09:17:18', 'pat', 'doc2'),
(77, 15, 12, '2024-09-14 16:59:00', 'Approved', '2024-08-29 08:59:40', '2024-08-29 09:17:02', 'pat', 'doc2'),
(78, 15, 12, '2024-09-14 16:59:00', 'Approved', '2024-08-29 09:00:22', '2024-08-29 09:12:14', 'pat', 'doc2'),
(79, 15, 12, '2024-10-17 17:00:00', 'Approved', '2024-08-29 09:00:56', '2024-08-29 09:17:28', 'pat', 'doc2'),
(80, 15, 11, '2024-09-01 20:23:00', 'Completed', '2024-08-29 09:23:20', '2024-08-30 14:22:54', 'pat', 'doc'),
(85, 15, 11, '2024-10-02 17:00:00', 'Completed', '2024-08-29 09:37:19', '2024-08-30 14:22:54', 'pat', 'doc'),
(86, 15, 12, '2024-10-03 18:00:00', 'Approved', '2024-08-29 09:37:19', '2024-08-29 09:40:46', 'pat', 'doc2'),
(97, 15, 11, '2024-09-11 09:00:00', 'Completed', '2024-08-29 10:00:59', '2024-08-30 14:22:52', NULL, NULL),
(98, 15, 11, '2024-09-12 10:00:00', 'Completed', '2024-08-29 10:00:59', '2024-08-30 14:21:31', NULL, NULL),
(99, 15, 11, '2024-09-13 11:00:00', 'Completed', '2024-08-29 10:00:59', '2024-08-30 14:21:30', NULL, NULL),
(100, 15, 12, '2024-09-11 10:00:00', 'Approved', '2024-08-29 10:01:07', '2024-08-29 10:01:43', NULL, NULL),
(101, 15, 12, '2024-09-12 11:00:00', 'Approved', '2024-08-29 10:01:07', '2024-08-29 10:01:42', NULL, NULL),
(102, 15, 12, '2024-09-13 12:00:00', 'Approved', '2024-08-29 10:01:07', '2024-08-29 10:01:42', NULL, NULL),
(103, 15, 13, '2024-09-06 18:09:00', 'Approved', '2024-08-29 10:09:58', '2024-08-30 04:19:13', 'pat', 'doc3'),
(105, 15, 13, '2024-09-10 10:00:00', 'Approved', '2024-08-29 10:15:31', '2024-08-30 04:19:15', NULL, NULL),
(106, 15, 13, '2024-09-11 11:00:00', 'Approved', '2024-08-29 10:15:31', '2024-08-30 04:18:48', NULL, NULL),
(107, 15, 13, '2024-09-12 12:00:00', 'Approved', '2024-08-29 10:15:31', '2024-08-30 04:18:48', NULL, NULL),
(108, 15, 13, '2024-09-13 13:00:00', 'Approved', '2024-08-29 10:15:31', '2024-08-30 04:18:48', NULL, NULL),
(109, 15, 13, '2024-09-14 14:00:00', 'Completed', '2024-08-29 10:15:31', '2024-08-30 04:19:16', NULL, NULL),
(110, 15, 13, '2024-09-15 15:00:00', 'Completed', '2024-08-29 10:15:31', '2024-08-30 04:19:17', NULL, NULL),
(111, 15, 13, '2024-09-16 16:00:00', 'Approved', '2024-08-29 10:15:31', '2024-08-30 04:18:46', NULL, NULL),
(112, 15, 13, '2024-09-17 17:00:00', 'Completed', '2024-08-29 10:15:31', '2024-08-30 04:19:18', NULL, NULL),
(126, 15, 11, '2024-09-26 08:27:00', 'Approved', '2024-09-04 00:27:55', '2024-09-09 10:18:59', 'pat', 'doc'),
(127, 29, 11, '2024-10-03 10:03:00', 'Approved', '2024-09-05 02:03:14', '2024-09-09 10:18:58', 'waw', 'doc'),
(128, 15, 11, '2024-09-11 18:19:00', 'Cancelled', '2024-09-09 10:19:19', '2024-09-09 10:20:53', 'pat', 'doc'),
(129, 15, 11, '2024-09-30 18:19:00', 'Cancelled', '2024-09-09 10:19:23', '2024-09-09 10:20:53', 'pat', 'doc'),
(130, 15, 11, '2024-10-10 18:19:00', 'Pending', '2024-09-09 10:19:29', '2024-09-09 10:19:29', 'pat', 'doc'),
(131, 15, 11, '2024-10-06 18:19:00', 'Pending', '2024-09-09 10:19:36', '2024-09-09 10:19:36', 'pat', 'doc');

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
(17, 28, 'patpat', 'pat3@gmail.com'),
(18, 30, 'pat4', 'pat4@gmail.com'),
(20, 35, 'pat12', 'pat12@gmail.com'),
(21, 37, 'pat13', 'pat13@gmail.com'),
(23, 39, 'pat11', 'pat11@gmail.com'),
(29, 49, 'waw', 'waw@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified` tinyint(1) DEFAULT 0,
  `password` varchar(255) NOT NULL,
  `user_type` enum('admin','doctor','patient') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image` varchar(255) DEFAULT 'default_images/default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified`, `password`, `user_type`, `created_at`, `updated_at`, `image`) VALUES
(23, 'admin', 'admin@gmail.com', 0, '$2y$10$76BKDZcmD2xEMBXcLGaC/up1oi3OQ4mWF/fSxYsX9ErOo9ZsXrA7q', 'admin', '2024-08-28 15:44:24', '2024-08-30 03:46:10', '1724989570_356151013_1414531679398792_8049240220012412639_n.jpg'),
(24, 'pat', 'pat@gmail.com', 0, '$2y$10$.AWNTXtzlu9aAmaL87S9qeRK8vgg.tbjeZxq1jZxRtbtL93y6lnue', 'patient', '2024-08-28 15:44:46', '2024-09-06 00:36:09', '1725027837_327491193_936599814354707_2662984082626557660_n.jpg'),
(25, 'pat2', 'pat2@gmail.com', 0, '$2y$10$lwp9JQABBaTG3zXtD3QVG.AvDlOptzP0vaO2KrSCSYyzZHgJwK52e', 'patient', '2024-08-28 15:45:00', '2024-08-30 10:28:01', 'default_images/default.png'),
(26, 'doc', 'doc@gmail.com', 0, '$2y$10$cCt5eo8h5Xx0xYl6UDmwReIAZ0rnIJSoGHdI5lDk773ljfB4LTepO', 'doctor', '2024-08-28 15:45:31', '2024-08-30 03:59:32', '1724990372_357646359_975128816970660_8020607003782267481_n.jpg'),
(27, 'doc2', 'doc2@gmail.com', 0, '$2y$10$2uDwLCw8bM0PCpzHvh8AAusASRz7xIsMyyl1ceCqQq5EDMTvNJ5C.', 'doctor', '2024-08-28 15:46:08', '2024-08-30 10:28:01', 'default_images/default.png'),
(28, 'patpat', 'pat3@gmail.com', 0, '$2y$10$hqjYYmbhVFEWeDgUiUGLcetJ3A4SBmGxVoZ7IeBoPXSQos/rmbipy', 'patient', '2024-08-29 00:28:04', '2024-08-30 10:10:36', 'default.jpg'),
(29, 'doc3', 'doc3@gmail.com', 0, '$2y$10$F8sF/jrkC2fky8u7rUPXGu4HjD/L9U7etOs/IC9/vtAONKz.joAEa', 'doctor', '2024-08-29 10:09:36', '2024-08-30 10:28:01', 'default_images/default.png'),
(30, 'pat4', 'pat4@gmail.com', 0, '$2y$10$vrJafljCcilF2WbFviJsKuxz0H1jPugJGoG9BeMUEvG/Y6uhC7zO2', 'patient', '2024-08-30 10:24:15', '2024-08-30 10:28:01', 'default_images/default.png'),
(31, 'admin2', 'admin2@gmail.com', 0, '$2y$10$H3RAKjF/mEBewRa3MEf2zuQE7hx7j/WymDcZKkVscuAQ78xBAR7yW', 'admin', '2024-08-30 10:32:43', '2024-08-30 10:32:43', NULL),
(32, 'admin3', 'admin3@gmail.com', 0, '$2y$10$BFmI.cWQPnUDp0AHeM2VWuKRPhNLiu.0C498RLwnn2.El6w5I8CVG', 'admin', '2024-08-30 10:35:06', '2024-08-30 10:35:06', NULL),
(34, 'admin12', 'admin12@gmail.com', 0, '$2y$10$hGOj.1S0D3bMvk8TNxS8R..uxSscMMwb6ZmgvMbxi0k08GkyFOXIq', 'admin', '2024-08-30 11:00:33', '2024-08-30 11:00:33', NULL),
(35, 'pat12', 'pat12@gmail.com', 0, '$2y$10$c/uN65.Lsc6Ghm4qOTOxauZMLLvZQGiKPiAVmDhXezFG3MyFQhOVu', 'patient', '2024-08-30 11:24:35', '2024-08-30 11:24:35', NULL),
(36, 'admin13', 'admin13@gmail.com', 0, '$2y$10$CycaD1dJG6WCjdRxwUOX7uYI0AO/gA/3DhKbmmxik25vxon38Shu6', 'admin', '2024-08-30 11:25:25', '2024-08-30 11:25:25', NULL),
(37, 'pat13', 'pat13@gmail.com', 0, '$2y$10$X7O.SzX69KQvleyuydhVBObbS8t.onmIoJcHQCZSKWIf6zGowAlXG', 'patient', '2024-08-30 11:43:33', '2024-08-30 13:22:01', NULL),
(39, 'pat11', 'pat11@gmail.com', 0, '$2y$10$.pUZHifl7K7dpZwnz4txa.riy.F80dKSEfEGT7PbHusJKu0WIZ4ge', 'patient', '2024-08-30 14:04:13', '2024-08-30 14:04:13', NULL),
(49, 'waw', 'waw@gmail.com', 0, '$2y$10$ozGQ99OctODa5UO2chq.yeIPLINjPe5gX.QCvXwqixPvrr4DU6vHK', 'patient', '2024-09-05 02:02:59', '2024-09-05 02:02:59', NULL);

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
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

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
