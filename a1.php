<?php
include 'db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user details from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if any user is found
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // Handle case where user is not found
    $user = []; // Ensure $user is an empty array if no user is found
}

// Handle phone number submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['phone'])) {
    $phone = $_POST['phone'];
    
    // Update the phone number in the database
    $update_query = "UPDATE users SET phone = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("si", $phone, $user_id);
    
    if ($update_stmt->execute()) {
        $user['phone'] = $phone;  // Update the $user array with the new phone number
        $message = "Phone number updated successfully!";
    } else {
        $message = "Error updating phone number.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Sofadi+One&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="a2.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="images/favvi.png" type="image/x-icon">
    <style>
        body {
            background-image: url("images/boooook.jpg");
            background-repeat: no-repeat; /* Ensures the image doesn't repeat */
            background-size: cover; /* Scales the image to cover the entire background */
            background-position: center;
            margin: 0px;
            padding: 0px;
            font-family: "Josefin Sans", sans-serif;
            overflow: scroll; 
            font-size: large;
            height: 100vh;
            align-items: center;
            background-attachment: fixed;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        /* Navigation Bar */
        .navbar {
            background-color: #000; /* Black theme */
            color: #fff; /* White text */
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0px 0px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3); /* Slight shadow for depth */
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
            color: #ff9800; /* Logo hover color */
        }
        
        /* Navigation Links */
        .nav-links {
            list-style: none;
            display: flex;
        }
        
        .nav-links li {
            margin-left: 30px;
        }
        
        .nav-links a {
            text-decoration: none;
            color: #fff; /* Default link color */
            font-size: 18px;
            transition: color 0.3s ease, background-color 0.3s ease;
            padding: 8px 16px;
            border-radius: 4px;
        }
        
        /* Hover Effects */
        .nav-links a:hover {
            color: #ff9800; /* Orange color on hover */
            background-color: rgba(255, 152, 0, 0.2); /* Light orange background on hover */
        }
        
        /* Active/Current Page Link */
        .nav-links a.active {
            color: #ff9800; /* Active link color */
            background-color: rgba(255, 152, 0, 0.4); /* Active link background */
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

        section {
            background-color: rgba(255, 255, 255, 0.5);
            padding: 40px;
            margin: 2% auto;
            max-width: 1200px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
            font-family: "Josefin Sans", sans-serif;
        }

        section h1 {
            font-family: "Josefin Sans", sans-serif;
            font-size: 36px;
            color: #333333;
            margin-bottom: 20px;
            font-family: 'Lora', serif;
        }

        section p {
            font-family: "Josefin Sans", sans-serif;
            font-size: 18px;
            color: #555555;
            line-height: 1.6;
            font-family: 'Lora', serif;
        }

        .login:hover {
            color: #fddb3a;
        }
    </style>
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
<script>
    // Add an event listener to the logout link
    document.getElementById('logout-link').addEventListener('click', function(e) {
        // Prevent the default action
        e.preventDefault();
        
        // Display confirmation alert
        var confirmed = confirm('Are you sure you want to logout?');
        
        // If confirmed, redirect to logout.php
        if (confirmed) {
            window.location.href = 'logout.php';
        }
    });
</script>
<section class="section1">
    <div>
        <h1 style="font-family:'Josefin Sans', sans-serif;">Your Profile</h1>
        <b>
            <p style="font-family:'Josefin Sans', sans-serif;">
                <strong>Name:</strong> <?php echo isset($user['name']) ? htmlspecialchars($user['name']) : 'N/A'; ?>
            </p>
            <p style="font-family:'Josefin Sans', sans-serif;">
                <strong>Email:</strong> <?php echo isset($user['email']) ? htmlspecialchars($user['email']) : 'N/A'; ?>
            </p>

            <?php if (!empty($user['phone'])): ?>
                <p style="font-family:'Josefin Sans', sans-serif;">
                    <strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?>
                </p>
            <?php else: ?>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <label for="phone">Enter your phone number:</label>
                    <input type="text" id="phone" name="phone" required>
                    <button type="submit">Submit</button>
                </form>
            <?php endif; ?>

            <?php if (isset($message)): ?>
                <p style="font-family:'Josefin Sans', sans-serif;"><?php echo $message; ?></p>
            <?php endif; ?>
        </b>
    </div>
</section>
</body>
</html>
