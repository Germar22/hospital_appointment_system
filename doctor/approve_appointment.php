<?php
session_start();
include '../db.php'; // Ensure this path is correct

// Check if user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    header("Location: ../index.php"); // Redirect to login page if not logged in or not a doctor
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointment_id = $_POST['appointment_id'];

    // Update appointment status to "Confirmed"
    try {
        $stmt = $pdo->prepare("UPDATE appointments SET status = 'Confirmed', updated_at = NOW() WHERE id = ?");
        $stmt->execute([$appointment_id]);

        // Fetch patient email and admin email for notifications
        $stmt = $pdo->prepare("SELECT p.user_id, u_patient.email AS patient_email, u_admin.email AS admin_email
                               FROM appointments a
                               JOIN patients p ON a.patient_id = p.id
                               JOIN users u_patient ON p.user_id = u_patient.id
                               JOIN doctors d ON a.doctor_id = d.id
                               JOIN users u_admin ON u_admin.user_type = 'admin'
                               WHERE a.id = ?");
        $stmt->execute([$appointment_id]);
        $data = $stmt->fetch();

        if ($data) {
            $patient_email = $data['patient_email'];
            $admin_email = $data['admin_email'];

            // Send notification to patient
            $subject_patient = "Appointment Confirmed";
            $message_patient = "Your appointment has been confirmed. Appointment ID: $appointment_id.";
            mail($patient_email, $subject_patient, $message_patient);

            // Send notification to admin
            $subject_admin = "Appointment Confirmed";
            $message_admin = "Appointment ID $appointment_id has been confirmed.";
            mail($admin_email, $subject_admin, $message_admin);
        }

        // Redirect back to the doctor dashboard
        header("Location: doctor_dashboard.php");
        exit();
    } catch (PDOException $e) {
        echo "Error updating appointment: " . $e->getMessage();
    }
} else {
    header("Location: doctor_dashboard.php"); // Redirect to dashboard if accessed directly
    exit();
}
