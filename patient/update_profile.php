<?php
session_start();
require '../db.php'; // Adjust the path as needed

// Check if the user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch patient details from the database
$stmt = $conn->prepare("SELECT * FROM patients WHERE user_id = ?");
$stmt->execute([$user_id]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$patient) {
    $error = "Patient data not found.";
    $patient = []; // Initialize $patient to avoid undefined variable errors
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    // Update patient details in the database
    $stmt = $conn->prepare("
        UPDATE patients 
        SET name = ?, email = ?, phone_number = ? 
        WHERE user_id = ?
    ");
    if ($stmt->execute([$name, $email, $phone_number, $user_id])) {
        $success = "Profile updated successfully.";
    } else {
        $error = "Failed to update profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Basic Reset */
        body, h1, h2, p, a, form, input {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Layout */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            background-color: #4CAF50;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }

        header h1 {
            margin-bottom: 0.5rem;
        }

        nav a {
            color: #fff;
            margin: 0 1rem;
            text-decoration: none;
        }

        nav a:hover {
            text-decoration: underline;
        }

        main {
            padding: 2rem;
            max-width: 600px;
            margin: auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 1rem;
        }

        form label {
            display: block;
            margin: 0.5rem 0;
        }

        form input {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .message {
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 4px;
            color: #fff;
        }

        .error {
            background-color: #f44336;
        }

        .success {
            background-color: #4CAF50;
        }

        footer {
            background-color: #4CAF50;
            color: #fff;
            text-align: center;
            padding: 1rem;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Update Profile</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <section>
            <?php if (isset($error)): ?>
                <div class="message error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div class="message success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <form method="post" action="update_profile.php">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($patient['name'] ?? ''); ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($patient['email'] ?? ''); ?>" required>

                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($patient['phone_number'] ?? ''); ?>" required>

                <button type="submit">Update Profile</button>
            </form
