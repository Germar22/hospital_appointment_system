<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch doctors
$stmt = $pdo->query("SELECT * FROM doctors");
$doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome to Your Dashboard</h2>
    <h3>Available Doctors</h3>
    <ul>
        <?php foreach ($doctors as $doctor): ?>
            <li>
                <strong><?php echo htmlspecialchars($doctor['name']); ?></strong><br>
                Specialty: <?php echo htmlspecialchars($doctor['specialty']); ?><br>
                Phone: <?php echo htmlspecialchars($doctor['phone']); ?><br>
                <a href="book_appointment.php?doctor_id=<?php echo $doctor['id']; ?>">Book Appointment</a>
                <a href="contact_doctor.php?doctor_id=<?php echo $doctor['id']; ?>">Contact Doctor</a>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="logout.php">Logout</a>
</body>
</html>
