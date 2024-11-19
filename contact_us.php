<?php
session_start();

// Initialize variables for form data and success message
$name = $email = $message = "";
$successMessage = "";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Simple validation
    if (!empty($name) && !empty($email) && !empty($message)) {
        // Process the data (e.g., send an email, save to a database)
        // Here, we'll just simulate a success message
        $successMessage = "Thank you, $name! Your message has been sent.";
    } else {
        $successMessage = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #000000;
            padding: 10px 0;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        nav ul li {
            float: left;
        }

        nav ul li a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        nav ul li a:hover {
            background-color: #ffffff;
            color: #000000;
        }

        .contact-section {
            background-color: #f4f4f4;
            padding: 40px;
            margin: 20px auto;
            max-width: 600px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .contact-section h1 {
            font-size: 36px;
            color: #333;
            margin-bottom: 20px;
        }

        .contact-section form {
            display: flex;
            flex-direction: column;
        }

        .contact-section label {
            text-align: left;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        .contact-section input[type="text"],
        .contact-section input[type="email"],
        .contact-section textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .contact-section button {
            padding: 10px;
            background-color: #000000;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .contact-section button:hover {
            background-color: #444444;
        }

        .success-message {
            margin-top: 20px;
            color: green;
        }

        .error-message {
            margin-top: 20px;
            color: red;
        }
    </style>
</head>
<body>

    <section class="contact-section">
        <h1>Contact Us</h1>
        <form action="" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="5" required></textarea>

            <button type="submit">Send Message</button>
        </form>

        <?php if ($successMessage): ?>
            <div class="success-message"><?php echo $successMessage; ?></div>
        <?php endif; ?>
    </section>
</body>
</html>
