<?php
include 'db.php';
session_start();

if (!isset($_GET['ride_id'])) {
    header("Location: rides.php");
    exit();
}

$ride_id = $_GET['ride_id'];

// Get the ride details and the user who posted it
$sql = "SELECT r.origin, r.destination, r.ride_date, r.price, u.name AS posted_user_name, u.phone AS posted_user_phone ,r.price
        FROM rides r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ride_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $ride = $result->fetch_assoc();
} else {
    echo "Ride not found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Success</title>
    <style>
        /* Loader styles */
        /* From Uiverse.io by Shoh2008 */ 
.loader {
  width: 175px;
  height: 80px;
  display: block;
  margin:auto;
  background-image: radial-gradient(circle 25px at 25px 25px, #FFF 100%, transparent 0), radial-gradient(circle 50px at 50px 50px, #FFF 100%, transparent 0), radial-gradient(circle 25px at 25px 25px, #FFF 100%, transparent 0), linear-gradient(#FFF 50px, transparent 0);
  background-size: 50px 50px, 100px 76px, 50px 50px, 120px 40px;
  background-position: 0px 30px, 37px 0px, 122px 30px, 25px 40px;
  background-repeat: no-repeat;
  position: relative;
  -webkit-box-sizing: border-box;
          box-sizing: border-box;
}
.loader::before {
  content: '';  
  left: 60px;
  bottom: 18px;
  position: absolute;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background-color: #FF3D00;
  background-image: radial-gradient(circle 8px at 18px 18px, #FFF 100%, transparent 0), radial-gradient(circle 4px at 18px 0px, #FFF 100%, transparent 0), radial-gradient(circle 4px at 0px 18px, #FFF 100%, transparent 0), radial-gradient(circle 4px at 36px 18px, #FFF 100%, transparent 0), radial-gradient(circle 4px at 18px 36px, #FFF 100%, transparent 0), radial-gradient(circle 4px at 30px 5px, #FFF 100%, transparent 0), radial-gradient(circle 4px at 30px 5px, #FFF 100%, transparent 0), radial-gradient(circle 4px at 30px 30px, #FFF 100%, transparent 0), radial-gradient(circle 4px at 5px 30px, #FFF 100%, transparent 0), radial-gradient(circle 4px at 5px 5px, #FFF 100%, transparent 0);
  background-repeat: no-repeat;
  -webkit-box-sizing: border-box;
          box-sizing: border-box;
  -webkit-animation: rotationBack 3s linear infinite;
          animation: rotationBack 3s linear infinite;
}
.loader::after {
  content: '';  
  left: 94px;
  bottom: 15px;
  position: absolute;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background-color: #FF3D00;
  background-image: radial-gradient(circle 5px at 12px 12px, #FFF 100%, transparent 0), radial-gradient(circle 2.5px at 12px 0px, #FFF 100%, transparent 0), radial-gradient(circle 2.5px at 0px 12px, #FFF 100%, transparent 0), radial-gradient(circle 2.5px at 24px 12px, #FFF 100%, transparent 0), radial-gradient(circle 2.5px at 12px 24px, #FFF 100%, transparent 0), radial-gradient(circle 2.5px at 20px 3px, #FFF 100%, transparent 0), radial-gradient(circle 2.5px at 20px 3px, #FFF 100%, transparent 0), radial-gradient(circle 2.5px at 20px 20px, #FFF 100%, transparent 0), radial-gradient(circle 2.5px at 3px 20px, #FFF 100%, transparent 0), radial-gradient(circle 2.5px at 3px 3px, #FFF 100%, transparent 0);
  background-repeat: no-repeat;
  -webkit-box-sizing: border-box;
          box-sizing: border-box;
  animation: rotationBack 4s linear infinite reverse;
}

@-webkit-keyframes rotationBack {
  0% {
    -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(-360deg);
            transform: rotate(-360deg);
  }
}

@keyframes rotationBack {
  0% {
    -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(-360deg);
            transform: rotate(-360deg);
  }
}

        /* Success message styling */
        .success {
            display: none;
            text-align: center;
        }

        h2 {
            color: green;
            font-size: 2em;
        }

        table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 50%;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        /* Button styling */
        .btn-home {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            text-align: center;
            border-radius: 5px;
            width:10%;
        }

        .btn-home:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <!-- Loader -->
    <div class="loader" id="loader"></div>

    <!-- Success Message -->
    <div class="success" id="successMessage">
        <h2>Your ride has been booked successfully!</h2>
        <h3>Ride Details:</h3>
        <table>
            <tr>
                <th>Origin</th>
                <td><?php echo $ride['origin']; ?></td>
            </tr>
            <tr>
                <th>Destination</th>
                <td><?php echo $ride['destination']; ?></td>
            </tr>
            <tr>
                <th>Ride Date</th>
                <td><?php echo $ride['ride_date']; ?></td>
            </tr>
            <tr>
                <th>Price</th>
                <td>₹<?php echo $ride['price']; ?></td>
            </tr>
            <tr>
                <th>Posted By</th>
                <td><?php echo $ride['posted_user_name']; ?></td>
            </tr>
            <tr>
                <th>Contact</th>
                <td><?php echo $ride['posted_user_phone']; ?></td>
            </tr>
        </table>

        <!-- Button to go back to home page -->
        <a href="index.php" class="btn-home">Go to Home Page</a>
    </div>

    <script>
        // Simulate loader for 3 seconds, then show success message
        setTimeout(function() {
            document.getElementById('loader').style.display = 'none';
            document.getElementById('successMessage').style.display = 'block';
        }, 3000);
    </script>
</body>
</html>
