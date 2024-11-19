<?php
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $sql = "INSERT INTO users (name, email, password, phone) VALUES ('$name', '$email', '$password', '$phone')";
    if ($conn->query($sql) === TRUE) 
    {
        header("Location: login.php");
    } 
    else 
    {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <form method="post" action="">
        <h2>Register</h2>
        <label for="name">Name:</label>
        <input type="text" name="name"  autocomplete="off" required>
        <label for="email">Email:</label>
        <input type="email" name="email"  autocomplete="off" required>
        <label for="phone">Phone Number:</label>
        <input type="tel" name="phone"  autocomplete="off" required>
        <label for="password">Password:</label>
        <input type="password" name="password"  autocomplete="off" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
        <button type="submit">Signup</button>
    </form>
</body>
</html>
