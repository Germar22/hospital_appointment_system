<?php
session_start();
include '../db.php';

// Check if the user is logged in and if chat_id is provided
if (!isset($_SESSION['user_id']) || !isset($_GET['chat_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit();
}

$chat_id = intval($_GET['chat_id']);
$user_id = $_SESSION['user_id'];

// Fetch messages for the given chat ID
$stmt = $pdo->prepare("SELECT m.*, u.name AS sender_name, u.image AS sender_image 
                       FROM messages m 
                       JOIN users u ON m.sender_id = u.id 
                       WHERE m.chat_id = ? 
                       ORDER BY m.timestamp ASC");
$stmt->execute([$chat_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mark messages as read if they are not sent by the current user
$stmt = $pdo->prepare("UPDATE messages SET is_read = 1 
                       WHERE chat_id = ? AND sender_id != ?");
$stmt->execute([$chat_id, $user_id]);

echo json_encode($messages);
