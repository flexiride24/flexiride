<?php
include 'db.php';
session_start();

$rides = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $vehicle_type = $_POST['vehicle_type'];

    $current_user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM rides WHERE origin = '$origin' AND destination = '$destination' AND vehicle_type = '$vehicle_type' AND user_id != '$current_user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rides[] = $row;
        }
    }

    header('Content-Type: application/json');
    echo json_encode(['rides' => $rides]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find a Ride</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f0f0f0;
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        /* Animated vehicle paths */
        /* Vehicle paths with curves */
@keyframes moveCar {
    0% { left: -150px; top: 60%; transform: rotate(0deg); }
    25% { left: 20%; top: 50%; transform: rotate(10deg); }
    50% { left: 50%; top: 55%; transform: rotate(-10deg); }
    75% { left: 80%; top: 50%; transform: rotate(5deg); }
    100% { left: 120%; top: 60%; transform: rotate(0deg); }
}

@keyframes moveBike {
    0% { left: 120%; top: 40%; transform: rotate(0deg); }
    25% { left: 80%; top: 50%; transform: rotate(-10deg); }
    50% { left: 50%; top: 45%; transform: rotate(5deg); }
    75% { left: 20%; top: 55%; transform: rotate(-5deg); }
    100% { left: -150px; top: 40%; transform: rotate(0deg); }
}

/* Other styles remain the same */
.car {
    position: absolute;
    width: 100px;
    animation: moveCar 12s ease-in-out infinite;
}

.bike {
    position: absolute;
    width: 80px;
    animation: moveBike 10s ease-in-out infinite;
}

        /* Glassmorphism form container */
        .form-container {
            backdrop-filter: blur(15px) saturate(180%);
            -webkit-backdrop-filter: blur(15px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.45);
            border-radius: 20px;
            padding: 40px;
            max-width: 400px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.18);
            text-align: center;
        }

        /* Glowing effect on inputs */
        .form-container input[type="text"],
        .form-container select {
            width: 100%;
            padding: 15px;
            border-radius: 30px;
            border: none;
            margin-bottom: 20px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-container input[type="text"]:focus,
        .form-container select:focus {
            outline: none;
            box-shadow: 0px 0px 15px rgba(0, 132, 255, 0.6);
        }

        .form-container button {
            width: 100%;
            padding: 15px;
            background-color: #007bff;
            color: white;
            font-size: 18px;
            border-radius: 30px;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        /* Title styling */
        .form-container h2 {
            margin-bottom: 30px;
            font-size: 24px;
            color: #333;
            text-transform: uppercase;
        }

        /* Responsive Vehicles */
        @media (max-width: 768px) {
            .car, .bike {
                display: none;
            }
        }
    </style>
</head>
<body>

    <!-- Animated vehicles -->
    <img src="images/l1.png" class="car" alt="car icon">
    <img src="images/bike_icon.png" class="bike" alt="bike icon">

    <!-- Form Section -->
    <div class="form-container">
        <h2>Find a Ride</h2>
        <form id="ride-form" method="post" action="">
            <label for="origin">Origin:</label>
            <input type="text" name="origin" required placeholder="Enter origin">

            <label for="destination">Destination:</label>
            <input type="text" name="destination" required placeholder="Enter destination">

            <label for="vehicle_type">Vehicle Type:</label>
            <select name="vehicle_type" required>
                <option value="car">Car</option>
                <option value="bike">Bike</option>
            </select>

            <button type="submit">Search Ride</button>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            $('#ride-form').on('submit', function (e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.post('', formData, function (response) {
                    const rides = response.rides;
                    if (rides && rides.length > 0) {
                        alert("Rides found!");
                    } else {
                        alert("No rides found.");
                    }
                });
            });
        });
    </script>
</body>
</html>
