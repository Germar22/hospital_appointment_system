<?php
session_start();
include 'db.php';
require 'vendor/autoload.php'; // Ensure you have PHPMailer installed via Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $user_type = $_POST['user_type'];
    $specialization = isset($_POST['specialization']) ? $_POST['specialization'] : '';

    // Combine start and end times into a single string for availability schedule
    $start_time = isset($_POST['start_time']) ? $_POST['start_time'] : '';
    $end_time = isset($_POST['end_time']) ? $_POST['end_time'] : '';
    $availability_schedule = $start_time && $end_time ? "$start_time to $end_time" : '';

    // Handle image upload
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = time() . '_' . $_FILES['image']['name']; // Unique filename
        $target_dir = 'uploads/'; // Directory to store images
        $target_file = $target_dir . $image_name;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = $image_name;
        }
    }

    // Generate a 6-character verification code
    $verification_code = random_int(100000, 999999);

    // Store the verification code in session
    $_SESSION['verification_code'] = $verification_code;

    // Send the verification code to the user's email using Gmail SMTP
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'germarbunda75@gmail.com'; // Replace with your Gmail email address
        $mail->Password = 'enwu ytph qccq eaia';    // Replace with your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('your-email@gmail.com', 'Your App Name');
        $mail->addAddress($email); // Send to the user's email

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Verification Code';
        $mail->Body = "Your verification code is <strong>$verification_code</strong>";

        $mail->send();
        echo 'Verification code sent to your email.';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        exit();
    }

    // Validate the verification code
    $entered_code = $_POST['verification_code'];
    if ($entered_code != $_SESSION['verification_code']) {
        echo "Invalid verification code. Please try again.";
        exit();
    }

    // Insert user data
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, user_type, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $password, $user_type, $image]);

    $user_id = $pdo->lastInsertId();

    // Insert additional data based on user type
    if ($user_type === 'doctor') {
        $stmt = $pdo->prepare("INSERT INTO doctors (user_id, name, specialization, availability_schedule) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $name, $specialization, $availability_schedule]);
    } elseif ($user_type === 'patient') {
        $stmt = $pdo->prepare("INSERT INTO patients (user_id, name, email) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $name, $email]);
    }

    // Clear the verification code from the session after successful registration
    unset($_SESSION['verification_code']);

    $_SESSION['user_id'] = $user_id;
    header("Location: index.php");
    exit();
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
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="time"],
        textarea,
        select,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .specialization-container {
            display: none;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        .login-link a {
            color: #007bff;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Register</h2>
    <form method="post" action="" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="user_type">User Type:</label>
        <select id="user_type" name="user_type" required>
            <option value="">Select user type</option>
            <option value="admin">Admin</option>
            <option value="doctor">Doctor</option>
            <option value="patient">Patient</option>
        </select>

        <div id="specialization_container" class="specialization-container">
            <label for="specialization">Specialization:</label>
            <input type="text" id="specialization" name="specialization">

            <label for="availability_schedule">Availability Schedule:</label>
            <input type="time" id="start_time" name="start_time">
            <input type="time" id="end_time" name="end_time">
            <p>Provide the available start and end times.</p>
        </div>

        <label for="image">Profile Image:</label>
        <input type="file" id="image" name="image" accept="image/*">

        <label for="verification_code">Verification Code:</label>
        <input type="text" id="verification_code" name="verification_code" maxlength="6" required>

        <input type="submit" value="Register">
    </form>

    <div class="login-link">
        <p>Already have an account? <a href="index.php">Login here</a></p>
    </div>
</div>

<script>
    document.getElementById('user_type').addEventListener('change', function() {
        var specializationContainer = document.getElementById('specialization_container');
        if (this.value === 'doctor') {
            specializationContainer.style.display = 'block';
        } else {
            specializationContainer.style.display = 'none';
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        var notificationMessage = document.createElement('p');
        notificationMessage.textContent = "A verification code has been sent to your email. Please enter the code below.";
        document.querySelector('.container').insertBefore(notificationMessage, document.querySelector('form'));
    });
</script>

</body>
</html>
