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

// Handle case where user details are not found
if (!$user) {
    echo "<p>Error: User details not found. Please contact support.</p>";
    exit();
}

// Fetch patient_id from the users table
$stmt = $pdo->prepare("SELECT id FROM patients WHERE user_id = ?");
$stmt->execute([$user_id]);
$patient = $stmt->fetch();

if (!$patient) {
    echo "<p>Error: Patient record not found. Please contact support.</p>";
    exit();
}

$patient_id = $patient['id'];

// Fetch all appointments for the patient, ordered by most recent appointment_id
$stmt = $pdo->prepare("
    SELECT a.id AS appointment_id, u_doctor.name AS doctor_name, a.appointment_date, a.status
    FROM appointments a
    JOIN doctors d ON a.doctor_id = d.id
    JOIN users u_doctor ON d.user_id = u_doctor.id
    WHERE a.patient_id = ?
    ORDER BY a.id DESC
");
$stmt->execute([$patient_id]);
$appointments = $stmt->fetchAll();

// Handle case where no appointments are found
if ($appointments === false) {
    echo "<p>Error: Failed to fetch appointments. Please try again later.</p>";
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
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            overflow: hidden; /* Ensure content doesn't overflow */
        }
        h2 {
            color: #333;
            text-align: center;
        }
        h3 {
            color: #555;
            text-align: center;
        }
        .table-wrapper {
            max-height: 400px; /* Set the maximum height for the table */
            overflow-y: auto; /* Enable vertical scrollbar if needed */
            margin-bottom: 20px; /* Space below the table */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0; /* Remove extra margins */
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            vertical-align: middle; /* Align text vertically in the middle */
        }
        th {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
            text-align: center;
        }
        td {
            text-align: left; /* Default alignment for all cells */
        }
        td.status {
            text-align: center; /* Center the text in the status column */
            vertical-align: middle; /* Vertically center the text */
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            color: #fff;
            text-align: center;
            width: 86%; /* Make sure the status text spans the full width of the cell */
        }
        .status.Approved {
            background-color: #307f1b;
        }
        .status.Pending {
            background-color: #ffc107;
        }
        .status.Cancelled {
            background-color: #dc3545;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e9ecef;
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
        .no-appointments {
            text-align: center;
            color: #999;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?></h2>
        <h3>Your Recent Appointments</h3>
        <?php if (!empty($appointments)): ?>
            <div class="table-wrapper">
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
                                <td><?php echo htmlspecialchars(date('F j, Y, g:i a', strtotime($appointment['appointment_date']))); ?></td>
                                <td class="status <?php echo htmlspecialchars($appointment['status']); ?>">
                                    <?php echo htmlspecialchars($appointment['status']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="no-appointments">You have no appointments at this time.</p>
        <?php endif; ?>

        <div class="nav-links">
            <a href="book_appointment.php">Book New Appointment</a>
            <a href="update_profile.php">Update Profile</a>
            <a href="../logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
