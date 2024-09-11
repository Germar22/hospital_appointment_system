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
    <title>Doctor Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .profile-card {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .profile-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 20px;
        }
        .profile-card h1 {
            font-size: 24px;
            color: #333;
            margin: 0;
        }
        .card {
            margin-bottom: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .card h2 {
            background: #007bff;
            color: #fff;
            margin: 0;
            padding: 15px;
            font-size: 18px;
            border-radius: 10px 10px 0 0;
        }
        .table-wrapper {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
        }
        .status.Pending {
            background-color: #ffc107;
            color: #fff;
        }
        .status.Approved {
            background-color: #28a745;
            color: #fff;
        }
        .status.Completed {
            background-color: #007bff;
            color: #fff;
        }
        .action-column {
            text-align: center;
        }
        .action-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .action-button:hover {
            background-color: #0056b3;
        }
        .dashboard-links {
            margin-top: 20px;
        }
        .dashboard-links a {
            display: inline-block;
            padding: 10px 20px;
            margin-right: 10px;
            color: white;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
        }
        .dashboard-links a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="navbar">
    Doctor Dashboard
</div>

<div class="container">
    <div class="profile-card">
        <img src="../uploads/<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image">
        <h1>Welcome, Doctor</h1>
    </div>

    <div class="card">
        <h2>Your Upcoming Appointments</h2>
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

    <div class="dashboard-links">
        <a href="update_profile.php">Update Profile</a>
        <a href="chat_patient.php">Chat with Patients</a>
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
                            button.remove();
                        } else if (action === 'complete') {
                            button.closest('tr').find('.status').removeClass('Approved').addClass('Completed').text('Completed');
                            button.remove();
                        }
                    } else {
                        alert('Action failed. Please try again.');
                    }
                }
            });
        });
    });
</script>
</body>
</html>
