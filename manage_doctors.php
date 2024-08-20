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
    $stmt = $pdo->prepare("DELETE FROM doctors WHERE id = ?");
    if ($stmt->execute([$delete_id])) {
        echo "Doctor deleted successfully.";
    } else {
        echo "Failed to delete doctor.";
    }
}

// Fetch and display doctors
$stmt = $pdo->query("SELECT * FROM doctors");
$doctors = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Doctors</title>
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
    <h2>Manage Doctors</h2>
    <form method="post" action="manage_doctors.php">
        <label for="name">Name:</label>
        <input type="text" name="name" required>
        <label for="specialty">Specialty:</label>
        <input type="text" name="specialty" required>
        <label for="phone">Phone:</label>
        <input type="text" name="phone" required>
        <button type="submit">Add Doctor</button>
    </form>

    <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>

    <h3>Existing Doctors</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Specialty</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($doctors as $doctor): ?>
                <tr>
                    <td><?php echo htmlspecialchars($doctor['id']); ?></td>
                    <td><?php echo htmlspecialchars($doctor['name']); ?></td>
                    <td><?php echo htmlspecialchars($doctor['specialty']); ?></td>
                    <td><?php echo htmlspecialchars($doctor['phone']); ?></td>
                    <td><a href="manage_doctors.php?delete_id=<?php echo $doctor['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this doctor?');">Delete</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
