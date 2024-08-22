<?php
require '../db.php';
session_start();
if ($_SESSION['user_type'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'];

// Fetch doctor details
$stmt = $conn->prepare("SELECT * FROM doctors INNER JOIN users ON doctors.user_id = users.id WHERE doctors.id = ?");
$stmt->execute([$id]);
$doctor = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $specialization = $_POST['specialization'];
    $phone_number = $_POST['phone_number'];

    // Update users table
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone_number = ? WHERE id = ?");
    $stmt->execute([$name, $email, $phone_number, $doctor['user_id']]);

    // Update doctors table
    $stmt = $conn->prepare("UPDATE doctors SET specialization = ? WHERE id = ?");
    $stmt->execute([$specialization, $id]);

    header("Location: manage_doctors.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Doctor</title>
</head>
<body>
    <h1>Edit Doctor</h1>
    <form method="post">
        <label>Name:</label>
        <input type="text" name="name" value="<?= $doctor['name'] ?>" required><br>
        
        <label>Email:</label>
        <input type="email" name="email" value="<?= $doctor['email'] ?>" required><br>
        
        <label>Specialization:</label>
        <input type="text" name="specialization" value="<?= $doctor['specialization'] ?>" required><br>
        
        <label>Phone Number:</label>
        <input type="text" name="phone_number" value="<?= $doctor['phone_number'] ?>" required><br>
        
        <button type="submit">Update Doctor</button>
    </form>
    <a href="manage_doctors.php">Back to Doctors</a>
</body>
</html>
