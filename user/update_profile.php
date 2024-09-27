<?php
session_start();
include '../db.php'; // Adjust path if needed

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'patient') {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = ""; // Initialize message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address']; // Capture address input
    
    // Handle image upload
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = time() . '_' . $_FILES['image']['name']; // Unique filename
        $target_dir = '../uploads/'; // Directory to store images
        $target_file = $target_dir . $image_name;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = $image_name;
        }
    }
    
    // Update user data including address
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, address = ?, image = COALESCE(?, image) WHERE id = ?");
    if ($stmt->execute([$name, $email, $address, $image, $user_id])) {
        $message = 'Changes have been saved.';
    } else {
        $message = 'Failed to update profile. Please try again.';
    }
}

// Fetch current user details
$stmt = $pdo->prepare("SELECT name, email, address, image FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
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
            align-items: center;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            text-align: center;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="file"] {
            width: 100%;
            max-width: 400px;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
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
        .save-button {
            background-color: #007bff;
            border: none;
            outline: none;
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
        .profile-image {
            display: block;
            max-width: 150px;
            margin: 10px auto;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h1>Update Profile</h1>
</div>

<div class="container">
    <?php if (!empty($message)): ?>
        <div class="notification"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <div class="card">
        <h2>Update Profile</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="image">Profile Image:</label>
                <input type="file" id="image" name="image" accept="image/*">
                <?php if ($user['image']): ?>
                    <img src="../uploads/<?php echo htmlspecialchars($user['image']); ?>" alt="Profile Image" class="profile-image">
                <?php endif; ?>
            </div>
            <div class="button-group">
                <button type="submit" class="save-button">Save Changes</button>
                <a href="patient_dashboard.php" class="cancel-button">Back to Dashboard</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
