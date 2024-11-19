<?php
include 'db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the ride ID from the query string
$ride_id = $_GET['id'];

// Fetch ride details
$sql = "SELECT * FROM rides WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $ride_id, $_SESSION['user_id']);
$stmt->execute();
$ride = $stmt->get_result()->fetch_assoc();

if (!$ride) {
    echo "Ride not found or you do not have permission to edit this ride.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $ride_date = $_POST['ride_date'];
    $vehicle_type = $_POST['vehicle_type'];
    $seats_available = $_POST['seats_available'];


    // Validate seat availability
    if ($vehicle_type === 'bike' && $seats_available != 1) {
        $error = "For bikes, seat availability must be exactly 1.";
    } elseif ($vehicle_type === 'car' && $seats_available <= 0) {
        $error = "For cars, seat availability must be greater than 0.";
    } else {
        // Update ride details
        $sql = "UPDATE rides SET origin = ?, destination = ?, ride_date = ?, vehicle_type = ?, seats_available = ?, WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiiii", $origin, $destination, $ride_date, $vehicle_type, $seats_available,  $ride_id, $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            header("Location: ride_edited.php");
            exit();
        } else {
            $error = "Error updating ride: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ride</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Sofadi+One&display=swap" rel="stylesheet">
    <script>
        function validateSeats() {
            var vehicleType = document.querySelector('select[name="vehicle_type"]').value;
            var seatsAvailable = document.querySelector('input[name="seats_available"]').value;
            var errorMessage = '';

            if (vehicleType === 'bike' && seatsAvailable != 1) {
                errorMessage = "For bikes, seat availability must be exactly 1.";
            } else if (vehicleType === 'car' && seatsAvailable <= 0) {
                errorMessage = "For cars, seat availability must be greater than 0.";
            }

            document.getElementById('seatError').innerText = errorMessage;
            document.querySelector('button[type="submit"]').disabled = !!errorMessage;
        }

        function onVehicleTypeChange() {
            validateSeats();
        }

        function onSeatsAvailableChange() {
            validateSeats();
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('select[name="vehicle_type"]').addEventListener('change', onVehicleTypeChange);
            document.querySelector('input[name="seats_available"]').addEventListener('input', onSeatsAvailableChange);
        });
    </script>
    <style>
        /* Ensure the entire page has a white background */
body {
    background-image: url("images/n.jpg");
    background-repeat: no-repeat; /* Ensures the image doesn't repeat */
    background-size: cover; /* Scales the image to cover the entire background */
    background-position: center;
    margin: 0px;
    padding: 0px;
    font-family: "Josefin Sans", sans-serif;
    color: rgb(255, 255, 255);
    font-size: large;
    height: 100vh;
    align-items: center;
}
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; /* Full viewport height */
}

/* Styling for the form box */
form {
    background-color: #ffffff94; /* White background for the form */
    border-radius: 10px; /* Curved corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    padding: 20px;
    max-width: 400px;
    width: 100%; /* Responsive width */
}

/* Styling for form headers */
h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #fffffffd; /* Darker color for the heading */
}

/* Styling for form labels */
label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}

/* Styling for input fields and select dropdown */
input[type="text"],
input[type="date"],
input[type="number"],
select {
    width: 90%; /* Full width */
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    margin-bottom: 15px; /* Space between fields */
}

/* Styling for the submit button */
button[type="submit"] {
    background-color: #4CAF50; /* Green background */
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    width: 100%; /* Full width */
}

/* Change button color on hover */
button[type="submit"]:hover {
    background-color: #45a049;
}

/* Error message styling */
.error {
    color: red;
    font-size: 0.9em;
    margin-bottom: 15px;
}

    </style>
</head>
<body>
    <div class="container">
        <form method="post" action="">
            <h2>Edit Ride</h2>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <label for="origin">Origin:</label>
            <input type="text" name="origin" value="<?php echo htmlspecialchars($ride['origin']); ?>" required>
            <label for="destination">Destination:</label>
            <input type="text" name="destination" value="<?php echo htmlspecialchars($ride['destination']); ?>" required>
            <label for="ride_date">Ride Date:</label>
            <input type="date" name="ride_date" value="<?php echo htmlspecialchars($ride['ride_date']); ?>" required min="<?php echo date('Y-m-d'); ?>">
            <label for="vehicle_type">Vehicle Type:</label>
            <select name="vehicle_type">
                <option value="car" <?php if ($ride['vehicle_type'] === 'car') echo 'selected'; ?>>Car</option>
                <option value="bike" <?php if ($ride['vehicle_type'] === 'bike') echo 'selected'; ?>>Bike</option>
            </select>
            <label for="seats_available">Seats Available:</label>
            <input type="number" name="seats_available" value="<?php echo htmlspecialchars($ride['seats_available']); ?>" required>
            <p id="seatError" style="color: red;"></p> <!-- Error message for seat availability -->
        
            <button type="submit">Update Ride</button>
        </form>
    </div>
</body>
</html>
