<?php
require 'db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $phone_number = $_POST['phone_number'];
    
    // Set user type as 'admin'
    $user_type = 'admin';

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, user_type, phone_number) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $password, $user_type, $phone_number]);

    echo "Admin registration successful! <a href='index.php'>Go to Home</a>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Hospital Appointment System</h1>
        <nav>
            <a href="index.php">Home</a>
        </nav>
    </header>
    <main>
        <section>
            <h2>Admin Registration</h2>
            <form method="post" action="admin_register.php">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" required>

                <button type="submit">Register Admin</button>
            </form>
            <a href="index.php">Back to Home</a>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Hospital Appointment System</p>
    </footer>
</body>
</html>
