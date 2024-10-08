<?php
session_start();
include '../db.php'; // Ensure this path is correct

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../index.php"); // Redirect to login page if not logged in or not an admin
    exit();
}

// Handle appointment deletion
if (isset($_POST['delete_appointment'])) {
    $appointment_id = $_POST['appointment_id'];
    
    // Delete the appointment from the database
    $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = ?");
    if ($stmt->execute([$appointment_id])) {
        $_SESSION['success_message'] = "Appointment deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to delete appointment.";
    }

    // Redirect to manage_appointments.php to avoid resubmission
    header("Location: manage_appointments.php");
    exit();
}

// Fetch appointments data ordered by appointment_id in descending order
$stmt = $pdo->query("
    SELECT a.id AS appointment_id, u_patient.name AS patient_name, u_doctor.name AS doctor_name, a.appointment_date, a.status
    FROM appointments a
    JOIN patients p ON a.patient_id = p.id
    JOIN users u_patient ON p.user_id = u_patient.id
    JOIN doctors d ON a.doctor_id = d.id
    JOIN users u_doctor ON d.user_id = u_doctor.id
    ORDER BY a.id DESC
");
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
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .back-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            overflow-y: auto;
            max-height: 500px; /* Limit the height */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th, td.status {
            text-align: center; /* Center the status text */
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #e9ecef;
        }
        .status {
            padding: 5px 10px;
            border-radius: 4px;
            color: #fff;
            text-align: center;
            font-weight: bold;
        }
        .status.Approved {
            background-color: #307f1b; /* Bright green */
        }
        .status.Pending {
            background-color:  #ffc107; /* Bright orange */
        }
        .status.Cancelled {
            background-color: #dc3545; /* Bright red */
        }
        .status.Completed {
            background-color: #15a38e; /* Deep blue */
        }
        .no-appointments {
            text-align: center;
            color: #999;
            margin-top: 20px;
        }
        .delete-button {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .delete-button:hover {
            background-color: #c82333;
        }

        th.appointment-id-column,
        td.appointment-id-column {
            width: 100px; /* Adjust the width as needed */
        }

        /* Set a fixed width for the Action column */
        th.action-column,
        td.action-column {
            width: 150px; /* Adjust the width as needed */
            text-align: center;
        }

        /* Ensure the Action column does not shift */
        td.action-column:empty::before {
            content: "-"; /* Adds a placeholder to avoid column shifting */
            visibility: hidden;
        }

    </style>
</head>
<body>
    <div class="container">
        <a href="admin_dashboard.php" class="back-button">Back to Admin Dashboard</a>
        <h2>Manage Appointments</h2>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th class="appointment-id-column">Appointment ID</th>
                        <th>Patient Name</th>
                        <th>Doctor Name</th>
                        <th>Appointment Date</th>
                        <th>Status</th>
                        <th class="action-column">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($appointments)): ?>
                        <?php foreach ($appointments as $appointment): ?>
                            <tr>
                                <td class="appointment-id-column"><?php echo htmlspecialchars($appointment['appointment_id']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['doctor_name']); ?></td>
                                <td><?php echo htmlspecialchars(date('F j, Y, g:i a', strtotime($appointment['appointment_date']))); ?></td>
                                <td class="status <?php echo htmlspecialchars($appointment['status']); ?>">
                                    <?php echo htmlspecialchars($appointment['status']); ?>
                                </td>
                                <td class="action-column">
                                    <?php if ($appointment['status'] === 'Cancelled'): ?>
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointment['appointment_id']); ?>">
                                            <button type="submit" name="delete_appointment" class="delete-button">Delete</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="no-appointments">No appointments found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
