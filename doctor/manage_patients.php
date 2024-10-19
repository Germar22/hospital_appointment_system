<?php
session_start();
include '../db.php'; // Adjust path if needed

// Check if the user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'doctor') {
    header("Location: login.php");
    exit();
}

// Fetch patients from the users table with user_type = 'patient'
$stmt = $pdo->prepare("SELECT id, name, email FROM users WHERE user_type = 'patient'");
$stmt->execute();
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
    </style>
</head>
<body>

<div class="navbar">
    <h1>Manage Patients</h1>
</div>

<div class="container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($patients as $patient): ?>
                <tr>
                    <td><?php echo htmlspecialchars($patient['id']); ?></td>
                    <td><?php echo htmlspecialchars($patient['name']); ?></td>
                    <td><?php echo htmlspecialchars($patient['email']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="logout">
        <a href="doctor_dashboard.php">back to Dashboard</a>
    </div>
</div>

</body>
</html>
