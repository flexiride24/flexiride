<?php
// Include PHPMailer classes (Make sure PHPMailer is in the correct path)
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from the AJAX request (since it's sent as JSON, not as POST data)
    $data = json_decode(file_get_contents("php://input"), true);
    $latitude = isset($data['latitude']) ? $data['latitude'] : 'No Latitude';
    $longitude = isset($data['longitude']) ? $data['longitude'] : 'No Longitude';

    // Log the received coordinates for debugging
    error_log("Received Latitude: $latitude, Longitude: $longitude");

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Email setup
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'rs7393334@gmail.com';
        $mail->Password = 'Aravind@028';  // Use App-specific password if using 2FA
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Use PHPMailer constant
        $mail->Port = 587;
    
        // Email content
        $mail->setFrom('no-reply@rs7393334.com', 'Rohith Sharma');
        $mail->addAddress('rangareddyvenkata734@gmail.com');
        $mail->isHTML(true);
        $mail->Subject = 'User Reached Destination';
        $mail->Body = 'A user has reached their destination. Location: Latitude: ' . $latitude . ', Longitude: ' . $longitude;
    
        // Send the email
        if ($mail->send()) {
            echo json_encode(["status" => "success", "message" => "Location and email sent successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to send email."]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Email error: {$mail->ErrorInfo}"]);
    }
    

    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Coordinates to Database</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<button id="send-location" onclick="sendLocation(37.7749, -122.4194)">Send Location</button>

<!-- Hidden form for sending latitude and longitude -->
<form id="location-form" action="new.php" method="POST" style="display: none;">
    <input type="hidden" name="latitude" id="latitude">
    <input type="hidden" name="longitude" id="longitude">
</form>

<script>
function sendLocation(lat, lng) {
    // Set the values in the hidden form fields
    $('#latitude').val(lat);
    $('#longitude').val(lng);

    // Submit the form
    $('#location-form').submit();
}
</script>

</body>
</html>
