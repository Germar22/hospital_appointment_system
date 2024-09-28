<?php
session_start();
include '../db.php';

// Check if user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    header("Location: ../index.php");
    exit();
}

$doctor_id = $_SESSION['user_id'];

// Fetch doctor details for profile image
$stmt = $pdo->prepare("SELECT image FROM users WHERE id = ?");
$stmt->execute([$doctor_id]);
$doctor = $stmt->fetch();

// Set default image if the user has not uploaded one
$profile_image = !empty($doctor['image']) ? $doctor['image'] : 'default.jpg';

// Fetch appointments for the logged-in doctor
$stmt = $pdo->prepare("
    SELECT a.id AS appointment_id, p.name AS patient_name, a.appointment_date, a.status
    FROM appointments a
    JOIN patients p ON a.patient_id = p.id
    WHERE a.doctor_id = ? 
    ORDER BY a.id DESC
");
$stmt->execute([$doctor_id]);
$appointments = $stmt->fetchAll();

// Handle appointment approval and completion through AJAX request
if (isset($_POST['ajax_action']) && $_POST['action'] == 'approve') {
    $appointment_id = $_POST['appointment_id'];
    $stmt = $pdo->prepare("UPDATE appointments SET status = 'Approved' WHERE id = ?");
    if ($stmt->execute([$appointment_id])) {
        echo "Success";
    } else {
        echo "Failed";
    }
    exit();
}

if (isset($_POST['ajax_action']) && $_POST['action'] == 'complete') {
    $appointment_id = $_POST['appointment_id'];
    $stmt = $pdo->prepare("UPDATE appointments SET status = 'Completed' WHERE id = ?");
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
    <title>Doctor Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
        }
        .navbar {
            background-color: #007bff;
            padding: 10px;
            color: white;
            text-align: center;
            margin-bottom: 10px;
        }
        .container {
            display: flex;
            height: calc(100vh - 50px);
            padding: 10px;
            gap: 10px;
            overflow-y: auto;
        }
        .profile-section, .appointments-section {
            flex: 1;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            overflow-y: auto;
        }
        .profile-card {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile-card img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 20px;
        }
        .profile-card h1 {
            margin: 0;
            font-size: 1.5em;
        }
        .dashboard-links a {
            display: block;
            padding: 10px;
            margin: 5px 0;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
            transition: background-color 0.3s;
        }
        .dashboard-links a:hover {
            background-color: #0056b3;
        }
        .card {
            margin-top: 20px;
        }
        .table-wrapper {
            margin-top: 10px;
            max-height: 300px;
            overflow-y: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
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
            display: inline-block;
            padding: 5px;
            border-radius: 4px;
            color: white;
            min-width: 80px;
        }
        .status.Pending {
            background-color: #ffc107;
        }
        .status.Approved {
            background-color: #307f1b;
        }
        .status.Completed {
            background-color: #15a38e;
        }
        .status.Cancelled {
            background-color: #dc3545;
        }
        .action-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .action-button:hover {
            background-color: #218838;
        }
        .action-column {
            width: 120px;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .appointments-section {
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>

<div class="navbar">
    <h1>Doctor Dashboard</h1>
</div>

<div class="container">
    <div class="profile-section">
        <div class="profile-card">
            <img src="../uploads/<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image">
            <div>
                <h1>Welcome, Doctor</h1>
            </div>
        </div>

        <div class="dashboard-links">
            <a href="update_profile.php">Update Profile</a>
            <a href="chat_patient.php">Chat with Patients</a> <!-- Link to chat page -->
            <a href="../logout.php">Logout</a>
        </div>
    </div>

    <div class="appointments-section">
        <div class="card">
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
        </div>
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
                data: { ajax_action: true, action: 'approve', appointment_id: appointmentId },
                success: function(response) {
                    if (response === "Success") {
                        var row = button.closest('tr');
                        row.find('.status')
                            .removeClass('Pending')
                            .addClass('Approved')
                            .text('Approved');
                        button.remove(); // Remove the approve button after approving
                    } else {
                        alert('Approval failed. Please try again.');
                    }
                }
            });
        });

        // Handle the Complete button click
        $('.complete-btn').click(function() {
            var button = $(this);
            var appointmentId = button.data('id');

            $.ajax({
                type: 'POST',
                url: 'doctor_dashboard.php',
                data: { ajax_action: true, action: 'complete', appointment_id: appointmentId },
                success: function(response) {
                    if (response === "Success") {
                        var row = button.closest('tr');
                        row.find('.status')
                            .removeClass('Approved')
                            .addClass('Completed')
                            .text('Completed');
                        button.remove(); // Remove the complete button after completing
                    } else {
                        alert('Completion failed. Please try again.');
                    }
                }
            });
        });
    });
</script>

</body>
</html>
