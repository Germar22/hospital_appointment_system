<?php
session_start();
include '../db.php'; // Ensure this path is correct

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch doctors for dropdown by joining with users table and filtering by user_type = 'doctor'
try {
    $stmt = $pdo->query("SELECT d.id, u.name 
                         FROM doctors d 
                         JOIN users u ON d.user_id = u.id 
                         WHERE u.user_type = 'doctor'");
    $doctors = $stmt->fetchAll();

    // Debug: Check if doctors are fetched
    if (empty($doctors)) {
        $error = "No doctors found.";
    }
} catch (PDOException $e) {
    $error = "Error fetching doctors: " . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];

    // Fetch the patient ID using the user_id from the session
    try {
        $stmt = $pdo->prepare("SELECT id FROM patients WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $patient = $stmt->fetch();

        if (!$patient) {
            $error = "Patient record not found.";
        } else {
            $patient_id = $patient['id'];

            // Insert appointment into database
            try {
                $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, status, created_at, updated_at) 
                                       VALUES (?, ?, ?, 'Pending', NOW(), NOW())");
                $stmt->execute([$patient_id, $doctor_id, $appointment_date]);

                // Redirect to a confirmation page or back to the book appointment page
                header("Location: appointment_confirmation.php");
                exit();
            } catch (PDOException $e) {
                $error = "Error booking appointment: " . $e->getMessage();
            }
        }
    } catch (PDOException $e) {
        $error = "Error fetching patient record: " . $e->getMessage();
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
        select, input[type="text"], input[type="datetime-local"] {
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
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
        .back-button {
            display: block;
            text-align: center;
            margin-top: 20px;
        }
        .back-button a {
            color: #007bff;
            text-decoration: none;
        }
        .back-button a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Book Appointment</h2>

    <?php if (isset($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <label for="doctor_id">Select Doctor:</label>
        <select id="doctor_id" name="doctor_id" required>
            <option value="">Select a doctor</option>
            <?php foreach ($doctors as $doctor): ?>
                <option value="<?php echo htmlspecialchars($doctor['id']); ?>">
                    <?php echo htmlspecialchars($doctor['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="appointment_date">Appointment Date and Time:</label>
        <input type="datetime-local" id="appointment_date" name="appointment_date" required>

        <input type="submit" value="Book Appointment">
    </form>

    <div class="back-button">
        <a href="patient_dashboard.php">Back to Dashboard</a>
    </div>
</div>

</body>
</html>
