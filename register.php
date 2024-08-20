<?php
require 'db.php';
require 'mail_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Generate a 7-character verification code
    $verification_code = substr(md5(uniqid(rand(), true)), 0, 7);

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM patients WHERE email = ?");
    $stmt->execute([$email]);
    $email_exists = $stmt->fetchColumn();

    if ($email_exists) {
        echo "Email already registered.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO patients (name, email, phone, password, verification_code) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $phone, $password, $verification_code])) {
            // Send verification email
            try {
                $mail->addAddress($email, $name);
                $mail->isHTML(true);
                $mail->Subject = 'Email Verification';
                $mail->Body = "Your verification code is: <b>$verification_code</b><br>";
                $mail->Body .= "Please enter this code on the verification page to complete your registration.";

                $mail->send();
                echo "Verification email sent. Please check your inbox.";
                
                // Redirect to verification form
                header('Location: verify_form.php?email=' . urlencode($email));
                exit();
            } catch (Exception $e) {
                echo "Failed to send verification email. Error: " . $mail->ErrorInfo;
            }
        } else {
            echo "Registration failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        input[type="text"], input[type="email"], input[type="password"] {
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form method="POST" action="register.php">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" maxlength="12" required>
            <input type="submit" value="Register">
        </form>
    </div>
</body>
</html>
