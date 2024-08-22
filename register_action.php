<?php
require 'db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $user_type = $_POST['user_type'];
    $phone_number = $_POST['phone_number'];

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, user_type, phone_number) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $password, $user_type, $phone_number]);

    echo "Registration successful! <a href='index.php'>Go to Home</a>";
}
