<?php
include 'db.php';
session_start();

$rides = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $vehicle_type = $_POST['vehicle_type'];

    // Get the current user ID
    $current_user_id = $_SESSION['user_id'];

    // Modify the query to exclude rides posted by the current user
    $sql = "SELECT * FROM rides WHERE origin = '$origin' AND destination = '$destination' AND vehicle_type = '$vehicle_type' AND user_id != '$current_user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rides[] = $row;
        }
    }
    
    // Return the rides as a JSON response
    header('Content-Type: application/json');
    echo json_encode(['rides' => $rides]);
    exit; // Stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find a Ride</title>
    <link rel="stylesheet" href="">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f0f0f0;
            overflow: hidden; /* Prevent scrollbars */
            font-family: "Josefin Sans", sans-serif;
        }

        .vehicles {
            position: absolute;
            display: flex;
            justify-content: space-between;
            width: 100%;
            pointer-events: none; /* Prevent mouse events */
        }

        .car, .bike {
            width: 10%; /* Adjust size of vehicles */
            animation-duration: 10s; /* Control speed */
            position: relative;
        }

        .car {
            animation-name: moveCar;
        }

        .bike {
            animation-name: moveBike;
        }

        @keyframes moveCar {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        @keyframes moveBike {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }

        .form {
            max-width: 600px;
            margin: 20px auto;
            padding: 30px;
            background-color: rgba(244, 244, 244, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            position: relative;
            z-index: 1; /* Ensure form is above animations */
        }

        .form h2 {
            text-align: center;
            color: #333;
        }

        .form label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }

        .form input[type="text"],
        .form select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .form button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="vehicles">
        <img class="car" src="https://www.pngmart.com/files/6/Red-Sports-Car-PNG-Transparent-Image.png" alt="Car">
        <img class="bike" src="https://www.pngmart.com/files/6/Motorcycle-PNG-File.png" alt="Bike">
        <img class="car" src="https://www.pngmart.com/files/5/Blue-Sports-Car-PNG-Image.png" alt="Car">
        <img class="bike" src="https://www.pngmart.com/files/6/Motorcycle-PNG-Image.png" alt="Bike">
    </div>

    <div class="form">
        <h2 style="text-align: center;">Find a Ride</h2>
        <form id="ride-form" method="post" action="">
            <label for="origin">Origin:</label>
            <input type="text" name="origin" required>
            <label for="destination">Destination:</label>
            <input type="text" name="destination" required>
            <label for="vehicle_type">Vehicle Type:</label>
            <select name="vehicle_type" required>
                <option value="car">Car</option>
                <option value="bike">Bike</option>
            </select>
            <button type="submit">Search</button>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            $('#ride-form').on('submit', function (e) {
                e.preventDefault(); // Prevent default form submission
                const formData = $(this).serialize(); // Serialize form data

                $.post('', formData, function (response) {
                    const rides = response.rides; // Assuming response structure is { rides: [...] }
                    let modalContent = '';

                    if (rides && rides.length > 0) {
                        modalContent += '<h3 class="rides-title">Available Rides:</h3><ul class="rides-list">';
                        rides.forEach(ride => {
                            modalContent += `<li class="ride-item">
                                Ride from ${ride.origin} to ${ride.destination} on ${ride.ride_date} at ${ride.ride_time}. 
                                Price: ₹${ride.price}
                            </li>`;
                        });
                        modalContent += '</ul>';
                    } else {
                        modalContent = '<div class="no-rides">No rides found.</div>';
                    }

                    alert(modalContent); // Display available rides in an alert for simplicity
                }, 'json');
            });
        });
    </script>
</body>
</html>
