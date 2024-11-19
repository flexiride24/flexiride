<?php
// Database connection
$conn = new mysqli("localhost", "username", "password", "balbla_clone");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Update location in the database for the logged-in user (replace '1' with the user's actual ID)
    $sql = "UPDATE users SET latitude = '$latitude', longitude = '$longitude' WHERE id = 1";

    if ($conn->query($sql) === TRUE) {
        echo "Location updated successfully";

        // Check if the user has reached their destination (add your own logic here)
        if (isUserAtDestination($latitude, $longitude)) {
            sendReachedNotification($latitude, $longitude);
        }
    } else {
        echo "Error updating location: " . $conn->error;
    }
}

// Function to check if the user has reached their destination
function isUserAtDestination($latitude, $longitude) {
    // Logic to determine if the user has reached the destination
    // This can be done by comparing the latitude/longitude with a predefined destination
    // For demo purposes, let's assume the destination is latitude 12.9716 and longitude 77.5946
    $destination_latitude = 12.9716;
    $destination_longitude = 77.5946;
    $threshold = 0.01; // Define a small range for when the user is considered to have "arrived"

    return (abs($latitude - $destination_latitude) <= $threshold && abs($longitude - $destination_longitude) <= $threshold);
}

// Function to send email notification when user reaches the destination
function sendReachedNotification($latitude, $longitude) {
    $to = "rangareddyvenkata734@gmail.com";
    $subject = "User Reached Destination";
    $message = "The user has reached the destination. Latest location: Latitude $latitude, Longitude $longitude.";
    $headers = "From: no-reply@yourwebsite.com";

    // Send the email
    mail($to, $subject, $message, $headers);
}

$conn->close();
?>
