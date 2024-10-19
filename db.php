<?php
$dsn = 'mysql:host=localhost;dbname=hospital_system';
$username = 'root'; // Adjust if needed
$password = ''; // Adjust if needed

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}
