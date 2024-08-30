<?php
session_start();
include '../db.php'; // Adjust path if needed

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch current admin data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    
    // Update user data
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->execute([$name, $email, $_SESSION['user_id']]);
    
    // Set success message
    $message = 'Changes have been saved.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #007bff;
            padding: 10px;
            color: white;
            text-align: center;
        }
        .container {
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }
        .card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card h2 {
            margin-top: 0;
        }
        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
            align-items: center; /* Center align input fields */
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            text-align: center;
        }
        .form-group input[type="text"],
        .form-group input[type="email"] {
            width: 100%; /* Full width within container */
            max-width: 400px; /* Restrict maximum width */
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box; /* Include padding and border in element's total width and height */
        }
        .button-group {
            text-align: center;
            margin-top: 20px;
        }
        .button-group button,
        .button-group a {
            padding: 10px 20px;
            margin: 5px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .button-group button {
            border: none;
            outline: none;
        }
        .save-button {
            background-color: #007bff;
        }
        .save-button:hover {
            background-color: #0056b3;
        }
        .cancel-button {
            background-color: #dc3545;
        }
        .cancel-button:hover {
            background-color: #c82333;
        }
        .notification {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h1>Update Profile</h1>
</div>

<div class="container">
    <?php if (isset($message)): ?>
        <div class="notification"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <div class="card">
        <h2>Update Profile</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="button-group">
                <button type="submit" class="save-button">Save Changes</button>
                <a href="admin_dashboard.php" class="cancel-button">Cancel</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
