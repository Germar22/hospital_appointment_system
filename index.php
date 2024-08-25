<?php
session_start();
include 'db.php'; // Ensure this path is correct

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the query to fetch user data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $user['user_type']; // Add user type to session

        // Redirect based on user type
        if ($user['user_type'] === 'patient') {
            header("Location: user/patient_dashboard.php");
        }
        if ($user['user_type'] === 'doctor') {
            header("Location: doctor/doctor_dashboard.php");
        }
        if ($user['user_type'] === 'admin') {
            header("Location: admin/admin_dashboard.php");
        }
        exit();
    } else {
        echo 'Invalid credentials.';
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            margin: 20px;
        }
        .login-container h1 {
            margin-top: 0;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .form-group input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .register-link {
            text-align: center;
            margin-top: 15px;
        }
        .register-link a {
            color: #007bff;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
        .error {
            color: #dc3545;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h1>Login</h1>
    <?php if (isset($login_error)): ?>
        <div class="error"><?php echo htmlspecialchars($login_error); ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-group">
            <input type="submit" value="Login">
        </div>
        <div class="register-link">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </form>
</div>

</body>
</html>
