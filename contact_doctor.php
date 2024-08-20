<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$doctor_id = $_GET['doctor_id'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST['message'];

    $stmt = $pdo->prepare("INSERT INTO messages (user_id, doctor_id, message) VALUES (?, ?, ?)");
    if ($stmt->execute([$user_id, $doctor_id, $message])) {
        echo "Message sent to the doctor.";
    } else {
        echo "Failed to send message.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Doctor</title>
</head>
<body>
    <h2>Contact Doctor</h2>
    <form method="post">
        <label for="message">Your Message:</label>
        <textarea name="message" required></textarea>
        <button type="submit">Send Message</button>
    </form>
</body>
</html>
