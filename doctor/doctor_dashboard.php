<?php
session_start();
include '../db.php';

// Check if user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    header("Location: ../index.php");
    exit();
}

$doctor_id = $_SESSION['user_id']; 

// Fetch appointments for the logged-in doctor
$stmt = $pdo->prepare("
    SELECT a.id AS appointment_id, p.name AS patient_name, a.appointment_date, a.status
    FROM appointments a
    JOIN patients p ON a.patient_id = p.id
    JOIN doctors d ON a.doctor_id = d.id
    WHERE d.user_id = ? 
    ORDER BY a.id DESC
");
$stmt->execute([$doctor_id]);
$appointments = $stmt->fetchAll();

// Handle appointment approval and completion through AJAX request
if (isset($_POST['ajax_action'])) {
    $appointment_id = $_POST['appointment_id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        $stmt = $pdo->prepare("UPDATE appointments SET status = 'Approved' WHERE id = ?");
    } elseif ($action == 'complete') {
        $stmt = $pdo->prepare("UPDATE appointments SET status = 'Completed' WHERE id = ?");
    }

    if ($stmt->execute([$appointment_id])) {
        echo "Success";
    } else {
        echo "Failed";
    }
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
            margin-bottom: 20px;
        }

        .table-wrapper {
            max-height: 400px; /* Adjust as needed */
            overflow-y: auto;
            margin-bottom: 20px; /* Space below the table */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd; /* Add border around table */
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd; /* Add border around each cell */
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .status {
            padding: 5px;
            border-radius: 4px;
            color: white;
            text-align: center;
            display: inline-block;
            min-width: 80px;
        }

        .status.Pending {
            background-color: #ffc107;
        }

        .status.Approved {
            background-color: #28a745;
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
            background-color: #ff2f43;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
        }

        .nav-links a:hover {
            background-color: #fb0018;
        }

        .action-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-align: center;
        }

        .action-button:hover {
            background-color: #218838;
        }

        form {
            margin: 0; /* Remove default form margin */
        }

        .action-column {
            width: 120px; /* Set a fixed width for the action column */
            text-align: center;
        }
        .action-button.complete-btn {
            background-color: #15a38e;
        }
        .action-button.complete-btn:hover {
            background-color: #138496;
        }
        .status.Completed {
            background-color: #15a38e;
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
                        <th class="action-column">Action</th>
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
                                <td class="action-column">
                                    <?php if ($appointment['status'] === 'Pending'): ?>
                                        <button class="action-button approve-btn" data-id="<?php echo htmlspecialchars($appointment['appointment_id']); ?>">Approve</button>
                                    <?php elseif ($appointment['status'] === 'Approved'): ?>
                                        <button class="action-button complete-btn" data-id="<?php echo htmlspecialchars($appointment['appointment_id']); ?>">Complete</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">No appointments found.</td>
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
            $('.approve-btn, .complete-btn').click(function() {
                var button = $(this);
                var appointmentId = button.data('id');
                var action = button.hasClass('approve-btn') ? 'approve' : 'complete';

                $.ajax({
                    type: 'POST',
                    url: 'doctor_dashboard.php',
                    data: { ajax_action: true, appointment_id: appointmentId, action: action },
                    success: function(response) {
                        if (response === "Success") {
                            if (action === 'approve') {
                                button.closest('tr').find('.status').removeClass('Pending').addClass('Approved').text('Approved');
                            } else if (action === 'complete') {
                                button.closest('tr').find('.status').removeClass('Approved').addClass('Completed').text('Completed');
                            }
                            button.remove(); // Remove the button after the action
                        } else {
                            alert('Failed to process the request. Please try again.');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>