<?php
session_start();
require '../db.php'; // Adjust the path as needed

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];

    // Delete the appointment from the database
    $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
    $stmt->execute([$appointment_id]);

    header('Location: manage_appointments.php');
    exit();
}
