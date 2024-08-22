<?php
session_start();
if ($_SESSION['user_type'] != 'doctor') {
    header("Location: ../index.php");
    exit();
}

require '../db.php';

$doctor_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    // Update profile
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone_number = ? WHERE id = ?");
    $stmt->execute([$name, $email, $phone_number, $doctor_id]);

    echo "Profile updated successfully.";
}

// Fetch current profile details
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$doctor_id]);
$doctor = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
    <h1>Update Profile</h1>
    <form method="post">
        <label>Name:</label>
        <input type="text" name="name" value="<?= $doctor['name'] ?>" required><br>
        
        <label>Email:</label>
        <input type="email" name="email" value="<?= $doctor['email'] ?>" required><br>
        
        <label>Phone Number:</label>
        <input type="text" name="phone_number" value="<?= $doctor['phone_number'] ?>" required><br><br>
        
        <button type="submit">Update Profile</button>
    </form>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
