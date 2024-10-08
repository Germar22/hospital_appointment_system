<?php
session_start();
include '../db.php'; // Adjust path if needed

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch statistics or data for dashboard
$stmt = $pdo->query("SELECT COUNT(*) FROM doctors");
$num_doctors = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM patients");
$num_patients = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM appointments");
$num_appointments = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM users");
$num_users = $stmt->fetchColumn();

// Fetch admin's profile image
$stmt = $pdo->prepare("SELECT image FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$admin = $stmt->fetch();
$profile_image = $admin['image'];

// Set the default image if no image is uploaded
if (empty($profile_image)) {
    $profile_image = 'default.jpg';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            width: 100px; /* Fixed width */
            height: 100px; /* Fixed height */
            object-fit: cover; /* Ensure the image covers the entire area */
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
    </style>
</head>
<body>

<div class="navbar">
    <h1>Admin Dashboard</h1>
</div>

<div class="container">
    <div class="profile-card">
        <img src="../uploads/<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image">
        <div>
            <h1>Welcome, Admin</h1>
            <p>Number of Doctors: <?php echo $num_doctors; ?></p>
            <p>Number of Patients: <?php echo $num_patients; ?></p>
            <p>Number of Appointments: <?php echo $num_appointments; ?></p>
            <p>Number of Users: <?php echo $num_users; ?></p>
        </div>
    </div>

    <div class="card dashboard-links">
        <h2>Manage Sections</h2>
        <a href="manage_users.php">Manage Users</a>
        <a href="manage_appointments.php">Manage Appointments</a>
        <a href="update_profile.php">Update Profile</a> <!-- Link to profile update -->
    </div>

    <div class="logout">
        <a href="../logout.php">Logout</a>
    </div>
</div>

</body>
</html>
