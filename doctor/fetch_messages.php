<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    echo json_encode([]);
    exit();
}

$doctor_id = $_SESSION['user_id'];
$chat_id = isset($_GET['chat_id']) ? intval($_GET['chat_id']) : 0;

$stmt = $pdo->prepare("
    SELECT m.*, u.name AS sender_name
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    WHERE m.chat_id = ?
    ORDER BY m.timestamp ASC
");
$stmt->execute([$chat_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($messages);
