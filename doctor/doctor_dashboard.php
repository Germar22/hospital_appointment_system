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
$stmt = $pdo->prepare("
    SELECT a.id AS appointment_id, p.name AS patient_name, a.appointment_date, a.status
    FROM appointments a
    JOIN patients p ON a.patient_id = p.id
    JOIN doctors d ON a.doctor_id = d.id
    WHERE d.user_id = ? 
    ORDER BY a.appointment_date DESC
");
$stmt->execute([$doctor_id]);
$appointments = $stmt->fetchAll();

// Handle appointment approval through AJAX request
if (isset($_POST['ajax_approve'])) {
    $appointment_id = $_POST['appointment_id'];

    $stmt = $pdo->prepare("UPDATE appointments SET status = 'Approved' WHERE id = ?");
    $stmt->execute([$appointment_id]);

    echo "Success"; // Respond with success message
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - Appointments</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .table-wrapper {
            max-height: 400px; /* Adjust as needed */
            overflow-y: auto;
            border: 1px solid #ddd; /* Optional: adds a border around the table */
            border-radius: 4px; /* Optional: rounds the corners */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0; /* Remove default margin */
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
        .status.Approved {
            background-color: #307f1b;
        }
        .status.Cancelled {
            background-color: #dc3545;
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
        .action-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .action-button:hover {
            background-color: #0056b3;
        }
        form {
            margin: 0; /* Remove default form margin */
        }
        </style>
</head>
<body>
    <div class="container">
        <h2>Your Appointments</h2>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Patient Name</th>
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
                                <td><?php echo htmlspecialchars(date('F j, Y, g:i a', strtotime($appointment['appointment_date']))); ?></td>
                                <td><span class="status <?php echo htmlspecialchars($appointment['status']); ?>"><?php echo htmlspecialchars($appointment['status']); ?></span></td>
                                <td>
                                    <?php if ($appointment['status'] === 'Pending'): ?>
                                        <button class="action-button approve-btn" data-id="<?php echo htmlspecialchars($appointment['appointment_id']); ?>">Approve</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No appointments found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="nav-links">
            <a href="../logout.php">Logout</a>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.approve-btn').click(function() {
                var button = $(this);
                var appointmentId = button.data('id');

                $.ajax({
                    type: 'POST',
                    url: 'doctor_dashboard.php',
                    data: { ajax_approve: true, appointment_id: appointmentId },
                    success: function(response) {
                        if (response === "Success") {
                            button.closest('tr').find('.status').removeClass('Pending').addClass('Approved').text('Approved');
                            button.remove(); // Remove the button after approval
                        } else {
                            alert('Failed to approve the appointment. Please try again.');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>