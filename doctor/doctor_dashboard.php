<?php
session_start();
include '../db.php'; // Ensure this path is correct

// Check if user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    header("Location: ../index.php"); // Redirect to login page if not logged in or not a doctor
    exit();
}

$doctor_id = $_SESSION['user_id']; // Assuming doctor_id is stored in session after login

// Fetch appointments for the logged-in doctor
$stmt = $pdo->prepare("SELECT a.id AS appointment_id, p.name AS patient_name, d.name AS doctor_name, a.appointment_date, a.status
                      FROM appointments a
                      JOIN patients p ON a.patient_id = p.id
                      JOIN doctors d ON a.doctor_id = d.id
                      WHERE d.user_id = ? 
                      ORDER BY a.appointment_date");
$stmt->execute([$doctor_id]);
$appointments = $stmt->fetchAll();

// Handle appointment approval
if (isset($_POST['approve'])) {
    $appointment_id = $_POST['appointment_id'];

    $stmt = $pdo->prepare("UPDATE appointments SET status = 'Approved' WHERE id = ?");
    $stmt->execute([$appointment_id]);

    // Notify admin and patient
    // ...

    header("Location: doctor_dashboard.php"); // Redirect after update
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - Appointments</title>
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
        .status.Approved {
            background-color: #007bff;
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
        <h2>Your Appointments</h2>
        <table>
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Patient Name</th>
                    <th>Doctor Name</th>
                    <th>Appointment Date</th>
                    <th>Status</th>
                    <th>Action</th>
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
                            <td>
                                <?php if ($appointment['status'] === 'Pending'): ?>
                                    <form method="post" action="">
                                        <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointment['appointment_id']); ?>">
                                        <button type="submit" name="approve">Approve</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No appointments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="nav-links">
            <a href="doctor_dashboard.php">Back to Dashboard</a>
        </div>

        <div class="nav-links">
            <a href="../logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
