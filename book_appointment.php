<?php
session_start();
require 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['patient_id'])) {
    die('You must be logged in to book an appointment.');
}

// Fetch available doctors for the dropdown
$stmt = $pdo->query("SELECT id, name FROM doctors");
$doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

$patient_id = $_SESSION['patient_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    // Validate input
    if (empty($doctor_id) || empty($appointment_date) || empty($appointment_time)) {
        $error = "All fields are required.";
    } else {
        // Insert appointment into the database
        $stmt = $pdo->prepare("INSERT INTO appointments (doctor_id, patient_id, appointment_date, appointment_time) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$doctor_id, $patient_id, $appointment_date, $appointment_time])) {
            echo "Appointment booked successfully.";
        } else {
            echo "Failed to book appointment.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <style>
        /* Add your CSS styles here */
    </style>
</head>
<body>
    <div class="container">
        <h1>Book an Appointment</h1>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST" action="book_appointment.php">
            <label for="doctor_id">Select Doctor:</label>
            <select id="doctor_id" name="doctor_id" required>
                <option value="">-- Select Doctor --</option>
                <?php foreach ($doctors as $doctor): ?>
                    <option value="<?php echo htmlspecialchars($doctor['id']); ?>">
                        <?php echo htmlspecialchars($doctor['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="appointment_date">Appointment Date:</label>
            <input type="date" id="appointment_date" name="appointment_date" required>
            <label for="appointment_time">Appointment Time:</label>
            <input type="time" id="appointment_time" name="appointment_time" required>
            <input type="submit" value="Book Appointment">
        </form>
        <a href="index.php" class="back-button">Back to Home</a>
    </div>
</body>
</html>
