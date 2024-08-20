<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id']; // Assuming user ID is stored in the session
    $doctor_id = intval($_POST['doctor']);
    $appointment_date = $_POST['date'];

    // Validate input
    if (empty($doctor_id) || empty($appointment_date)) {
        echo "Please fill in all required fields.";
        exit();
    }

    // Check if the appointment already exists
    $stmt = $pdo->prepare("SELECT * FROM appointments WHERE patient_id = ? AND appointment_date = ?");
    $stmt->execute([$user_id, $appointment_date]);
    if ($stmt->rowCount() > 0) {
        echo "You already have an appointment on this date.";
        exit();
    }

    // Insert appointment into the database
    $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date) VALUES (?, ?, ?)");
    if ($stmt->execute([$user_id, $doctor_id, $appointment_date])) {
        echo "Appointment booked successfully.";
    } else {
        echo "Failed to book appointment.";
    }
}
