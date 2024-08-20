<?php
session_start();
require 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = ?");
    if ($stmt->execute([$delete_id])) {
        echo "Appointment deleted successfully.";
    } else {
        echo "Failed to delete appointment.";
    }
}

// Fetch and display appointments
$stmt = $pdo->query("SELECT * FROM appointments");
$appointments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .delete-btn {
            color: red;
            text-decoration: none;
        }
        .delete-btn:hover {
            text-decoration: underline;
        }
        .back-btn {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h2>Manage Appointments</h2>

    <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>

    <h3>Existing Appointments</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient ID</th>
                <th>Doctor ID</th>
                <th>Appointment Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appointments as $appointment): ?>
                <tr>
                    <td><?php echo htmlspecialchars($appointment['id']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['patient_id']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['doctor_id']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                    <td><a href="manage_appointments.php?delete_id=<?php echo $appointment['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this appointment?');">Delete</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
