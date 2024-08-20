<?php
require 'db.php';
require 'mail_config.php'; // Ensure PHPMailer is set up correctly

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM patients WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate a unique reset token
        $reset_token = bin2hex(random_bytes(32));
        $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token expires in 1 hour

        // Store the token and expiration date
        $stmt = $pdo->prepare("UPDATE patients SET reset_token = ?, reset_expires_at = ? WHERE email = ?");
        $stmt->execute([$reset_token, $expires_at, $email]);

        // Send reset email
        $reset_link = "http://localhost/reset_password.php?token=$reset_token"; // Ensure the URL is correct
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body = "Please click the following link to reset your password: <a href='$reset_link'>$reset_link</a>";

        try {
            $mail->send();
            echo "Reset email sent.";
        } catch (Exception $e) {
            echo "Failed to send reset email: " . $mail->ErrorInfo;
        }
    } else {
        echo "No account found with that email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Password Reset</title>
</head>
<body>
    <h2>Request Password Reset</h2>
    <form method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>
