<?php
require '../db.php';
session_start();
if ($_SESSION['user_type'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Fetch all patients
$stmt = $conn->query("SELECT patients.id, users.name, users.email, patients.address, users.phone_number, patients.date_of_birth FROM patients INNER JOIN users ON patients.user_id = users.id");
$patients = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Patients</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 15px 0;
            text-align: center;
        }
        nav {
            margin: 10px;
            text-align: center;
        }
        nav a {
            margin: 0 15px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
        main {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: white;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .actions a {
            text-decoration: none;
            color: #007BFF;
            margin-right: 10px;
        }
        .actions a:hover {
            text-decoration: underline;
        }
        .add-button {
            display: block;
            margin: 20px 0;
            text-align: center;
        }
        .add-button a {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        .add-button a:hover {
            background-color: #45a049;
        }
        footer {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Manage Patients</h1>
    </header>

    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="manage_doctors.php">Manage Doctors</a>
    </nav>

    <main>
        <section class="add-button">
            <a href="add_patient.php">Add New Patient</a>
        </section>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <th>Date of Birth</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($patients as $patient): ?>
                    <tr>
                        <td><?= htmlspecialchars($patient['id']) ?></td>
                        <td><?= htmlspecialchars($patient['name']) ?></td>
                        <td><?= htmlspecialchars($patient['email']) ?></td>
                        <td><?= htmlspecialchars($patient['address']) ?></td>
                        <td><?= htmlspecialchars($patient['phone_number']) ?></td>
                        <td><?= htmlspecialchars($patient['date_of_birth']) ?></td>
                        <td class="actions">
                            <a href="edit_patient.php?id=<?= htmlspecialchars($patient['id']) ?>">Edit</a> |
                            <a href="delete_patient.php?id=<?= htmlspecialchars($patient['id']) ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; 2024 Hospital Appointment System</p>
    </footer>
</body>
</html>
