<?php
session_start();
require '../db.php'; // Adjust the path as needed

// Check if the user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header('Location: ../login.php');
    exit();
}

// Fetch doctors for the dropdown menu
$stmt = $conn->prepare("SELECT id, name FROM doctors");
$stmt->execute();
$doctors = $stmt->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $user_id = $_SESSION['user_id']; // Get the current logged-in user's ID

    // Ensure the patient exists before inserting the appointment
    $stmt = $conn->prepare("SELECT user_id FROM patients WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $patient = $stmt->fetch();

    if ($patient) {
        // Insert appointment into the database
        $stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, status) VALUES (?, ?, ?, 'Scheduled')");
        $stmt->execute([$user_id, $doctor_id, $appointment_date]);

        // Redirect to a confirmation page or back to dashboard
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Patient record not found.";
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
        /* Basic Reset */
        body, h1, h2, p, a, select, input, button {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Layout */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            background-color: #4CAF50;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }

        header h1 {
            margin-bottom: 0.5rem;
        }

        nav a {
            color: #fff;
            margin: 0 1rem;
            text-decoration: none;
        }

        nav a:hover {
            text-decoration: underline;
        }

        main {
            padding: 2rem;
            max-width: 1000px;
            margin: auto;
        }

        /* Form Styles */
        form {
            background: #fff;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin: 0.5rem 0;
        }

        input, select, button {
            width: 100%;
            padding: 0.5rem;
            margin: 0.5rem 0;
        }

        button {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        footer {
            background-color: #4CAF50;
            color: #fff;
            text-align: center;
            padding: 1rem;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Book an Appointment</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <section>
            <h2>Appointment Booking</h2>
            <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
            <form method="post" action="book_appointment.php">
                <label for="doctor">Select Doctor:</label>
                <select id="doctor" name="doctor_id" required>
                    <option value="">Select a Doctor</option>
                    <?php foreach ($doctors as $doctor): ?>
                        <option value="<?php echo htmlspecialchars($doctor['id']); ?>"><?php echo htmlspecialchars($doctor['name']); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="appointment_date">Appointment Date:</label>
                <input type="datetime-local" id="appointment_date" name="appointment_date" required>

                <button type="submit">Book Appointment</button>
            </form>
            <a href="dashboard.php">Back to Dashboard</a>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Hospital Appointment System</p>
    </footer>
</body>
</html>
