<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure you have PHPMailer installed via Composer

$mail = new PHPMailer(true); // Passing `true` enables exceptions

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
    $mail->SMTPAuth   = true;
    $mail->Username   = 'germarbunda75@gmail.com'; // SMTP username
    $mail->Password   = 'gxaj qwew xknq avgy'; // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('your-email@gmail.com', 'Hospital Appointment System');
} catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
}
