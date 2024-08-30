<?php
session_start();
include '../db.php'; // Ensure this path is correct

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'patient') {
    header("Location: ../index.php"); // Redirect to login page if not logged in or not a patient
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch patient details (name, email, and image)
$stmt = $pdo->prepare("SELECT name, email, image FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Handle case where user details are not found
if (!$user) {
    echo "<p>Error: User details not found. Please contact support.</p>";
    exit();
}

// Handle profile image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image'])) {
        $file = $_FILES['image'];
        $upload_dir = '../uploads/';
        $upload_file = $upload_dir . basename($file['name']);
        $file_type = strtolower(pathinfo($upload_file, PATHINFO_EXTENSION));

        // Check if file is a valid image
        $allowed_types = ['jpg', 'jpeg', 'png'];
        if (in_array($file_type, $allowed_types) && $file['size'] <= 2000000) {
            if (move_uploaded_file($file['tmp_name'], $upload_file)) {
                // Update the user image in the database
                $stmt = $pdo->prepare("UPDATE users SET image = ? WHERE id = ?");
                $stmt->execute([basename($file['name']), $user_id]);
                header("Location: update_profile.php"); // Refresh the page to reflect changes
                exit();
            } else {
                echo "<p>Error: File upload failed. Please try again.</p>";
            }
        } else {
            echo "<p>Error: Invalid file type or size. Please upload a JPG, JPEG, or PNG image under 2MB.</p>";
        }
    }
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
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
            text-align: center;
        }
        .profile {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 15px;
        }
        .profile-info {
            display: flex;
            flex-direction: column;
        }
        .profile-info h3 {
            margin: 0;
            font-size: 22px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .form-group input[type="file"] {
            border: none;
        }
        .form-group button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
        .nav-links {
            margin-top: 20px;
            text-align: center;
        }
        .nav-links a {
            display: inline-block;
            margin: 0 10px;
            padding: 10px 15px;
            color: #007bff;
            text-decoration: none;
            border: 1px solid #007bff;
            border-radius: 4px;
            font-size: 16px;
        }
        .nav-links a:hover {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Profile</h2>
        <div class="profile">
            <img src="../uploads/<?php echo htmlspecialchars($user['image']); ?>" alt="Profile Image" onerror="this.onerror=null; this.src='../uploads/default-profile.png';">
            <div class="profile-info">
                <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                <p><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
        </div>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="image">Profile Image</label>
                <input type="file" id="image" name="image">
            </div>
            <div class="form-group">
                <button type="submit">Update Profile</button>
            </div>
        </form>
        <div class="nav-links">
            <a href="patient_dashboard.php">Back to Dashboard</a>
            <a href="../logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
