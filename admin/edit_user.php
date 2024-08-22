<?php
session_start();
require '../db.php'; // Adjust the path as needed

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $user_type = $_POST['user_type'];

    // Update user details in the database
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, user_type = ? WHERE id = ?");
    $stmt->execute([$name, $email, $user_type, $user_id]);

    header('Location: manage_users.php');
    exit();
}

// Fetch user details
$user_id = $_GET['id'];
$stmt = $conn->prepare("SELECT id, name, email, user_type FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Basic Reset */
        body, h1, h2, p, a, form, input, select, button {
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
            max-width: 800px;
            margin: auto;
        }

        /* Form Styles */
        form {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        form input, form select {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        form button {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 0.75rem;
            border-radius: 4px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #45a049;
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
        <h1>Edit User</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <section>
            <h2>User Details</h2>
            <form method="post" action="edit_user.php">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                <label for="user_type">User Type:</label>
                <select id="user_type" name="user_type">
                    <option value="admin" <?php echo $user['user_type'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="doctor" <?php echo $user['user_type'] === 'doctor' ? 'selected' : ''; ?>>Doctor</option>
                    <option value="patient" <?php echo $user['user_type'] === 'patient' ? 'selected' : ''; ?>>Patient</option>
                </select>

                <button type="submit">Update User</button>
            </form>
            <a href="manage_users.php">Back to Users</a>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Hospital Appointment System</p>
    </footer>
</body>
</html>
