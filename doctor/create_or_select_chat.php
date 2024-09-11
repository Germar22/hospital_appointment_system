<?php
session_start();
include '../db.php';

// Check if user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit();
}

$doctor_id = $_SESSION['user_id'];
$patient_id = isset($_POST['patient_id']) ? intval($_POST['patient_id']) : 0;

if ($patient_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid patient ID']);
    exit();
}

// Check if a chat already exists between the doctor and patient
$stmt = $pdo->prepare("SELECT id FROM chats WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)");
$stmt->execute([$doctor_id, $patient_id, $patient_id, $doctor_id]);
$chat = $stmt->fetch();

if ($chat) {
    $chat_id = $chat['id'];
} else {
    // Create a new chat
    $stmt = $pdo->prepare("INSERT INTO chats (sender_id, receiver_id) VALUES (?, ?)");
    $stmt->execute([$doctor_id, $patient_id]);
    $chat_id = $pdo->lastInsertId();
}

// Fetch patient info
$stmt = $pdo->prepare("SELECT name, image FROM users WHERE id = ?");
$stmt->execute([$patient_id]);
$patient = $stmt->fetch();

if ($chat_id && $patient) {
    echo json_encode([
        'status' => 'success',
        'chat_id' => $chat_id,
        'patient_name' => $patient['name'],
        'patient_image' => $patient['image'] ? '../uploads/' . $patient['image'] : '../default_images/default.png'
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to create or select chat']);
}
