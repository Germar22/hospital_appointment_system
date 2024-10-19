<?php
session_start();
include 'db.php'; // Ensure this file contains your database connection setup

// Initialize variables
$email = '';
$message = '';
$message_type = '';

// Handle registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $user_type = isset($_POST['user_type']) ? trim($_POST['user_type']) : '';
    $specialization = isset($_POST['specialization']) ? trim($_POST['specialization']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : ''; // Capture the address field

    // Combine start and end times into a single string for availability schedule
    $start_time = isset($_POST['start_time']) ? trim($_POST['start_time']) : '';
    $end_time = isset($_POST['end_time']) ? trim($_POST['end_time']) : '';
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

    // Check if the email already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $email_exists = $stmt->fetchColumn() > 0;

    if ($email_exists) {
        $message = "Email address is already registered. Please use a different email.";
        $message_type = 'error';
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert user data including the address
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, user_type, image, address) VALUES (?, ?, ?, ?, ?, ?)");
        try {
            $stmt->execute([$name, $email, $hashed_password, $user_type, $image, $address]);

            $user_id = $pdo->lastInsertId();

            // Insert additional data based on user type
            if ($user_type === 'doctor') {
                $stmt = $pdo->prepare("INSERT INTO doctors (user_id, name, specialization, availability_schedule) VALUES (?, ?, ?, ?)");
                $stmt->execute([$user_id, $name, $specialization, $availability_schedule]);
            } elseif ($user_type === 'patient') {
                $stmt = $pdo->prepare("INSERT INTO patients (user_id, name, email) VALUES (?, ?, ?)");
                $stmt->execute([$user_id, $name, $email]);
            }

            $_SESSION['user_id'] = $user_id;
            header("Location: index.php");
            exit();
        } catch (PDOException $e) {
            $message = "Database error: " . $e->getMessage();
            $message_type = 'error';
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        /* Add your CSS styles here */
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
            width: calc(100% - 20px); /* Adjust width to accommodate padding */
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="time"]:focus,
        select:focus {
            border-color: #007bff; /* Change border color on focus */
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
            transition: background-color 0.3s;
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
        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }
        /* Leaflet map style */
        #map {
            height: 400px; /* Set the height of the map */
            margin-bottom: 15px;
        }

    </style>
</head>
<body>

<div class="container">
    <h2>Register</h2>
    <?php if (isset($message)): ?>
        <div class="message <?php echo htmlspecialchars($message_type); ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Registration Form -->
    <form method="post" action="" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="user_type">User Type:</label>
        <select id="user_type" name="user_type" required>
            <option value="" disabled selected>Select User Type</option>
            <option value="admin">Admin</option>
            <option value="doctor">Doctor</option>
            <option value="patient">Patient</option>
        </select>

        <div id="specialization_container" class="specialization-container">
            <label for="specialization">Specialization:</label>
            <input type="text" id="specialization" name="specialization">

            <label for="availability_schedule">Availability Schedule:</label>
            <label for="start_time">Start Time:</label>
            <input type="time" id="start_time" name="start_time">
            <label for="end_time">End Time:</label>
            <input type="time" id="end_time" name="end_time">
        </div>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required> <!-- New Address Field -->

        <div id="map"></div> <!-- Map container -->

        <button type="button" id="get-location">Use My Location</button> <!-- Button to get location -->

        <label for="image">Profile Image:</label>
        <input type="file" id="image" name="image">

        <input type="submit" name="register" value="Register">
    </form>

    <div class="login-link">
        <p>Already have an account? <a href="index.php">Login here</a></p>
    </div>
</div>


<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    // Initialize the map
        var map = L.map('map').setView([6.11452, 125.17242], 13); // Default view

        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        var marker;

        // Function to update the map based on address input
        function updateMap() {
            var address = document.getElementById('address').value;
            if (address) {
                // Fetch geocode data from Nominatim
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            var lat = data[0].lat;
                            var lon = data[0].lon;

                            // Update the map view and marker
                            map.setView([lat, lon], 21);
                            if (marker) {
                                map.removeLayer(marker);
                            }
                            marker = L.marker([lat, lon]).addTo(map);
                        } else {
                            alert('Address not found. Please enter a valid address.');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching location:', error);
                        alert('An error occurred while fetching the location. Please try again.');
                    });
            }
        }

        // Add event listener to address input to update map on input change
        document.getElementById('address').addEventListener('blur', updateMap);

        // Event listener for the user type dropdown
        document.getElementById('user_type').addEventListener('change', function() {
            var specializationContainer = document.getElementById('specialization_container');
            if (this.value === 'doctor') {
                specializationContainer.style.display = 'block';
            } else {
                specializationContainer.style.display = 'none';
            }
        });

// Get user's current location
document.getElementById('get-location').addEventListener('click', function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var lat = position.coords.latitude;
            var lon = position.coords.longitude;

            // Use reverse geocoding to get address (optional)
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name) {
                        document.getElementById('address').value = data.display_name;
                        // Update the map view and marker
                        map.setView([lat, lon], 17);
                        if (marker) {
                            map.removeLayer(marker);
                        }
                        marker = L.marker([lat, lon]).addTo(map);
                    } else {
                        alert('Could not retrieve address from location.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching location:', error);
                    alert('An error occurred while fetching the address. Please try again.');
                });
        }, function(error) {
            alert('Unable to retrieve your location: ' + error.message);
        });
    } else {
        alert('Geolocation is not supported by this browser.');
    }
});

// Add click event on the map to get the clicked location's coordinates
map.on('click', function(e) {
    var lat = e.latlng.lat;
    var lon = e.latlng.lng;

    // Use reverse geocoding to get the address of the clicked location
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
        .then(response => response.json())
        .then(data => {
            if (data && data.display_name) {
                document.getElementById('address').value = data.display_name;
                // Update the map view and marker
                if (marker) {
                    map.removeLayer(marker);
                }
                marker = L.marker([lat, lon]).addTo(map);
            } else {
                alert('Could not retrieve address from the clicked location.');
            }
        })
        .catch(error => {
            console.error('Error fetching location:', error);
            alert('An error occurred while fetching the address. Please try again.');
        });
});

    // Add event listener to address input to update map on input change
    document.getElementById('address').addEventListener('blur', updateMap);

    // Event listener for the user type dropdown
    document.getElementById('user_type').addEventListener('change', function() {
        var specializationContainer = document.getElementById('specialization_container');
        if (this.value === 'doctor') {
            specializationContainer.style.display = 'block';
        } else {
            specializationContainer.style.display = 'none';
        }
    });

    // Get user's current location
    document.getElementById('get-location').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;

                // Use reverse geocoding to get address (optional)
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.display_name) {
                            document.getElementById('address').value = data.display_name;
                            // Update the map view and marker
                            map.setView([lat, lon], 17);
                            if (marker) {
                                map.removeLayer(marker);
                            }
                            marker = L.marker([lat, lon]).addTo(map);
                        } else {
                            alert('Could not retrieve address from location.');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching location:', error);
                        alert('An error occurred while fetching the address. Please try again.');
                    });
            }, function(error) {
                alert('Unable to retrieve your location: ' + error.message);
            });
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    });
</script>

</body>
</html>
