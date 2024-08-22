<?php
require '../db.php';
session_start();
if ($_SESSION['user_type'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'];

// Fetch patient details
$stmt = $conn->prepare("SELECT * FROM patients INNER JOIN users ON patients.user_id = users.id WHERE patients.id = ?");
$stmt->execute([$id]);
$patient = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $date_of_birth = $_POST['date_of_birth'];

    // Update users table
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone_number = ? WHERE id = ?");
    $stmt->execute([$name, $email, $phone_number, $patient['user_id']]);

    // Update patients table
    $stmt = $conn->prepare("UPDATE patients SET address = ?, date_of_birth = ? WHERE id = ?");
    $stmt->execute([$address, $date_of_birth, $id]);

    header("Location: manage_patients.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient</title>
</head>
<body>
    <h1>Edit Patient</h1>
    <form method="post">
        <label>Name:</label>
        <input type="text" name="name" value="<?= $patient['name'] ?>" required><br>
        
        <label>Email:</label>
        <input type="email" name="email" value="<?= $patient['email'] ?>" required><br>
        
        <label>Address:</label>
        <input type="text" name="address" value="<?= $patient['address'] ?>" required><br>
        
        <label>Phone Number:</label>
        <input type="text" name="phone_number" value="<?= $patient['phone_number'] ?>" required><br>
        
        <label>Date of Birth:</label>
        <input type="date" name="date_of_birth" value="<?= $patient['date_of_birth'] ?>" required><br>
        
        <button type="submit">Update Patient</button>
    </form>
    <a href="manage_patients.php">Back to Patients</a>
</body>
</html>
