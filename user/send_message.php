<?php
session_start();
include '../db.php';

// Check if user is logged in and if chat_id and message are provided
if (!isset($_SESSION['user_id']) || !isset($_POST['chat_id']) || !isset($_POST['message'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit();
}

$chat_id = intval($_POST['chat_id']);
$message = trim($_POST['message']);
$sender_id = $_SESSION['user_id'];

// Insert the new message
$stmt = $pdo->prepare("INSERT INTO messages (chat_id, sender_id, message, timestamp, is_read) VALUES (?, ?, ?, NOW(), 0)");
$stmt->execute([$chat_id, $sender_id, $message]);

echo json_encode(['status' => 'success']);
