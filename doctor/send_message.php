<?php
session_start();
include '../db.php';

// Check if user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit();
}

$doctor_id = $_SESSION['user_id'];
$chat_id = isset($_POST['chat_id']) ? intval($_POST['chat_id']) : 0;
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if ($chat_id <= 0 || $message === '') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    exit();
}

// Insert the new message
$stmt = $pdo->prepare("INSERT INTO messages (chat_id, sender_id, message, is_read) VALUES (?, ?, ?, 0)");
$stmt->execute([$chat_id, $doctor_id, $message]);

// Mark all other messages in this chat as read for the other participant
$stmt = $pdo->prepare("UPDATE messages SET is_read = 1 WHERE chat_id = ? AND sender_id != ?");
$stmt->execute([$chat_id, $doctor_id]);

echo json_encode(['status' => 'success']);
