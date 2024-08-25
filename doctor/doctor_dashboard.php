<?php
session_start();
include '../db.php'; // Adjust path if needed

// Check if the user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'doctor') {
    header("Location: login.php");
    exit();
}

// Fetch appointments with patient and doctor details
$stmt = $pdo->prepare("SELECT a.id, a.appointment_date, a.status, u.name AS patient_name, d.name AS doctor_name 
                       FROM appointments a 
                       JOIN users u ON a.patient_id = u.id 
                       JOIN doctors d ON a.doctor_id = d.user_id 
                       WHERE d.user_id = :doctor_id");
$stmt->execute(['doctor_id' => $_SESSION['user_id']]);
$appointments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .logout, .manage-buttons {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .logout a, .manage-buttons a {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            margin: 0 10px;
        }
        .logout a:hover, .manage-buttons a:hover {
            background-color: #0056b3;
        }
        .logout a.logout-btn {
            background-color: #dc3545;
        }
        .logout a.logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h1>Doctor Dashboard</h1>
</div>

<div class="container">
    <h2>Upcoming Appointments</h2>
    <table>
        <thead>
            <tr>
                <th>Appointment ID</th>
                <th>Patient Name</th>
                <th>Appointment Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appointments as $appointment): ?>
                <tr>
                    <td><?php echo htmlspecialchars($appointment['id']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['status']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Manage Patients and Update Profile Buttons -->
    <div class="manage-buttons">
        <a href="manage_patients.php">Manage Patients</a>
        <a href="update_profile.php">Update Profile</a>
    </div>

    <!-- Logout Button -->
    <div class="logout">
        <a href="../logout.php" class="logout-btn">Logout</a>
    </div>
</div>

</body>
</html>
