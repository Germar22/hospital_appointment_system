<?php
session_start();
include '../db.php'; // Adjust path if needed

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch statistics or data for dashboard
// Example: Number of doctors and patients
$stmt = $pdo->query("SELECT COUNT(*) FROM doctors");
$num_doctors = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM patients");
$num_patients = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM appointments");
$num_appointments = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM users");
$num_users = $stmt->fetchColumn();
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
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #007bff;
            padding: 10px;
            color: white;
            text-align: center;
        }
        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card h2 {
            margin-top: 0;
        }
        .logout {
            display: block;
            text-align: center;
            margin-top: 20px;
        }
        .logout a {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
        }
        .logout a:hover {
            background-color: #c82333;
        }
        .dashboard-links a {
            display: block;
            padding: 10px;
            margin: 5px 0;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
        }
        .dashboard-links a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h1>Admin Dashboard</h1>
</div>

<div class="container">
    <div class="card">
        <h2>Dashboard Overview</h2>
        <p>Number of Doctors: <?php echo $num_doctors; ?></p>
        <p>Number of Patients: <?php echo $num_patients; ?></p>
        <p>Number of Appointments: <?php echo $num_appointments; ?></p>
        <p>Number of Users: <?php echo $num_users; ?></p>
    </div>

    <div class="card dashboard-links">
        <h2>Manage Sections</h2>
        <a href="manage_users.php">Manage Users</a>
        <a href="manage_appointments.php">Manage Appointments</a>
    </div>

    <div class="logout">
        <a href="../logout.php">Logout</a>
    </div>
</div>

</body>
</html>
