<?php
session_start();
include '../db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit();
}

$patient_id = $_SESSION['user_id'];

// Fetch the list of doctors with their profile images and unread message counts
$stmt = $pdo->prepare("
    SELECT u.id, u.name, u.image,
           IFNULL(SUM(CASE WHEN m.is_read = 0 AND m.sender_id != ? THEN 1 ELSE 0 END), 0) AS unread_messages
    FROM users u
    JOIN doctors d ON u.id = d.user_id
    LEFT JOIN messages m ON (m.sender_id = u.id AND m.is_read = 0 AND m.chat_id IN (
        SELECT id FROM chats WHERE sender_id = ? OR receiver_id = ?
    ))
    WHERE u.id != ?
    GROUP BY u.id, u.name, u.image
");
$stmt->execute([$patient_id, $patient_id, $patient_id, $patient_id]);
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($contacts);
