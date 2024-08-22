<?php
session_start();
require '../db.php'; // Adjust the path as needed

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Fetch appointments from the database
$stmt = $conn->prepare("
    SELECT a.id, p.name AS patient_name, d.name AS doctor_name, a.appointment_date, a.status 
    FROM appointments a
    JOIN patients p ON a.patient_id = p.user_id
    JOIN doctors d ON a.doctor_id = d.user_id
");
$stmt->execute();
$appointments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments</title>
    <link rel="stylesheet" href="../styles.css"> <!-- Link to your CSS file -->
    <style>
        /* Basic Reset */
        body, h1, h2, p, a, table {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Layout */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            background-color: #4CAF50;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }

        nav a {
            color: #fff;
            margin: 0 1rem;
            text-decoration: none;
        }

        nav a:hover {
            text-decoration: underline;
        }

        main {
            padding: 2rem;
            max-width: 1000px;
            margin: auto;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 0.75rem;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table a {
            text-decoration: none;
            color: #4CAF50;
        }

        table a:hover {
            text-decoration: underline;
        }

        footer {
            background-color: #4CAF50;
            color: #fff;
            text-align: center;
            padding: 1rem;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Manage Appointments</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <section>
            <h2>Appointment List</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient Name</th>
                        <th>Doctor Name</th>
                        <th>Appointment Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['id']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['doctor_name']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['status']); ?></td>
                        <td>
                            <a href="edit_appointment.php?id=<?php echo htmlspecialchars($appointment['id']); ?>">Edit</a> |
                            <a href="delete_appointment.php?id=<?php echo htmlspecialchars($appointment['id']); ?>" onclick="return confirm('Are you sure you want to delete this appointment?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="dashboard.php">Back to Dashboard</a>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Hospital Appointment System</p>
    </footer>
</body>
</html>
