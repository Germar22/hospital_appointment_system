<?php
session_start();
include '../db.php'; // Ensure this path is correct

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch patient details (name and email)
$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Debug: Check if patient details are fetched
if (!$user) {
    echo "Error: User details not found.";
    exit();
}

// Fetch all appointments for the patient, including newly booked ones
$stmt = $pdo->prepare("SELECT d.name AS doctor_name, a.appointment_date, a.status
                       FROM appointments a
                       JOIN doctors d ON a.doctor_id = d.id
                       WHERE a.patient_id = ?
                       ORDER BY a.appointment_date");
$stmt->execute([$user_id]);
$appointments = $stmt->fetchAll();

// Debug: Check if appointments are fetched
if ($appointments === false) {
    echo "Error: Failed to fetch appointments.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        h3 {
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
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
            color: #fff;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            color: #fff;
        }
        .status.Approved {
            background-color: #28a745;
        }
        .status.Pending {
            background-color: #ffc107;
        }
        .status.Cancelled {
            background-color: #dc3545;
        }
        .nav-links {
            margin-top: 20px;
            text-align: center;
        }
        .nav-links a {
            display: inline-block;
            margin: 0 10px;
            padding: 10px 15px;
            color: #007bff;
            text-decoration: none;
            border: 1px solid #007bff;
            border-radius: 4px;
            font-size: 16px;
        }
        .nav-links a:hover {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?></h2>
        <h3>Notifications</h3>
        <?php if (!empty($appointments)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Doctor Name</th>
                        <th>Appointment Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($appointment['doctor_name']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                            <td><span class="status <?php echo htmlspecialchars($appointment['status']); ?>"><?php echo htmlspecialchars($appointment['status']); ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No appointments found.</p>
        <?php endif; ?>

        <div class="nav-links">
            <a href="book_appointment.php">Book New Appointment</a>
            <a href="update_profile.php">Update Profile</a>
            <a href="../logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
