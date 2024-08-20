<?php
session_start();
require 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Fetch user details
$username = $_SESSION['username'];
$stmt = $pdo->prepare("SELECT * FROM patients WHERE email = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        .back-btn {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h2>User Profile</h2>
    
    <a href="home.php" class="back-btn">Back to Home</a>
    <a href="book_appointment.php" class="back-btn">Book Appointment</a>
    <a href="view_appointments.php" class="back-btn">View Appointments</a>

    <h3>Welcome, <?php echo htmlspecialchars($user['name']); ?></h3>
    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
    <p>Phone: <?php echo htmlspecialchars($user['phone']); ?></p>

    <!-- Additional user details or functionality -->
</body>
</html>
