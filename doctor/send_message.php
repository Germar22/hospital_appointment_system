<?php
session_start();
include '../db.php';

// Check if user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$doctor_id = $_SESSION['user_id'];
$chat_id = isset($_POST['chat_id']) ? intval($_POST['chat_id']) : 0;
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if ($chat_id && $message) {
    // Insert message
    $stmt = $pdo->prepare("INSERT INTO messages (chat_id, sender_id, message) VALUES (?, ?, ?)");
    $stmt->execute([$chat_id, $doctor_id, $message]);

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
}
