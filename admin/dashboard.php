<?php
session_start();
require '../db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Fetch counts for overview
$stmt = $conn->query("SELECT COUNT(*) AS count FROM doctors");
$doctorCount = $stmt->fetchColumn();

$stmt = $conn->query("SELECT COUNT(*) AS count FROM patients");
$patientCount = $stmt->fetchColumn();

$stmt = $conn->query("SELECT COUNT(*) AS count FROM appointments");
$appointmentCount = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 15px 0;
            text-align: center;
        }
        nav {
            margin: 20px;
            text-align: center;
        }
        nav a {
            margin: 0 15px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
        .overview {
            display: flex;
            justify-content: space-around;
            margin: 20px;
        }
        .overview .card {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            flex: 1;
            margin: 0 10px;
        }
        .overview .card h2 {
            margin: 0;
            font-size: 2em;
        }
        .overview .card p {
            margin: 10px 0 0;
            font-size: 1.2em;
            color: #333;
        }
        .logout {
            text-align: center;
            margin: 20px;
        }
        .logout a {
            text-decoration: none;
            color: white;
            background-color: #e74c3c;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 16px;
        }
        .logout a:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
    </header>

    <nav>
        <a href="manage_doctors.php">Manage Doctors</a>
        <a href="manage_patients.php">Manage Patients</a>
        <a href="manage_appointments.php">Manage Appointments</a>
    </nav>

    <div class="overview">
        <div class="card">
            <h2><?php echo $doctorCount; ?></h2>
            <p>Doctors</p>
        </div>
        <div class="card">
            <h2><?php echo $patientCount; ?></h2>
            <p>Patients</p>
        </div>
        <div class="card">
            <h2><?php echo $appointmentCount; ?></h2>
            <p>Appointments</p>
        </div>
    </div>

    <div class="logout">
        <a href="../logout.php">Logout</a>
    </div>
</body>
</html>
