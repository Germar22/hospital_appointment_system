<?php
require 'db.php'; // Include your database connection file

// Sample doctor data
$name = 'Dr. John Doe';
$specialty = 'Cardiologist';
$phone = '+1234567890';

try {
    $stmt = $pdo->prepare("INSERT INTO doctors (name, specialty, phone) VALUES (?, ?, ?)");
    $stmt->execute([$name, $specialty, $phone]);
    echo "Sample doctor added successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
