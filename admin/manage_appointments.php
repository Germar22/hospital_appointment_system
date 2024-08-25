<?php
session_start();
include '../db.php'; // Ensure this path is correct

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../index.php"); // Redirect to login page if not logged in or not an admin
    exit();
}

// Fetch all appointments
$stmt = $pdo->query("SELECT a.id AS appointment_id, p.name AS patient_name, d.name AS doctor_name, a.appointment_date, a.status
                      FROM appointments a
                      JOIN patients p ON a.patient_id = p.id
                      JOIN doctors d ON a.doctor_id = d.id
                      ORDER BY a.appointment_date");
$appointments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .status {
            padding: 5px;
            border-radius: 4px;
            color: white;
            text-align: center;
        }
        .status.Pending {
            background-color: #ffc107;
        }
        .status.Completed {
            background-color: #28a745;
        }
        .status.Cancelled {
            background-color: #dc3545;
        }
        .nav-links {
            text-align: center;
            margin-top: 20px;
        }
        .nav-links a {
            display: inline-block;
            margin: 5px;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
        }
        .nav-links a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Appointments</h2>
        <table>
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Patient Name</th>
                    <th>Doctor Name</th>
                    <th>Appointment Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($appointments)): ?>
                    <?php foreach ($appointments as $appointment): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($appointment['appointment_id']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['doctor_name']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                            <td><span class="status <?php echo htmlspecialchars($appointment['status']); ?>"><?php echo htmlspecialchars($appointment['status']); ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No appointments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="nav-links">
            <a href="admin_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
