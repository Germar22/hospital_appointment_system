-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2024 at 01:25 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
(59, 15, 11, '2024-09-04 11:57:00', 'Completed', '2024-08-29 03:57:49', '2024-09-10 10:36:15', 'pat', 'doc'),
(60, 15, 11, '2024-09-04 12:01:00', 'Completed', '2024-08-29 04:01:33', '2024-09-10 10:36:16', 'pat', 'doc'),
(61, 15, 11, '2024-09-01 12:02:00', 'Completed', '2024-08-29 04:02:13', '2024-09-10 10:36:13', 'pat', 'doc'),
(62, 15, 11, '2024-09-02 12:03:00', 'Completed', '2024-08-29 04:03:34', '2024-09-10 10:36:12', 'pat', 'doc'),
(63, 15, 11, '2024-09-10 10:00:00', 'Completed', '2024-08-29 08:51:44', '2024-09-10 10:36:11', 'pat', 'doc'),
(64, 15, 12, '2024-09-11 11:00:00', 'Approved', '2024-08-29 08:51:44', '2024-08-29 09:04:32', 'pat', 'doc2'),
(65, 15, 11, '2024-09-12 12:00:00', 'Completed', '2024-08-29 08:51:44', '2024-09-10 10:36:13', 'pat', 'doc'),
(66, 15, 12, '2024-09-13 13:00:00', 'Approved', '2024-08-29 08:51:44', '2024-08-29 09:12:10', 'pat', 'doc2'),
(67, 15, 11, '2024-09-14 14:00:00', 'Completed', '2024-08-29 08:52:58', '2024-09-10 10:36:07', 'pat', 'doc'),
(68, 15, 12, '2024-09-15 15:00:00', 'Approved', '2024-08-29 08:52:58', '2024-08-29 09:17:16', 'pat', 'doc2'),
(69, 15, 11, '2024-09-16 16:00:00', 'Completed', '2024-08-29 08:52:58', '2024-09-10 10:36:06', 'pat', 'doc'),
(70, 15, 12, '2024-09-17 17:00:00', 'Approved', '2024-08-29 08:52:58', '2024-08-29 09:17:16', 'pat', 'doc2'),
(71, 15, 11, '2024-09-18 18:00:00', 'Completed', '2024-08-29 08:52:58', '2024-09-10 10:36:06', 'pat', 'doc'),
(72, 15, 12, '2024-09-19 19:00:00', 'Completed', '2024-08-29 08:52:58', '2024-09-10 09:37:40', 'pat', 'doc2'),
(73, 15, 11, '2024-09-20 20:00:00', 'Completed', '2024-08-29 08:52:58', '2024-08-30 14:22:55', 'pat', 'doc'),
(74, 15, 12, '2024-09-21 21:00:00', 'Completed', '2024-08-29 08:52:58', '2024-09-10 09:37:40', 'pat', 'doc2'),
(75, 15, 11, '2024-09-22 22:00:00', 'Completed', '2024-08-29 08:52:58', '2024-08-30 14:22:55', 'pat', 'doc'),
(76, 15, 12, '2024-09-23 23:00:00', 'Approved', '2024-08-29 08:52:58', '2024-08-29 09:17:18', 'pat', 'doc2'),
(77, 15, 12, '2024-09-14 16:59:00', 'Completed', '2024-08-29 08:59:40', '2024-09-10 09:37:39', 'pat', 'doc2'),
(78, 15, 12, '2024-09-14 16:59:00', 'Completed', '2024-08-29 09:00:22', '2024-09-10 09:37:39', 'pat', 'doc2'),
(79, 15, 12, '2024-10-17 17:00:00', 'Completed', '2024-08-29 09:00:56', '2024-09-10 09:37:37', 'pat', 'doc2'),
(80, 15, 11, '2024-09-01 20:23:00', 'Completed', '2024-08-29 09:23:20', '2024-08-30 14:22:54', 'pat', 'doc'),
(85, 15, 11, '2024-10-02 17:00:00', 'Completed', '2024-08-29 09:37:19', '2024-08-30 14:22:54', 'pat', 'doc'),
(86, 15, 12, '2024-10-03 18:00:00', 'Completed', '2024-08-29 09:37:19', '2024-09-10 09:37:37', 'pat', 'doc2'),
(97, 15, 11, '2024-09-11 09:00:00', 'Completed', '2024-08-29 10:00:59', '2024-08-30 14:22:52', NULL, NULL),
(98, 15, 11, '2024-09-12 10:00:00', 'Completed', '2024-08-29 10:00:59', '2024-08-30 14:21:31', NULL, NULL),
(99, 15, 11, '2024-09-13 11:00:00', 'Completed', '2024-08-29 10:00:59', '2024-08-30 14:21:30', NULL, NULL),
(100, 15, 12, '2024-09-11 10:00:00', 'Completed', '2024-08-29 10:01:07', '2024-09-10 09:37:37', NULL, NULL),
(101, 15, 12, '2024-09-12 11:00:00', 'Completed', '2024-08-29 10:01:07', '2024-09-10 09:37:36', NULL, NULL),
(102, 15, 12, '2024-09-13 12:00:00', 'Completed', '2024-08-29 10:01:07', '2024-09-10 09:37:36', NULL, NULL),
(103, 15, 13, '2024-09-06 18:09:00', 'Completed', '2024-08-29 10:09:58', '2024-09-14 04:05:47', 'pat', 'doc3'),
(105, 15, 13, '2024-09-10 10:00:00', 'Completed', '2024-08-29 10:15:31', '2024-09-14 04:05:46', NULL, NULL),
(106, 15, 13, '2024-09-11 11:00:00', 'Completed', '2024-08-29 10:15:31', '2024-09-14 04:05:46', NULL, NULL),
(107, 15, 13, '2024-09-12 12:00:00', 'Completed', '2024-08-29 10:15:31', '2024-09-14 04:05:45', NULL, NULL),
(108, 15, 13, '2024-09-13 13:00:00', 'Completed', '2024-08-29 10:15:31', '2024-09-14 04:05:45', NULL, NULL),
(109, 15, 13, '2024-09-14 14:00:00', 'Completed', '2024-08-29 10:15:31', '2024-08-30 04:19:16', NULL, NULL),
(110, 15, 13, '2024-09-15 15:00:00', 'Completed', '2024-08-29 10:15:31', '2024-08-30 04:19:17', NULL, NULL),
(111, 15, 13, '2024-09-16 16:00:00', 'Completed', '2024-08-29 10:15:31', '2024-09-14 04:05:36', NULL, NULL),
(112, 15, 13, '2024-09-17 17:00:00', 'Completed', '2024-08-29 10:15:31', '2024-08-30 04:19:18', NULL, NULL),
(126, 15, 11, '2024-09-26 08:27:00', 'Completed', '2024-09-04 00:27:55', '2024-09-10 10:36:03', 'pat', 'doc'),
(127, 29, 11, '2024-10-03 10:03:00', 'Completed', '2024-09-05 02:03:14', '2024-09-10 10:36:03', 'waw', 'doc'),
(145, 15, 14, '2024-09-13 20:26:00', 'Approved', '2024-09-10 10:26:54', '2024-09-10 10:35:23', 'pat', 'doc12'),
(146, 15, 11, '2024-09-28 18:26:00', 'Completed', '2024-09-10 10:27:00', '2024-09-10 10:47:31', 'pat', 'doc'),
(147, 15, 11, '2024-09-12 12:29:00', 'Completed', '2024-09-10 10:32:49', '2024-09-10 10:47:30', 'pat', 'doc'),
(148, 15, 11, '2024-09-12 00:33:00', 'Completed', '2024-09-10 10:33:51', '2024-09-10 10:47:30', 'pat', 'doc'),
(149, 15, 14, '2024-09-20 10:34:00', 'Approved', '2024-09-10 10:34:20', '2024-09-10 10:35:22', 'pat', 'doc12'),
(150, 15, 11, '2024-09-14 13:53:00', 'Completed', '2024-09-10 10:53:58', '2024-09-10 10:54:41', 'pat', 'doc'),
(152, 31, 13, '2024-09-28 12:05:00', 'Completed', '2024-09-14 04:05:08', '2024-09-14 04:07:44', 'paw', 'doc3'),
(153, 15, 11, '2024-09-24 12:25:00', 'Completed', '2024-09-14 04:25:18', '2024-09-14 04:31:12', 'pat', 'doc'),
(154, 15, 11, '2024-09-24 12:30:00', 'Completed', '2024-09-14 04:25:28', '2024-09-14 04:31:10', 'pat', 'doc'),
(155, 15, 11, '2024-10-01 12:30:00', 'Completed', '2024-09-14 04:30:46', '2024-09-14 04:31:06', 'pat', 'doc'),
(156, 15, 11, '2024-09-28 12:30:00', 'Completed', '2024-09-14 04:30:50', '2024-09-14 04:31:05', 'pat', 'doc'),
(157, 15, 11, '2024-09-15 12:43:00', 'Completed', '2024-09-14 04:43:33', '2024-09-14 04:44:40', 'pat', 'doc'),
(158, 15, 11, '2024-09-16 12:43:00', 'Completed', '2024-09-14 04:43:39', '2024-09-14 04:44:39', 'pat', 'doc'),
(159, 15, 11, '2024-09-17 12:43:00', 'Completed', '2024-09-14 04:43:44', '2024-09-14 04:44:37', 'pat', 'doc'),
(160, 15, 11, '2024-09-15 12:55:00', 'Completed', '2024-09-14 04:55:55', '2024-09-14 04:57:43', 'pat', 'doc'),
(161, 15, 11, '2024-09-16 12:55:00', 'Completed', '2024-09-14 04:55:59', '2024-09-14 04:57:42', 'pat', 'doc'),
(162, 15, 11, '2024-09-17 12:56:00', 'Completed', '2024-09-14 04:56:05', '2024-09-14 04:57:41', 'pat', 'doc'),
(163, 15, 11, '2024-09-16 13:00:00', 'Completed', '2024-09-14 05:00:51', '2024-09-14 05:01:28', 'pat', 'doc'),
(164, 15, 11, '2024-09-18 13:00:00', 'Completed', '2024-09-14 05:00:57', '2024-09-14 05:01:27', 'pat', 'doc'),
(165, 15, 11, '2024-10-11 13:00:00', 'Completed', '2024-09-14 05:01:00', '2024-09-14 05:01:22', 'pat', 'doc'),
(166, 15, 11, '2024-10-02 13:01:00', 'Completed', '2024-09-14 05:01:48', '2024-09-14 05:02:10', 'pat', 'doc'),
(169, 15, 11, '2024-09-17 13:09:00', 'Completed', '2024-09-14 05:09:58', '2024-09-14 05:17:11', 'pat', 'doc'),
(170, 15, 11, '2024-09-15 13:17:00', 'Completed', '2024-09-14 05:17:34', '2024-09-14 05:18:14', 'pat', 'doc'),
(171, 15, 11, '2024-10-01 13:24:00', 'Completed', '2024-09-14 05:25:01', '2024-09-14 05:25:12', 'pat', 'doc'),
(172, 15, 11, '2024-10-07 14:20:00', 'Completed', '2024-10-06 11:18:56', '2024-10-06 11:19:28', 'pat', 'doc'),
(173, 15, 11, '2024-10-09 12:25:00', 'Completed', '2024-10-06 11:20:22', '2024-10-06 11:20:47', 'pat', 'doc');

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `sender_id`, `receiver_id`, `created_at`) VALUES
(1, 24, 26, '2024-09-10 11:21:13'),
(2, 24, 26, '2024-09-10 11:22:41'),
(3, 24, 27, '2024-09-10 11:44:06'),
(4, 24, 50, '2024-09-10 11:44:07'),
(5, 24, 51, '2024-09-10 11:44:07'),
(6, 24, 29, '2024-09-10 11:44:07'),
(7, 24, 52, '2024-09-11 07:26:56'),
(8, 26, 25, '2024-09-11 08:45:30'),
(9, 26, 28, '2024-09-11 08:45:31'),
(10, 26, 30, '2024-09-11 08:45:31'),
(11, 26, 35, '2024-09-11 08:45:32'),
(12, 26, 37, '2024-09-11 08:45:32'),
(13, 26, 39, '2024-09-11 08:45:32'),
(14, 26, 49, '2024-09-11 08:45:33'),
(15, 25, 27, '2024-09-11 09:25:37'),
(16, 25, 29, '2024-09-11 10:03:52'),
(17, 25, 51, '2024-09-11 10:03:54'),
(18, 25, 52, '2024-09-11 10:03:55'),
(19, 26, 53, '2024-09-11 10:04:45'),
(20, 53, 27, '2024-09-11 10:11:40'),
(21, 53, 29, '2024-09-11 10:11:41'),
(22, 53, 50, '2024-09-11 10:11:41'),
(23, 53, 51, '2024-09-11 10:11:41'),
(24, 53, 52, '2024-09-11 10:11:42'),
(25, 24, 25, '2024-09-11 10:23:48'),
(26, 24, 28, '2024-09-11 10:23:51'),
(27, 24, 30, '2024-09-11 10:23:52'),
(28, 24, 35, '2024-09-11 10:23:53'),
(29, 24, 37, '2024-09-11 10:23:53'),
(30, 24, 49, '2024-09-11 10:23:53'),
(31, 24, 39, '2024-09-11 10:23:54'),
(32, 24, 53, '2024-09-11 10:25:20'),
(33, 26, 27, '2024-09-11 10:34:05'),
(34, 26, 29, '2024-09-11 10:34:05'),
(35, 26, 50, '2024-09-11 10:34:05'),
(36, 26, 51, '2024-09-11 10:34:06'),
(37, 26, 52, '2024-09-11 10:34:06'),
(38, 0, 26, '2024-09-12 00:42:49'),
(39, 0, 27, '2024-09-12 00:42:50'),
(40, 0, 29, '2024-09-12 00:42:50'),
(41, 0, 50, '2024-09-12 00:42:51'),
(42, 0, 52, '2024-09-12 00:42:51'),
(43, 54, 29, '2024-09-14 04:05:15'),
(44, 54, 26, '2024-09-14 04:11:40'),
(45, 54, 27, '2024-09-14 04:11:40'),
(46, 54, 50, '2024-09-14 04:11:41'),
(47, 54, 51, '2024-09-14 04:11:41'),
(48, 54, 52, '2024-09-14 04:11:42');

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
(13, 29, 'gastroenterologist', '10:09 to 13:12', '2024-08-29 10:09:36', '2024-08-29 10:09:36', 'doc3'),
(14, 50, 'endodontology', '08:49 to 18:50', '2024-09-10 09:50:21', '2024-09-10 09:50:21', 'doc12'),
(15, 51, 'gastroenterology', '05:51 to 14:51', '2024-09-10 09:51:59', '2024-09-10 09:51:59', 'doc13'),
(16, 52, 'epidemiology', '10:22 to 15:22', '2024-09-11 07:22:17', '2024-09-11 07:22:17', 'wewe');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `chat_id`, `sender_id`, `message`, `sent_at`, `timestamp`, `is_read`) VALUES
(3, 5, 24, 'asdasd', '2024-09-11 08:50:34', '2024-09-11 08:50:34', 0),
(4, 5, 24, 'adaasd', '2024-09-11 08:50:36', '2024-09-11 08:50:36', 0),
(5, 1, 24, 'asdaasd', '2024-09-11 08:50:41', '2024-09-11 08:50:41', 1),
(6, 1, 26, 'qweqwe', '2024-09-11 08:53:34', '2024-09-11 08:53:34', 1),
(7, 1, 26, 'qweqwe', '2024-09-11 08:53:37', '2024-09-11 08:53:37', 1),
(8, 1, 26, 'asdasdas', '2024-09-11 08:54:20', '2024-09-11 08:54:20', 1),
(9, 1, 24, 'asdasd', '2024-09-11 08:57:08', '2024-09-11 08:57:08', 1),
(10, 1, 24, 'wawau', '2024-09-11 08:57:14', '2024-09-11 08:57:14', 1),
(11, 1, 26, 'asdasd', '2024-09-11 09:08:38', '2024-09-11 09:08:38', 1),
(12, 1, 26, 'With these changes, messages sent by the doctor will be aligned to the right side, while messages received from the patient will be aligned to the left. This should give a clear visual distinction between sent and received messages.', '2024-09-11 09:08:54', '2024-09-11 09:08:54', 1),
(13, 1, 26, 'asdasdasd', '2024-09-11 09:10:36', '2024-09-11 09:10:36', 1),
(14, 8, 26, 'hello', '2024-09-11 09:25:07', '2024-09-11 09:25:07', 1),
(15, 8, 25, 'asdadasdasd', '2024-09-11 09:25:40', '2024-09-11 09:25:40', 1),
(16, 8, 25, 'asdadadasdasd', '2024-09-11 09:28:54', '2024-09-11 09:28:54', 1),
(17, 19, 26, 'hi', '2024-09-11 10:04:50', '2024-09-11 10:04:50', 1),
(18, 1, 26, 'adsadadqwasdwdwd w', '2024-09-11 10:11:07', '2024-09-11 10:11:07', 1),
(19, 1, 26, 'qdas dqwed qadaqwd w', '2024-09-11 10:11:10', '2024-09-11 10:11:10', 1),
(20, 1, 26, 'asd asd qwd qawf awdf a', '2024-09-11 10:11:13', '2024-09-11 10:11:13', 1),
(21, 1, 24, 'asdasdasd', '2024-09-11 10:14:39', '2024-09-11 10:14:39', 1),
(22, 19, 26, 'asdasdasdasd', '2024-09-11 10:16:08', '2024-09-11 10:16:08', 1),
(23, 1, 26, 'hello', '2024-09-11 10:18:08', '2024-09-11 10:18:08', 1),
(24, 1, 24, 'qweqeqeqwe', '2024-09-11 10:33:34', '2024-09-11 10:33:34', 1),
(25, 1, 24, 'qweqweqwe', '2024-09-11 10:33:35', '2024-09-11 10:33:35', 1),
(26, 1, 24, 'qweqweqwewq', '2024-09-11 10:33:36', '2024-09-11 10:33:36', 1),
(27, 1, 24, 'qweqweqweqwe', '2024-09-11 10:33:37', '2024-09-11 10:33:37', 1),
(28, 1, 24, 'qweqweqweqwe', '2024-09-11 10:33:39', '2024-09-11 10:33:39', 1),
(29, 1, 26, 'qweqweqweqwe', '2024-09-11 10:39:47', '2024-09-11 10:39:47', 1),
(30, 1, 24, 'adadadasdad', '2024-09-11 10:40:01', '2024-09-11 10:40:01', 1),
(31, 1, 26, 'qweqeqeqweqwe', '2024-09-11 10:46:40', '2024-09-11 10:46:40', 1),
(32, 19, 26, 'qweqeweqeqwe', '2024-09-11 10:50:55', '2024-09-11 10:50:55', 1),
(33, 19, 53, 'qweqqwqwweqeqweqwqwe', '2024-09-11 10:51:28', '2024-09-11 10:51:28', 1),
(34, 19, 26, 'waowaowawo', '2024-09-11 11:03:36', '2024-09-11 11:03:36', 0),
(35, 1, 26, 'asdasdasd', '2024-09-11 11:12:09', '2024-09-11 11:12:09', 1),
(36, 1, 24, 'qqweqwweqweqwesad  qwe qw q w', '2024-09-11 11:12:33', '2024-09-11 11:12:33', 1),
(37, 1, 24, 'asdadadadadadadadadasdasdasd', '2024-09-11 11:21:56', '2024-09-11 11:21:56', 1),
(38, 1, 24, '1', '2024-09-11 11:21:58', '2024-09-11 11:21:58', 1),
(39, 1, 24, '1', '2024-09-11 11:22:00', '2024-09-11 11:22:00', 1),
(40, 1, 24, '2312312', '2024-09-11 11:22:01', '2024-09-11 11:22:01', 1),
(41, 8, 26, 'asdasdasdasd', '2024-09-11 23:26:51', '2024-09-11 23:26:51', 1),
(42, 8, 26, 'dapat magreset nig click', '2024-09-11 23:26:58', '2024-09-11 23:26:58', 1),
(43, 1, 24, 'qweqeqweqweqwe', '2024-09-12 00:18:09', '2024-09-12 00:18:09', 1),
(44, 1, 24, 'test', '2024-09-12 00:18:14', '2024-09-12 00:18:14', 1),
(45, 1, 24, 'qqweqwewqweqweq', '2024-09-12 00:18:35', '2024-09-12 00:18:35', 1),
(46, 1, 26, 'qweqeqweqwe', '2024-09-12 00:21:23', '2024-09-12 00:21:23', 1),
(47, 1, 26, 'hello', '2024-09-12 00:21:26', '2024-09-12 00:21:26', 1),
(48, 1, 26, 'qweqwe', '2024-09-14 03:08:25', '2024-09-14 03:08:25', 1),
(49, 1, 24, 'asdasasd', '2024-09-14 03:09:48', '2024-09-14 03:09:48', 1),
(50, 1, 24, 'test', '2024-09-14 03:10:15', '2024-09-14 03:10:15', 1),
(51, 1, 24, 'tes2', '2024-09-14 03:10:24', '2024-09-14 03:10:24', 1),
(52, 1, 26, 'test', '2024-09-14 03:11:06', '2024-09-14 03:11:06', 1),
(53, 6, 24, 'asdasd', '2024-09-14 03:15:12', '2024-09-14 03:15:12', 0),
(54, 1, 26, 'adasd', '2024-09-14 03:25:12', '2024-09-14 03:25:12', 1),
(55, 1, 26, 'asdasd', '2024-09-14 03:25:14', '2024-09-14 03:25:14', 1),
(56, 1, 24, 'asdadasd', '2024-09-14 03:29:03', '2024-09-14 03:29:03', 1),
(57, 1, 24, 'asdasd', '2024-09-14 03:29:05', '2024-09-14 03:29:05', 1),
(58, 1, 24, 'replied', '2024-09-14 03:29:11', '2024-09-14 03:29:11', 1),
(59, 8, 26, 'adadsasd', '2024-09-14 03:35:04', '2024-09-14 03:35:04', 1),
(60, 1, 24, 'replied2', '2024-09-14 03:49:49', '2024-09-14 03:49:49', 1),
(61, 1, 26, 'adasdasd', '2024-09-14 03:55:36', '2024-09-14 03:55:36', 1),
(62, 1, 26, 'adadasd', '2024-09-14 03:55:37', '2024-09-14 03:55:37', 1),
(63, 8, 26, 'yesyes', '2024-09-14 03:56:17', '2024-09-14 03:56:17', 1),
(64, 43, 54, 'hi gae ko kwarta', '2024-09-14 04:05:23', '2024-09-14 04:05:23', 0),
(65, 1, 24, 'hello', '2024-09-14 04:13:03', '2024-09-14 04:13:03', 1),
(66, 1, 26, 'waw', '2024-09-14 04:13:15', '2024-09-14 04:13:15', 1),
(67, 1, 24, 'adaasasdad', '2024-09-14 04:14:23', '2024-09-14 04:14:23', 1),
(68, 8, 26, 'aasdadasa', '2024-09-14 06:28:14', '2024-09-14 06:28:14', 1),
(69, 8, 25, 'asdadadadasad', '2024-09-14 06:29:16', '2024-09-14 06:29:16', 1),
(70, 1, 24, 'germar', '2024-09-14 06:29:39', '2024-09-14 06:29:39', 1),
(71, 1, 26, 'qweqweqwe', '2024-09-14 06:30:34', '2024-09-14 06:30:34', 1),
(72, 1, 24, 'patpatpaptaptpapta', '2024-09-14 06:37:11', '2024-09-14 06:37:11', 1),
(73, 1, 26, 'adsadasdasd', '2024-09-14 06:37:29', '2024-09-14 06:37:29', 1),
(74, 1, 26, 'try ulet', '2024-09-14 06:46:55', '2024-09-14 06:46:55', 1),
(75, 1, 26, 'qqweqqweqwe', '2024-09-16 14:08:09', '2024-09-16 14:08:09', 1),
(76, 1, 24, 'wao', '2024-09-16 14:08:28', '2024-09-16 14:08:28', 1),
(77, 1, 26, 'hello', '2024-09-27 09:12:59', '2024-09-27 09:12:59', 1);

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
(29, 49, 'waw', 'waw@gmail.com'),
(30, 53, 'wae', 'wae@gmail.com'),
(31, 54, 'paw', 'paw@gmail.com'),
(32, 55, 'gear', 'gear@gmail.com');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image` varchar(255) DEFAULT 'default_images/default.jpg',
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`, `created_at`, `updated_at`, `image`, `address`) VALUES
(23, 'admin', 'admin@gmail.com', '$2y$10$76BKDZcmD2xEMBXcLGaC/up1oi3OQ4mWF/fSxYsX9ErOo9ZsXrA7q', 'admin', '2024-08-28 15:44:24', '2024-09-28 05:02:46', '1725895441_default.png', 'Fatima, General Santos, Soccsksargen, 9500, Philippines'),
(24, 'pat', 'pat@gmail.com', '$2y$10$.AWNTXtzlu9aAmaL87S9qeRK8vgg.tbjeZxq1jZxRtbtL93y6lnue', 'patient', '2024-08-28 15:44:46', '2024-09-28 05:00:06', '1725895461_2020-1274-PHOTO.jpg', 'Placeda, Fatima, General Santos, Soccsksargen, 9500, Philippines'),
(25, 'pat2', 'pat2@gmail.com', '$2y$10$lwp9JQABBaTG3zXtD3QVG.AvDlOptzP0vaO2KrSCSYyzZHgJwK52e', 'patient', '2024-08-28 15:45:00', '2024-09-14 06:29:08', '1726295348_default.png', NULL),
(26, 'doc', 'doc@gmail.com', '$2y$10$cCt5eo8h5Xx0xYl6UDmwReIAZ0rnIJSoGHdI5lDk773ljfB4LTepO', 'doctor', '2024-08-28 15:45:31', '2024-09-27 08:43:16', '1726038642_3rd.png', 'prk. 24 fatima Uhaw General Santos City'),
(27, 'doc2', 'doc2@gmail.com', '$2y$10$2uDwLCw8bM0PCpzHvh8AAusASRz7xIsMyyl1ceCqQq5EDMTvNJ5C.', 'doctor', '2024-08-28 15:46:08', '2024-09-14 04:14:00', '1726287240_default.png', NULL),
(28, 'patpat', 'pat3@gmail.com', '$2y$10$hqjYYmbhVFEWeDgUiUGLcetJ3A4SBmGxVoZ7IeBoPXSQos/rmbipy', 'patient', '2024-08-29 00:28:04', '2024-08-30 10:10:36', 'default.jpg', NULL),
(29, 'doc3', 'doc3@gmail.com', '$2y$10$F8sF/jrkC2fky8u7rUPXGu4HjD/L9U7etOs/IC9/vtAONKz.joAEa', 'doctor', '2024-08-29 10:09:36', '2024-09-14 04:08:29', '1726286909_default.png', NULL),
(30, 'pat4', 'pat4@gmail.com', '$2y$10$vrJafljCcilF2WbFviJsKuxz0H1jPugJGoG9BeMUEvG/Y6uhC7zO2', 'patient', '2024-08-30 10:24:15', '2024-09-14 06:43:17', '1726296197_default.png', NULL),
(31, 'admin2', 'admin2@gmail.com', '$2y$10$H3RAKjF/mEBewRa3MEf2zuQE7hx7j/WymDcZKkVscuAQ78xBAR7yW', 'admin', '2024-08-30 10:32:43', '2024-08-30 10:32:43', NULL, NULL),
(32, 'admin3', 'admin3@gmail.com', '$2y$10$BFmI.cWQPnUDp0AHeM2VWuKRPhNLiu.0C498RLwnn2.El6w5I8CVG', 'admin', '2024-08-30 10:35:06', '2024-08-30 10:35:06', NULL, NULL),
(34, 'admin12', 'admin12@gmail.com', '$2y$10$hGOj.1S0D3bMvk8TNxS8R..uxSscMMwb6ZmgvMbxi0k08GkyFOXIq', 'admin', '2024-08-30 11:00:33', '2024-08-30 11:00:33', NULL, NULL),
(35, 'pat12', 'pat12@gmail.com', '$2y$10$c/uN65.Lsc6Ghm4qOTOxauZMLLvZQGiKPiAVmDhXezFG3MyFQhOVu', 'patient', '2024-08-30 11:24:35', '2024-08-30 11:24:35', NULL, NULL),
(36, 'admin13', 'admin13@gmail.com', '$2y$10$CycaD1dJG6WCjdRxwUOX7uYI0AO/gA/3DhKbmmxik25vxon38Shu6', 'admin', '2024-08-30 11:25:25', '2024-08-30 11:25:25', NULL, NULL),
(37, 'pat13', 'pat13@gmail.com', '$2y$10$X7O.SzX69KQvleyuydhVBObbS8t.onmIoJcHQCZSKWIf6zGowAlXG', 'patient', '2024-08-30 11:43:33', '2024-08-30 13:22:01', NULL, NULL),
(39, 'pat11', 'pat11@gmail.com', '$2y$10$.pUZHifl7K7dpZwnz4txa.riy.F80dKSEfEGT7PbHusJKu0WIZ4ge', 'patient', '2024-08-30 14:04:13', '2024-08-30 14:04:13', NULL, NULL),
(49, 'waw', 'waw@gmail.com', '$2y$10$ozGQ99OctODa5UO2chq.yeIPLINjPe5gX.QCvXwqixPvrr4DU6vHK', 'patient', '2024-09-05 02:02:59', '2024-09-05 02:02:59', NULL, NULL),
(50, 'doc12', 'doc12@gmail.com', '$2y$10$7JmnJycnL7KzQZKwlnICieejazjSrYuCjBvgjnahWV4AQPLnqOQqe', 'doctor', '2024-09-10 09:50:21', '2024-09-10 09:50:21', NULL, NULL),
(51, 'doc13', 'doc13@gmail.com', '$2y$10$dFhGxRzeUF4e8/qyqTDk/efeYesuOkp5QOINssnr9j0Fe5ua/eAXC', 'doctor', '2024-09-10 09:51:59', '2024-09-10 09:51:59', NULL, NULL),
(52, 'wewe', 'wewe@gmail.com', '$2y$10$8DVk0REIZjquf20GrzQpXugsHGBbJ7mrZRQANqMrJsbP75AlnojT.', 'doctor', '2024-09-11 07:22:17', '2024-09-11 07:22:17', NULL, NULL),
(53, 'wae', 'wae@gmail.com', '$2y$10$PzDEtgGeBuFkWKUplxQ3ZeGvUDnbM1.SWAOBBfYDbseg4lcAjhWr.', 'patient', '2024-09-11 10:04:31', '2024-09-11 10:04:31', '1726049071_images.jpg', NULL),
(54, 'paw', 'paw@gmail.com', '$2y$10$iIretboVoNw6WwnrZNQxjeXEz.11Xg4QZ8WF4bSthrsH4D/Stez.a', 'patient', '2024-09-14 04:03:01', '2024-09-14 04:03:20', '1726286600_357646359_975128816970660_8020607003782267481_n.jpg', NULL),
(55, 'gear', 'gear@gmail.com', '$2y$10$5o.3V8Bm6JdCjSNmCaVG2.ppFuaSrtr0iXWwiVLgH73J/JPYprJ5O', 'patient', '2024-09-27 08:09:24', '2024-09-27 08:09:24', NULL, 'prk. 24 fatima uhaw, general santos city');

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
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_id` (`chat_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

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
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`);

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
