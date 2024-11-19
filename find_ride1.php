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
            background-image: url("images/new_bg.jpg");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            font-family: 'Josefin Sans', sans-serif;
            height: 100vh;
            overflow: hidden;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Rotating Animation for Elements */
        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Floating Effect for Cards */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        /* Pulsating Button */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255, 152, 0, 0.7); }
            70% { box-shadow: 0 0 20px 20px rgba(255, 152, 0, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 152, 0, 0); }
        }

        .navbar {
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo a {
            font-size: 24px;
            font-weight: bold;
            color: #fff;
            text-decoration: none;
        }

        .nav-links {
            list-style: none;
            display: flex;
        }

        .nav-links a {
            text-decoration: none;
            color: #fff;
            font-size: 18px;
            padding: 10px;
        }

        .form {
            max-width: 600px;
            margin: 80px auto;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            backdrop-filter: blur(5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            animation: float 6s ease-in-out infinite;
        }

        .form h2 {
            text-align: center;
            color: #fff;
        }

        .form label {
            display: block;
            margin-bottom: 8px;
            color: #fff;
        }

        .form input[type="text"],
        .form select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .form button {
            width: 100%;
            padding: 12px;
            background-color: #ff9800;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            animation: pulse 2s infinite;
        }

        .form button:hover {
            background-color: #e68a00;
        }

        .gear-icon {
            font-size: 50px;
            color: rgba(255, 255, 255, 0.7);
            animation: rotate 8s linear infinite;
            position: absolute;
            top: 5%;
            left: 5%;
        }

        .gear-icon2 {
            font-size: 80px;
            color: rgba(255, 255, 255, 0.5);
            animation: rotate 12s linear infinite reverse;
            position: absolute;
            bottom: 5%;
            right: 5%;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            overflow: auto;
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
        }

        .close:hover,
        .close:focus {
            color: black;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="logo">
            <a href="#">FindMyRide</a>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="rides.php">Find</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="post_ride.php">Post</a></li>
                <li><a href="myrides.php">MyRides</a></li>
                <li><a href="#" id="logout-link">Logout</a></li>
            <?php else: ?>
                <li><a href="login1.html">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Rotating Gear Icons -->
    <i class='bx bx-cog gear-icon'></i>
    <i class='bx bx-cog gear-icon2'></i>

    <!-- Form Section -->
    <div class="form">
        <h2>Find a Ride</h2>
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

    <!-- Modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" id="close-modal">&times;</span>
            <div id="modal-body" class="avail">
                <!-- Results will be injected here -->
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#ride-form').on('submit', function (e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.post('', formData, function (response) {
                    const rides = response.rides;
                    let modalContent = '';

                    if (rides && rides.length > 0) {
                        modalContent += '<h3 class="rides-title">Available Rides:</h3><ul class="rides-list">';
                        rides.forEach(ride => {
                            modalContent += `<li class="ride-item">
                                <div class="ride-details">
                                    Ride from ${ride.origin} to ${ride.destination} on ${ride.ride_date} at ${ride.ride_time}. 
                                    Price: ₹${ride.price}
                                </div>
                            </li>`;
                        });
                        modalContent += '</ul>';
                    } else {
                        modalContent += '<p>No rides found.</p>';
                    }

                    $('#modal-body').html(modalContent);
                    $('#modal').fadeIn();
                });
            });

            $('#close-modal').on('click', function () {
                $('#modal').fadeOut();
            });

            $('#logout-link').on('click', function (e) {
                e.preventDefault();
                $.post('logout.php', function () {
                    window.location.href = 'index.php';
                });
            });
        });
    </script>

</body>
</html>
