<?php
session_start();
require '../db.php'; // Adjust the path as needed

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];

    // Update appointment in the database
    $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
    $stmt->execute([$status, $appointment_id]);

    header('Location: manage_appointments.php');
    exit();
}

// Fetch appointment details
$appointment_id = $_GET['id'];
$stmt = $conn->prepare("
    SELECT a.id, p.name AS patient_name, d.name AS doctor_name, a.appointment_date, a.status 
    FROM appointments a
    JOIN patients p ON a.patient_id = p.id
    JOIN doctors d ON a.doctor_id = d.id
    WHERE a.id = ?
");
$stmt->execute([$appointment_id]);
$appointment = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Edit Appointment</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <section>
            <h2>Appointment Details</h2>
            <form method="post" action="edit_appointment.php">
                <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointment['id']); ?>">
                <label for="patient_name">Patient Name:</label>
                <input type="text" id="patient_name" value="<?php echo htmlspecialchars($appointment['patient_name']); ?>" disabled>

                <label for="doctor_name">Doctor Name:</label>
                <input type="text" id="doctor_name" value="<?php echo htmlspecialchars($appointment['doctor_name']); ?>" disabled>

                <label for="appointment_date">Appointment Date:</label>
                <input type="text" id="appointment_date" value="<?php echo htmlspecialchars($appointment['appointment_date']); ?>" disabled>

                <label for="status">Status:</label>
                <select id="status" name="status">
                    <option value="Scheduled" <?php echo $appointment['status'] === 'Scheduled' ? 'selected' : ''; ?>>Scheduled</option>
                    <option value="Completed" <?php echo $appointment['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                    <option value="Cancelled" <?php echo $appointment['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                </select>

                <button type="submit">Update Appointment</button>
            </form>
            <a href="manage_appointments.php">Back to Appointments</a>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Hospital Appointment System</p>
    </footer>
</body>
</html>
