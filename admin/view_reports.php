<?php
session_start();
require '../db.php'; // Adjust the path as needed

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Fetch reports from the database
$stmt = $conn->prepare("
    SELECT report_id, report_name, report_date, report_status
    FROM reports
");
$stmt->execute();
$reports = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reports</title>
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

        header h1 {
            margin-bottom: 0.5rem;
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
        <h1>View Reports</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <section>
            <h2>Report List</h2>
            <table>
                <thead>
                    <tr>
                        <th>Report ID</th>
                        <th>Report Name</th>
                        <th>Report Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports as $report): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($report['report_id']); ?></td>
                        <td><?php echo htmlspecialchars($report['report_name']); ?></td>
                        <td><?php echo htmlspecialchars($report['report_date']); ?></td>
                        <td><?php echo htmlspecialchars($report['report_status']); ?></td>
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
