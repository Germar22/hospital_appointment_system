<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $verification_code = $_POST['verification_code'];

    // Validate input
    if (empty($email) || empty($verification_code)) {
        $error = "Email and verification code are required.";
    } else {
        // Check if the code matches
        $stmt = $pdo->prepare("SELECT verification_code FROM patients WHERE email = ?");
        $stmt->execute([$email]);
        $stored_code = $stmt->fetchColumn();

        if ($stored_code === $verification_code) {
            // Code matches, mark user as verified
            $stmt = $pdo->prepare("UPDATE patients SET verified = 1 WHERE email = ?");
            if ($stmt->execute([$email])) {
                echo "Your email has been verified successfully.";
            } else {
                echo "Failed to verify your email.";
            }
        } else {
            $error = "Invalid verification code.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .container {
            width: 90%;
            max-width: 500px;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        h2 {
            margin-top: 0;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: #fff;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Verify Your Email</h2>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST" action="verify_form.php">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="verification_code">Verification Code:</label>
            <input type="text" id="verification_code" name="verification_code" required>
            <input type="submit" value="Verify">
        </form>
    </div>
</body>
</html>
