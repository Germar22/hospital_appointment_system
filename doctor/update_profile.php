<?php
session_start();
include '../db.php'; // Adjust path if needed

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'doctor') {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = ""; // Initialize message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address']; // Capture address input
    
    // Handle image upload
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = time() . '_' . $_FILES['image']['name']; // Unique filename
        $target_dir = '../uploads/'; // Directory to store images
        $target_file = $target_dir . $image_name;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = $image_name;
        }
    }
    
    // Update user data including address
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, address = ?, image = COALESCE(?, image) WHERE id = ?");
    if ($stmt->execute([$name, $email, $address, $image, $user_id])) {
        $message = 'Changes have been saved.';
    } else {
        $message = 'Failed to update profile. Please try again.';
    }
}

// Fetch current user details
$stmt = $pdo->prepare("SELECT name, email, address, image FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        /* Your existing styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #007bff;
            padding: 10px;
            color: white;
            text-align: center;
        }
        .container {
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }
        .card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card h2 {
            margin-top: 0;
        }
        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
            align-items: stretch; /* Adjust alignment */
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            text-align: left; /* Align left */
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 12px; /* Increased padding */
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            font-size: 14px; /* Adjust font size */
        }
        .button-group {
            text-align: center;
            margin-top: 20px;
        }
        .button-group button,
        .button-group a {
            padding: 10px 20px;
            margin: 5px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .save-button {
            background-color: #007bff;
            border: none;
            outline: none;
        }
        .save-button:hover {
            background-color: #0056b3;
        }
        .cancel-button {
            background-color: #dc3545;
        }
        .cancel-button:hover {
            background-color: #c82333;
        }
        .notification {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-image {
            display: block;
            max-width: 150px;
            margin: 10px auto;
        }
        .container {
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
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
    <?php if (!empty($message)): ?>
        <div class="notification"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <div class="card">
        <h2>Update Profile</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
            </div>

            <div id="map"></div> <!-- Map container -->
            <button type="button" id="get-location">Use My Location</button> <!-- Button to get location -->

            <div class="form-group">
                <label for="image">Profile Image:</label>
                <input type="file" id="image" name="image" accept="image/*">
                <?php if ($user['image']): ?>
                    <img src="../uploads/<?php echo htmlspecialchars($user['image']); ?>" alt="Profile Image" class="profile-image">
                <?php endif; ?>
            </div>

            <div class="button-group">
                <button type="submit" class="save-button">Save Changes</button>
                <a href="doctor_dashboard.php" class="cancel-button">Back to Dashboard</a>
            </div>
        </form>
    </div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    // Initialize the map
    var map = L.map('map').setView([51.505, -0.09], 13); // Default view

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
                        map.setView([lat, lon], 17);
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

    // Reverse geocode function to get the address from lat/lon
    function reverseGeocode(lat, lon) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.display_name) {
                    // Update the address input field with the fetched address
                    document.getElementById('address').value = data.display_name;
                } else {
                    alert('Could not retrieve the address from this location.');
                }
            })
            .catch(error => {
                console.error('Error reverse geocoding:', error);
                alert('An error occurred while reverse geocoding the location.');
            });
    }

    // Add event listener to map for click events
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lon = e.latlng.lng;

        // Set marker at clicked location
        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker([lat, lon]).addTo(map);

        // Fetch the address from the clicked location and update the input field
        reverseGeocode(lat, lon);
    });

    // Add event listener to address input to update map on input change
    document.getElementById('address').addEventListener('blur', updateMap);

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
