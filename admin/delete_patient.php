<?php
require '../db.php';
session_start();
if ($_SESSION['user_type'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'];

// Delete patient
$stmt = $conn->prepare("DELETE FROM patients WHERE id = ?");
$stmt->execute([$id]);

header("Location: manage_patients.php");
