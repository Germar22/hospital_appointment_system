<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    echo json_encode([]);
    exit();
}

$doctor_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT u.id, u.name, u.image,
           IFNULL(SUM(CASE WHEN m.is_read = 0 AND m.sender_id != ? THEN 1 ELSE 0 END), 0) AS unread_messages
    FROM users u
    JOIN patients p ON u.id = p.user_id
    LEFT JOIN messages m ON (m.sender_id = u.id AND m.is_read = 0 AND m.chat_id IN (
        SELECT id FROM chats WHERE sender_id = ? OR receiver_id = ?
    ))
    WHERE u.id != ?
    GROUP BY u.id, u.name, u.image
");
$stmt->execute([$doctor_id, $doctor_id, $doctor_id, $doctor_id]);
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($contacts);
