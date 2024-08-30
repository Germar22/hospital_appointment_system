<?php
session_start();
include '../db.php'; // Adjust path if needed

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header("Location: ../index.php");
    exit();
}

$patient_id = $_SESSION['user_id']; 

// Fetch patient details for profile image
$stmt = $pdo->prepare("SELECT image FROM users WHERE id = ?");
$stmt->execute([$patient_id]);
$patient = $stmt->fetch();

// Set default image if the user has not uploaded one
$profile_image = !empty($patient['image']) ? $patient['image'] : 'default.jpg';

// Fetch appointments for the logged-in patient
$stmt = $pdo->prepare("
    SELECT a.id AS appointment_id, d.name AS doctor_name, a.appointment_date, a.status
    FROM appointments a
    JOIN doctors d ON a.doctor_id = d.id
    JOIN patients p ON a.patient_id = p.id
    WHERE p.user_id = ? 
    ORDER BY a.id DESC
");
$stmt->execute([$patient_id]);
$appointments = $stmt->fetchAll();

// Handle appointment cancellation through AJAX request
if (isset($_POST['ajax_action']) && $_POST['action'] == 'cancel') {
    $appointment_id = $_POST['appointment_id'];
    $stmt = $pdo->prepare("UPDATE appointments SET status = 'Cancelled' WHERE id = ?");
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
    <title>Patient Dashboard</title>
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
            padding: 10px;
            color: white;
            text-align: center;
        }
        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
        }
        .profile-card {
            display: flex;
            align-items: center;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
        }
        .profile-card p {
            margin: 5px 0;
        }
        .card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card h2 {
            margin-top: 0;
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
        .dashboard-links a {
            display: block;
            padding: 10px;
            margin: 5px 0;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
        }
        .dashboard-links a:hover {
            background-color: #0056b3;
        }
        .table-wrapper {
            margin-top: 20px;
            max-height: 400px;
            overflow-y: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center; /* Center align all cells horizontally */
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
            display: flex;
            align-items: center; /* Center vertically */
            justify-content: center; /* Center horizontally */
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
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-align: center;
        }
        .action-button:hover {
            background-color: #c82333;
        }
        .hidden {
            visibility: hidden;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h1>Patient Dashboard</h1>
</div>

<div class="container">
    <div class="profile-card">
        <img src="../uploads/<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image">

        <div>
            <h1>Welcome, Patient</h1>
        </div>
    </div>

    <div class="dashboard-links">
        <a href="book_appointment.php">Book Appointment</a>
        <a href="update_profile.php">Update Profile</a>
        <a href="../logout.php">Logout</a>
    </div>

    <div class="card">
        <h2>Your Appointments</h2>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Doctor Name</th>
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
                                <td><?php echo htmlspecialchars($appointment['doctor_name']); ?></td>
                                <td><?php echo htmlspecialchars(date('F j, Y, g:i a', strtotime($appointment['appointment_date']))); ?></td>
                                <td><span class="status <?php echo htmlspecialchars($appointment['status']); ?>"><?php echo htmlspecialchars($appointment['status']); ?></span></td>
                                <td class="action-column">
                                    <?php if ($appointment['status'] === 'Pending'): ?>
                                        <button class="action-button cancel-btn" data-id="<?php echo htmlspecialchars($appointment['appointment_id']); ?>">Cancel</button>
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

<script>
    $(document).ready(function() {
        $('.cancel-btn').click(function() {
            var button = $(this);
            var appointmentId = button.data('id');

            $.ajax({
                type: 'POST',
                url: 'patient_dashboard.php',
                data: { ajax_action: true, action: 'cancel', appointment_id: appointmentId },
                success: function(response) {
                    if (response === "Success") {
                        button.closest('tr').find('.status')
                            .removeClass('Pending')
                            .addClass('Cancelled')
                            .text('Cancelled');
                        button.addClass('hidden'); // Hide the button while preserving space
                    } else {
                        alert('Cancellation failed. Please try again.');
                    }
                }
            });
        });
    });
</script>

</body>
</html>
