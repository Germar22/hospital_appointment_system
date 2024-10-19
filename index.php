<?php
session_start();
include 'db.php'; // Ensure this path is correct

$login_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        // Prepare and execute the query to fetch user data
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = $user['user_type']; // Add user type to session

            // Redirect based on user type
            switch ($user['user_type']) {
                case 'patient':
                    header("Location: user/patient_dashboard.php");
                    break;
                case 'doctor':
                    header("Location: doctor/doctor_dashboard.php");
                    break;
                case 'admin':
                    header("Location: admin/admin_dashboard.php");
                    break;
            }
            exit();
        } else {
            $login_error = 'Invalid email or password.';
        }
    } else {
        $login_error = 'Please fill in both fields.';
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
            background-color: #e9ecef;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center; /* Center the content inside the container */
        }
        .login-container h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: center; /* Center the form elements */
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        .form-group input {
            width: 80%; /* Adjust the width of the input fields */
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            text-align: center; /* Center the text inside the input fields */
        }
        .form-group input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 50%;
        }
        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
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
            margin-top: 15px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h1>Login</h1>
    <?php if (!empty($login_error)): ?>
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
            <p>Forgot your password? <a href="forgot_password.php">Reset it here</a></p>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </form>
</div>

</body>
</html>
