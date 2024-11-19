<?php 
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['ride_id'])) {
    $ride_id = $_GET['ride_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $seats_booked = $_POST['seats_booked'];
        $user_id = $_SESSION['user_id'];

        // Check available seats
        $ride_sql = "SELECT * FROM rides WHERE id = $ride_id";
        $ride_result = $conn->query($ride_sql);
        $ride = $ride_result->fetch_assoc();

        if ($ride['seats_available'] >= $seats_booked) {
            // Update seats and insert booking
            $new_seats = $ride['seats_available'] - $seats_booked;
            $update_sql = "UPDATE rides SET seats_available = $new_seats WHERE id = $ride_id";
            $conn->query($update_sql);

            $booking_sql = "INSERT INTO bookings (user_id, ride_id, seats_booked) VALUES ('$user_id', '$ride_id', '$seats_booked')";
            if ($conn->query($booking_sql) === TRUE) {
                // Redirect to booking success page
                header("Location: booking_success.php?ride_id=$ride_id");
                exit();
            } else {
                echo "Error: " . $booking_sql . "<br>" . $conn->error;
            }
        } else {
            echo "Not enough seats available!";
        }
    }
} else {
    echo "Invalid Ride ID.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Ride</title>
    <style>
        /* General styling */
body {
    font-family: "Josefin Sans", sans-serif;
    background-color: #f4f4f9;
    color: black;
    margin: 0;
    padding: 0;
    background-image: url("images/sample-transformed.jpeg");
    background-repeat: no-repeat; /* Ensures the image doesn't repeat */
    background-size: cover; /* Scales the image to cover the entire background */
    background-position: center;
    align-items: center;
    height: 100vh;
}
.book{
    width: fit-content;
    margin: 100px auto;
    
}
h2 {
    text-align: center;
    font-size: 2.2rem;
    color: black;
    margin-bottom: 20px;
}

/* Form container */
form {
    backdrop-filter: blur(7px);
    width: 350px;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.8);
    text-align: center;
}

/* Form input fields */
label {
    font-size: 1.1rem;
    color: black;
    display: block;
    margin-bottom: 8px;
    text-align: left;
}

input[type="number"] {
    width: 90%;
    padding: 10px;
    margin-bottom: 15px;
    border: 2px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

input[type="number"]:focus {
    border-color: #4a4a8a;
    outline: none;
}

/* Button styling */
.book button[type="submit"] {
    font-family: "Josefin Sans", sans-serif;
    background-color: #4a4a8a;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
    width: 90%;
}

button[type="submit"]:hover {
    background-color: #6767b3;
    transform: translateY(-3px);
}

button[type="submit"]:active {
    transform: translateY(1px);
    background-color: #39396b;
}

/* Responsive Design */
@media (max-width: 768px) {
    form {
        width: 90%;
        padding: 15px;
    }

    h2 {
        font-size: 1.8rem;
    }

    input[type="number"], button[type="submit"] {
        font-size: 1rem;
        padding: 10px;
    }
}

    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Sofadi+One&display=swap" rel="stylesheet">
</head>
<body>
    <div class="book">
        <h1 style="color:black;">Book rides</h1>
    <form method="post" action="">
        <label for="seats_booked">Seats to Book:</label>
        <input type="number" name="seats_booked" required>
        <button type="submit">Book Ride</button>
    </form>
    <div class="error"></div>
    </div>
</body>
</html>
