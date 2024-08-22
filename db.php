<?php
$host = 'localhost'; // or your database host
$db = 'hospital_system'; // your database name
$user = 'root'; // your database user
$pass = ''; // your database password

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
