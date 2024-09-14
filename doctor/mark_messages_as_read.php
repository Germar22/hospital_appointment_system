<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$doctor_id = $_SESSION['user_id'];
$chat_id = isset($_POST['chat_id']) ? intval($_POST['chat_id']) : 0;

if ($chat_id) {
    // Update messages to mark them as read
    $stmt = $pdo->prepare("UPDATE messages SET is_read = 1 WHERE chat_id = ? AND is_read = 0 AND sender_id != ?");
    $stmt->execute([$chat_id, $doctor_id]);

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid chat ID']);
}
