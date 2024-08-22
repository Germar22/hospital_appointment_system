<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Hospital Appointment System</h1>
        <nav>
            <a href="index.php">Home</a>
        </nav>
    </header>
    <main>
        <section>
            <h2>Register</h2>
            <form method="post" action="register_action.php">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <label for="user_type">User Type:</label>
                <select id="user_type" name="user_type" required>
                    <option value="doctor">Doctor</option>
                    <option value="patient">Patient</option>
                </select>

                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" required>

                <button type="submit">Register</button>
            </form>
            <a href="index.php">Back to Home</a>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Hospital Appointment System</p>
    </footer>
</body>
</html>
