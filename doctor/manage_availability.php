<?php
session_start();
if ($_SESSION['user_type'] != 'doctor') {
    header("Location: ../index.php");
    exit();
}

require '../db.php';

$doctor_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $availability_schedule = $_POST['availability_schedule'];

    // Update doctor's availability
    $stmt = $conn->prepare("UPDATE doctors SET availability_schedule = ? WHERE user_id = ?");
    $stmt->execute([$availability_schedule, $doctor_id]);

    echo "Availability updated successfully.";
}

// Fetch current availability
$stmt = $conn->prepare("SELECT availability_schedule FROM doctors WHERE user_id = ?");
$stmt->execute([$doctor_id]);
$doctor = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Availability</title>
</head>
<body>
    <h1>Manage Availability</h1>
    <form method="post">
        <label>Availability Schedule:</label><br>
        <textarea name="availability_schedule" rows="5" cols="50"><?= $doctor['availability_schedule'] ?></textarea><br><br>
        <button type="submit">Update</button>
    </form>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
