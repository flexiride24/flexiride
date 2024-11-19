<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $ride_date = $_POST['ride_date'];
    $ride_time = $_POST['ride_time'];
    $vehicle_type = $_POST['vehicle_type'];
    $seats_available = $_POST['seats_available'];
    $price = $_POST['price'];
    $user_id = $_SESSION['user_id'];

    // Validation for seats based on vehicle type
    if ($vehicle_type === 'bike' && $seats_available != 1) {
        echo "Error: For bikes, seat availability must be exactly 1.";
    } elseif ($vehicle_type === 'car' && $seats_available <= 0) {
        echo "Error: For cars, seat availability must be greater than 0.";
    } else {
        $query = "INSERT INTO rides (user_id, origin, destination, ride_date, ride_time, vehicle_type, seats_available, price) 
        VALUES ('$user_id', '$origin', '$destination', '$ride_date', '$ride_time', '$vehicle_type', '$seats_available', '$price')";

        if (mysqli_query($conn, $query)) {
            header("Location: ride_success.php"); // Redirect to success page
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Ride</title>
    <link rel="icon" href="images\favvi.png" type="image/x-icon">
    <style>
        .container {
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        input, select, button {
            margin-bottom: 10px;
            padding: 8px;
            width: 300px;
        }
        button {
            padding: 8px 16px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        #map { height: 300px; width: 100%; margin-top: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Post a Ride</h2>
    <form method="POST" action="post_ride.php">
        <label for="origin">Origin:</label><br>
        <input type="text" id="origin" name="origin" placeholder="Enter origin location" required><br>
        
        <label for="destination">Destination:</label><br>
        <input type="text" id="destination" name="destination" placeholder="Enter destination location" required><br>

        <label for="ride_date">Ride Date:</label><br>
        <input type="date" id="ride_date" name="ride_date" required><br>

        <label for="ride_time">Ride Time:</label><br>
        <input type="time" id="ride_time" name="ride_time" required><br>

        <label for="vehicle_type">Vehicle Type:</label><br>
        <select id="vehicle_type" name="vehicle_type" required>
            <option value="car">Car</option>
            <option value="bike">Bike</option>
        </select><br>

        <label for="seats_available">Seats Available:</label><br>
        <input type="number" id="seats_available" name="seats_available" required placeholder="Enter number of seats"><br>

        <div id="distance-container">
            <strong>Distance:</strong> <span id="distance"></span> km<br>
            <strong>System-generated price:</strong> Rs. <span id="system_price"></span><br>
        </div>

        <label for="price">Price (± Rs. 10 of system price):</label><br>
        <input type="number" id="price" name="price" required placeholder="Enter price"><br>

        <button type="submit" id="submit-btn">Post Ride</button>
    </form>
</div>

<script>
document.getElementById('submit-btn').addEventListener('click', function(event) {
    var startLocation = document.getElementById('origin').value;
    var endLocation = document.getElementById('destination').value;

    if (startLocation && endLocation) {
        event.preventDefault();  // Prevent form submission to wait for calculation
        calculateDistance(startLocation, endLocation)
            .then(distanceKm => {
                if (distanceKm) {
                    var systemPrice = Math.round(distanceKm * 2 / 10) * 10;
                    document.getElementById('distance').innerHTML = distanceKm.toFixed(2);
                    document.getElementById('system_price').innerHTML = systemPrice;

                    var userPrice = parseFloat(document.getElementById('price').value);

                    // Ensure the user price is within Rs. 10 of the system price
                    if (userPrice < (systemPrice - 10) || userPrice > (systemPrice + 10)) {
                        alert('Error: The price must be within Rs. 10 of the system-generated price.');
                    } else {
                        document.forms[0].submit();  // Submit the form after validation
                    }
                }
            })
            .catch(error => {
                console.error('Error calculating distance:', error);
                alert("Couldn't calculate the distance. Please try again.");
            });
    } else {
        alert('Please enter both origin and destination.');
    }
});

function calculateDistance(startLocation, endLocation) {
    var geocodeUrl = 'https://nominatim.openstreetmap.org/search?format=json&q=';

    return Promise.all([
        fetch(geocodeUrl + encodeURIComponent(startLocation)).then(response => response.json()),
        fetch(geocodeUrl + encodeURIComponent(endLocation)).then(response => response.json())
    ])
    .then(data => {
        if (data[0].length > 0 && data[1].length > 0) {
            var startCoords = [parseFloat(data[0][0].lat), parseFloat(data[0][0].lon)];
            var endCoords = [parseFloat(data[1][0].lat), parseFloat(data[1][0].lon)];

            return haversineDistance(startCoords, endCoords);
        } else {
            throw new Error("Couldn't find one or both of the locations.");
        }
    });
}

// Haversine formula to calculate distance between two points on Earth
function haversineDistance(coords1, coords2) {
    const R = 6371; // Earth radius in km
    const lat1 = coords1[0];
    const lon1 = coords1[1];
    const lat2 = coords2[0];
    const lon2 = coords2[1];

    const dLat = toRadians(lat2 - lat1);
    const dLon = toRadians(lon2 - lon1);

    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(toRadians(lat1)) * Math.cos(toRadians(lat2)) *
              Math.sin(dLon / 2) * Math.sin(dLon / 2);
    
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    return R * c; // Distance in km
}

function toRadians(deg) {
    return deg * (Math.PI / 180);
}
</script>

</body>
</html>
