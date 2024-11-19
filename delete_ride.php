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

// Delete the ride
$sql = "DELETE FROM rides WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $ride_id, $_SESSION['user_id']);

if ($stmt->execute()) {
    header("Location: myrides.php");
    exit();
} else {
    echo "Error deleting ride: " . $stmt->error;
}
?>
