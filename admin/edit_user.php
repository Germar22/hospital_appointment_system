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
    
    // Fetch the user details
    $stmt = $pdo->prepare("SELECT id, name, email, user_type FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    if (!$user) {
        header("Location: manage_users.php?error=User not found");
        exit();
    }
    
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $user_type = $_POST['user_type'];

        // Update user details
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, user_type = ? WHERE id = ?");
        $stmt->execute([$name, $email, $user_type, $user_id]);
        
        header("Location: manage_users.php?message=User updated successfully");
        exit();
    }
} else {
    header("Location: manage_users.php?error=No user ID provided");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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
        }
        .card h2 {
            margin-top: 0;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
        }
        input, select {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .back {
            margin-top: 20px;
        }
        .back a {
            text-decoration: none;
            color: #007bff;
        }
        .back a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h1>Edit User</h1>
</div>

<div class="container">
    <div class="card">
        <h2>Edit User Details</h2>
        <form method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            
            <label for="user_type">User Type:</label>
            <select id="user_type" name="user_type" required>
                <option value="admin" <?php if ($user['user_type'] == 'admin') echo 'selected'; ?>>Admin</option>
                <option value="doctor" <?php if ($user['user_type'] == 'doctor') echo 'selected'; ?>>Doctor</option>
                <option value="patient" <?php if ($user['user_type'] == 'patient') echo 'selected'; ?>>Patient</option>
            </select>
            
            <input type="submit" value="Update User">
        </form>
        <div class="back">
            <a href="manage_users.php">Back to Manage Users</a>
        </div>
    </div>
</div>

</body>
</html>
