<?php
session_start();
include '../db.php'; // Adjust path if needed

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Check if the user_id is provided
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Check if the user exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_exists = $stmt->fetchColumn();

    if ($user_exists) {
        // Delete the user
        try {
            $pdo->beginTransaction();
            
            // Delete user data from related tables if needed
            $pdo->prepare("DELETE FROM appointments WHERE patient_id = ?")->execute([$user_id]);
            $pdo->prepare("DELETE FROM patients WHERE user_id = ?")->execute([$user_id]);
            $pdo->prepare("DELETE FROM doctors WHERE user_id = ?")->execute([$user_id]);

            // Finally, delete the user
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            
            $pdo->commit();
            header("Location: manage_users.php?message=User deleted successfully");
        } catch (Exception $e) {
            $pdo->rollBack();
            header("Location: manage_users.php?error=Failed to delete user");
        }
    } else {
        header("Location: manage_users.php?error=User not found");
    }
} else {
    header("Location: manage_users.php?error=No user ID provided");
}
