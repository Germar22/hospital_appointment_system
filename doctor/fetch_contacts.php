<?php
session_start();
include '../db.php';

// Check if user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit();
}

$doctor_id = $_SESSION['user_id'];

// Fetch the list of patients with their profile images and unread message count
$stmt = $pdo->prepare("
    SELECT u.id, u.name, u.image, COUNT(m.id) AS unread_count
    FROM users u
    JOIN patients p ON u.id = p.user_id
    LEFT JOIN chats c ON (c.sender_id = u.id OR c.receiver_id = u.id)
    LEFT JOIN messages m ON m.chat_id = c.id AND m.is_read = 0 AND m.sender_id != ?
    WHERE u.id != ?
    GROUP BY u.id
");
$stmt->execute([$doctor_id, $doctor_id]);
$patients = $stmt->fetchAll();

echo json_encode($patients);
