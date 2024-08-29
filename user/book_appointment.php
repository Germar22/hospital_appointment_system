<?php
session_start();
include '../db.php'; // Ensure this path is correct

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header("Location: ../index.php"); // Redirect to login page if not logged in or not a patient
    exit();
}

$patient_user_id = $_SESSION['user_id']; // Fetch the patient_id from session

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];

    // Ensure form data is valid
    if (empty($doctor_id) || empty($appointment_date)) {
        $error_message = "Doctor ID and appointment date are required.";
    } elseif (strtotime($appointment_date) < time()) {
        $error_message = "The appointment date cannot be in the past.";
    } else {
        // Check if the patient exists and fetch patient name
        $stmt = $pdo->prepare("SELECT id, name FROM patients WHERE user_id = ?");
        $stmt->execute([$patient_user_id]);
        $patient = $stmt->fetch();

        if (!$patient) {
            $error_message = "No patient record found for the logged-in user.";
        } else {
            $patient_id = $patient['id'];
            $patient_name = $patient['name'];

            // Get doctor name
            $stmt = $pdo->prepare("SELECT name FROM doctors WHERE id = ?");
            $stmt->execute([$doctor_id]);
            $doctor = $stmt->fetch();

            if (!$doctor) {
                $error_message = "Invalid doctor ID.";
            } else {
                $doctor_name = $doctor['name'];

                // Prepare and execute SQL statement with patient_name and doctor_name
                $stmt = $pdo->prepare("
                    INSERT INTO appointments (patient_id, doctor_id, patient_name, doctor_name, appointment_date, status, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, 'Pending', NOW(), NOW())
                ");
                if ($stmt->execute([$patient_id, $doctor_id, $patient_name, $doctor_name, $appointment_date])) {
                    $success_message = "Appointment booked successfully.";
                    // Redirect or show success message
                    header("Location: patient_dashboard.php"); // Adjust as needed
                    exit();
                } else {
                    $error_message = "Failed to book appointment. Please try again.";
                }
            }
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            text-align: left;
            color: #555;
            font-size: 14px;
        }
        input[type="datetime-local"], select, button {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            width: 100%;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: left;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Book an Appointment</h2>
        <?php if (isset($success_message)): ?>
            <div class="message success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <label for="doctor_id">Select Doctor:</label>
            <select id="doctor_id" name="doctor_id" required>
                <option value="" disabled selected>Select a doctor</option>
                <?php
                // Fetch available doctors
                $stmt = $pdo->query("SELECT id, name FROM doctors");
                while ($row = $stmt->fetch()) {
                    echo "<option value=\"" . htmlspecialchars($row['id']) . "\">" . htmlspecialchars($row['name']) . "</option>";
                }
                ?>
            </select>
            <label for="appointment_date">Appointment Date:</label>
            <input type="datetime-local" id="appointment_date" name="appointment_date" required>
            <button type="submit">Book Appointment</button>
        </form>
    </div>
</body>
</html>
