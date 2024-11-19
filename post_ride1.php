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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Sofadi+One&display=swap" rel="stylesheet">
    <style>
        body {
            background-image: url("images/nikki.jpg");
            background-repeat: no-repeat; 
            background-size: cover; 
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            font-family: "Josefin Sans", sans-serif;
            text-transform: capitalize;
            font-size: large;
            height: 100vh;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        /* Navigation Bar */
        .navbar {
            background-color: #000;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0px 10px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }
        
        .logo a {
            font-size: 24px;
            font-weight: bold;
            color: #fff;
            text-decoration: none;
            letter-spacing: 2px;
            transition: color 0.3s ease;
        }
        
        .logo a:hover {
            color: #ff9800;
        }
        
        /* Navigation Links */
        .nav-links {
            list-style: none;
            display: flex;
        }
        
        .nav-links li {
            margin-left: 20px;
        }
        
        .nav-links a {
            text-decoration: none;
            color: #fff;
            font-size: 18px;
            transition: color 0.3s ease, background-color 0.3s ease;
            padding: 8px 16px;
            border-radius: 4px;
        }
        
        /* Hover Effects */
        .nav-links a:hover {
            color: #ff9800;
            background-color: rgba(255, 152, 0, 0.2);
        }
        
        /* Active/Current Page Link */
        .nav-links a.active {
            color: #ff9800;
            background-color: rgba(255, 152, 0, 0.4);
        }
        
        /* Responsive Design for smaller screens */
        @media screen and (max-width: 768px) {
            .navbar {
                flex-direction: column;
            }
            .nav-links {
                flex-direction: column;
                align-items: center;
                margin-top: 10px;
            }
            .nav-links li {
                margin-left: 0;
                margin-bottom: 0;
            }
        }
        
        .post {
            font-family: "Josefin Sans", sans-serif;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(244, 244, 244, 0.1);
            border-radius: 10px;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.9);
        }
        .post:hover {
            transform: translateY(-10px);
        }

        .post label {
            display: block;
            margin-bottom: 5px;
            font-size: 1.1em;
        }

        .post input, .post select {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
        }

        .post input:hover, .post select:hover {
            border-color: #3b6faa; /* Change border color on hover */
            transform: translateY(-3px); /* Lift effect */
        }

        .post input:focus, .post select:focus {
            border-color: #3b6faa; /* Change border color on focus */
            box-shadow: 0 0 5px rgba(59, 106, 170, 0.5); /* Lift effect */
            outline: none; /* Remove default outline */
        }

        .post button {
            font-family: "Josefin Sans", sans-serif;
            width: 100%;
            padding: 12px;
            background-color: #3b6faa;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .icon {
            margin-right: 5px;
        }
        /* Error message styling */
        .error {
            color: red;
            display: none;
            font-size: 0.9em;
        }
        .error.active {
            display: block;
        }
        /* Blur effect */
        .blur {
            filter: blur(5px);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
        }
    </style>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="images\favvi.png" type="image/x-icon">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="images/logo1.png" alt="logo" height="30%" width="30%">
        </div>
        <img src="images/name.png" alt="logo" height="20%" width="20%">
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="rides.php">Find</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="post_ride.php">Post</a></li>
                <li><a href="myrides.php">MyRides</a></li>
                <li><a href="#" id="logout-link">Logout</a></li>
                <li><a href="a1.php"><i class='bx bxs-user bx-sm'></i></a></li>
            <?php else: ?>
                <li><a href="login1.html">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="blur"></div>

    <div class="post">
        <form method="post" action="">
            <h2 style="text-align:center;">Post a Ride</h2>
            <label for="origin">Origin:</label>
            <input type="text" name="origin" required placeholder="Enter origin">
            
            <label for="destination">Destination:</label>
            <input type="text" name="destination" required placeholder="Enter destination">
            
            <label for="ride_date">Ride Date:</label>
            <input type="date" name="ride_date" required min="<?php echo date('Y-m-d'); ?>">
            <label for="ride_time">Ride Time:</label>
            <input type="time" name="ride_time" required>
            
            <label for="vehicle_type">Vehicle Type:</label>
            <select name="vehicle_type" id="vehicle_type">
                <option value="car">Car</option>
                <option value="bike">Bike</option>
            </select>
            
            <label for="seats_available">Seats Available:</label>
            <input type="number" name="seats_available" id="seats_available" required placeholder="Enter number of seats">
            <span id="seats_error" class="error">Seats available must be valid based on vehicle type.</span>
            
            <label for="price">Price:</label>
            <input type="number" name="price" required placeholder="Enter price">
            
            <button type="submit">Post Ride</button>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            const $vehicleTypeSelect = $('#vehicle_type');
            const $seatsInput = $('#seats_available');
            const $seatsError = $('#seats_error');

            function validateSeats() {
                const vehicleType = $vehicleTypeSelect.val();
                const seatsAvailable = parseInt($seatsInput.val(), 10);

                if (vehicleType === 'bike' && seatsAvailable !== 1) {
                    $seatsError.text('For bikes, seat availability must be exactly 1.');
                    $seatsError.addClass('active');
                } else if (vehicleType === 'car' && seatsAvailable <= 0) {
                    $seatsError.text('For cars, seat availability must be greater than 0.');
                    $seatsError.addClass('active');
                } else {
                    $seatsError.text('');
                    $seatsError.removeClass('active');
                }
            }

            // Event listeners
            $vehicleTypeSelect.on('change', validateSeats);
            $seatsInput.on('input', validateSeats);
        });

        // Logout confirmation
        $('#logout-link').on('click', function(e) {
            e.preventDefault();
            var confirmed = confirm('Are you sure you want to logout?');
            if (confirmed) {
                window.location.href = 'logout.php';
            }
        });
    </script>
</body>
</html>
