<?php
include 'db.php';  // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the user already exists
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "User with this email already exists.";
    } else {
        // Insert the new user into the database
        $sql = "INSERT INTO users (name, email, password) VALUES ('$username', '$email', '$hashed_password')";
        if ($conn->query($sql) === TRUE) {
            header("Location: login1.html");
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>
