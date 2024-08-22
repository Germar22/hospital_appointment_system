<?php
require '../db.php';
session_start();
if ($_SESSION['user_type'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
</head>
<body>
    <h1>Manage Users</h1>

    <a href="manage_doctor.php">
        <button>Manage Doctors</button>
    </a>

    <a href="manage_patient.php">
        <button>Manage Patients</button>
    </a>

    <br><br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
