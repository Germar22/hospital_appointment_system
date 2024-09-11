<?php
session_start();
include '../db.php';

// Check if user is logged in and if doctor_id is provided
if (!isset($_SESSION['user_id']) || !isset($_POST['doctor_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit();
}

$patient_id = $_SESSION['user_id'];
$doctor_id = intval($_POST['doctor_id']);

// Check if a chat already exists
$stmt = $pdo->prepare("SELECT id FROM chats WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)");
$stmt->execute([$patient_id, $doctor_id, $doctor_id, $patient_id]);
$chat = $stmt->fetch();

if ($chat) {
    // Chat exists
    $chat_id = $chat['id'];
} else {
    // Create a new chat
    $stmt = $pdo->prepare("INSERT INTO chats (sender_id, receiver_id) VALUES (?, ?)");
    $stmt->execute([$patient_id, $doctor_id]);
    $chat_id = $pdo->lastInsertId();
}

// Fetch doctor info
$stmt = $pdo->prepare("SELECT u.id, u.name, u.image FROM users u WHERE u.id = ?");
$stmt->execute([$doctor_id]);
$doctor = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode([
    'status' => 'success',
    'chat_id' => $chat_id,
    'doctor_name' => $doctor['name'],
    'doctor_image' => $doctor['image'] ? '../uploads/' . $doctor['image'] : '../default_images/default.png'
]);
