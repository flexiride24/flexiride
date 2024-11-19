<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Function to calculate price based on distance
function calculatePrice($origin, $destination) {
    // This is a mock function. Replace with actual distance calculation logic
    $distance = rand(1, 100); // Random distance for demonstration; replace with actual logic
    return round($distance * 2 / 10) * 10; // Calculate price as per your requirement
}





// Assuming data is being passed from the previous form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];

    // Calculate the price dynamically
    $cprice = calculatePrice($origin, $destination);

    if (isset($_POST['final_price'])) {
        $final_price = $_POST['final_price'];

        // Update database with the final price
        $user_id = $_SESSION['user_id'];
        $query = "INSERT INTO rides (user_id, origin, destination, ride_date, ride_time, vehicle_type, seats_available, price) 
                  VALUES ('$user_id', '$origin', '$destination', '$ride_date', '$ride_time', '$vehicle_type', '$seats_available', '$final_price')";

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
    <title>Ride Price Adjustment</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="price-container">
        <h2>Your Ride Price</h2>
        <p>The best calculated price for your ride is: Rs. <?php echo $calculated_price; ?></p>
        
        <form method="post" action="">
            <label for="final_price">Enter your desired price:</label>
            <input type="number" name="final_price" id="final_price" value="<?php echo $calculated_price; ?>" min="<?php echo $calculated_price - 10; ?>" max="<?php echo $calculated_price + 10; ?>">
            <button type="submit">Submit Price</button>
        </form>
    </div>

    <style>
        body {
            font-family: 'Josefin Sans', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .price-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            animation: slideIn 1s ease-in-out;
        }

        h2 {
            color: #3b6faa;
            margin-bottom: 20px;
        }

        label {
            font-size: 1.2em;
            display: block;
            margin-bottom: 10px;
        }

        input {
            width: 80%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1.1em;
        }

        button {
            background-color: #3b6faa;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            animation: bounce 2s infinite;
        }

        button:hover {
            background-color: #ff9800;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</body>
</html>
