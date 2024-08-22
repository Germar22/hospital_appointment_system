<?php
require '../db.php';
session_start();
if ($_SESSION['user_type'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'];

// Delete doctor
$stmt = $conn->prepare("DELETE FROM doctors WHERE id = ?");
$stmt->execute([$id]);

header("Location: manage_doctors.php");
