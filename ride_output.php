<?php
// ride_output.php

session_start();
include 'db.php'; // Ensure you have a database connection file included

if (!isset($_SESSION['origin']) || !isset($_SESSION['destination'])) {
    header("Location: ride_input.php");
    exit();
}

// Predefined coordinates for some locations
$coords = [
    'Hyderabad' => [17.385044, 78.486671],
    'Bangalore' => [12.971599, 77.594566],
    'Chennai' => [13.082680, 80.270721],
    'Mumbai' => [19.076090, 72.877426],
    'Guntakal' => [15.174230, 77.372330],
    'Tirupati' => [13.628756, 79.419182],
    'Rangampeta' => [13.623653, 79.278233],
    'Renigunta' => [13.634838, 79.510540],
    "Banaganapalle" => [15.318202, 78.227886],
    'Pulivendula' => [14.424225, 78.225189],
    'Chandragiri' => [13.588201, 79.315461],
    'Mangapuram' => [13.611300, 79.328365],
    'MBU' => [13.623717, 79.289289],
    'Kadapa' => [14.469021, 78.823877],
    'Tadipatri' => [14.910682, 78.006774],
    'Pileru' => [13.655236, 78.946855],
    'Madanapalle' => [13.555927, 78.501010],
    'Mantralayam' => [15.941332, 77.424632],
    'Kurnool' => [15.831978, 78.034646],
    'Rayachoty' => [14.059031, 78.751738],
    'Vijayawada' => [16.507186, 80.642819],
    'Anantapuram' => [14.682880, 77.607933],
    'Dharmavaram' => [14.412524, 77.719426],
    'Srikalahasti' => [13.750923, 79.699462],
    'Visakhapatnam' => [17.686815, 83.218482],
    'Guntur' => [16.306722, 80.436131],
    'Kakinada' => [16.991515, 82.227323],
    'Rajahmundry' => [17.005090, 81.830280],
    'Nellore' => [14.442250, 79.986203],
    'Chittoor' => [13.210476, 79.100454],
    'Eluru' => [16.706525, 81.102774],
    'Proddatur' => [14.748268, 78.570663],
    'Tenali' => [16.239410, 80.606434],
    'Bhimavaram' => [16.538055, 81.513455],
    'Nandyal' => [15.487114, 78.484593],
    'Bapatla' => [15.896224, 80.476471],
    'Kothapeta' => [16.934037, 81.796631],
    'Machilipatnam' => [16.189324, 81.131268],
    'Srikakulam' => [18.298112, 83.897042],
    'Visakhapatnam Beach' => [17.686815, 83.218482],
    'Kumarakom' => [9.599802, 76.333576],
    'Puttaparthi' => [14.134115, 77.665571],
    'Chilakaluripet' => [16.102048, 80.160870],
    'Kavali' => [14.917331, 79.980949],
    'Kakinada Port' => [16.988387, 82.230293],
    'Gooty' => [15.261700, 77.343982],
    'Mangalagiri' => [16.234857, 80.555296],
    'Peddaganjam' => [14.383193, 79.955069],
    'Palakollu' => [16.427750, 81.667658],
    'Atmakur' => [15.524623, 78.292212],
    'Bhainsa' => [19.130309, 77.329895],
    'Jaggayyapeta' => [16.832874, 80.144460],
    'Mandapeta' => [16.809959, 81.882557],
    'Peddapalli' => [17.660321, 78.100226],
    'Tadepalligudem' => [16.853790, 81.524150],
    'Vempalli' => [14.643401, 78.755709],
    'Rajampet' => [14.146145, 78.866096],
    'Thotapalliguduru' => [14.184778, 79.132750],
    'Sabbavaram' => [17.703573, 83.226879],
    'Chirala' => [15.831043, 80.355689],
    'Gannavaram' => [16.508261, 80.794386],
    'Nagari' => [13.615400, 79.517800],
    'Vellore' => [12.972446, 79.132403],
    'Puthalapattu' => [13.241200, 79.246400],
    'Gudur' => [14.235186, 79.165263],
    'Srinivasa Mangapuram' => [13.605600, 79.430800],
    'Kapilatheertham' => [13.688800, 79.337100],
    'Akasa Ganga' => [13.688800, 79.337100],
    'Padmavathi Temple' => [13.626300, 79.431000],
    'Vaikuntha Teertham' => [13.670000, 79.344400],
    'Panchamukha Anjaneya Swamy Temple' => [13.602400, 79.429700],
    'Ramakrishna Ashram' => [13.628000, 79.425300],
    'Bellary' => [15.139646, 76.930663],
    'Vajra Karur' => [14.632305, 77.555793],
    'Singanamala' => [14.747928, 77.931195],
    'Chennekothapalli' => [14.568121, 77.536528],
    'Kottapalli' => [14.484060, 77.829856],
    'Kondapalli' => [14.720093, 77.494132],
    'Challakere' => [14.080268, 76.998293],
];


// Get user input
$origin = $_SESSION['origin'];
$destination = $_SESSION['destination'];

// Get coordinates for the origin and destination
$startCoords = $coords[$origin] ?? null;
$endCoords = $coords[$destination] ?? null;

// Check if both locations exist in the coordinates array
if ($startCoords === null || $endCoords === null) {
    echo "Invalid origin or destination. Please make sure both locations are in the predefined list.";
    
    exit();
}

// Vincenty's formula to calculate distance
function vincentyDistance($lat1, $lon1, $lat2, $lon2) {
    $a = 6378137; // Earth's semi-major axis in meters
    $b = 6356752.314245; // Earth's semi-minor axis in meters
    $f = 1 / 298.257223563; // Flattening
    $L = deg2rad($lon2 - $lon1);
    
    $U1 = atan((1 - $f) * tan(deg2rad($lat1)));
    $U2 = atan((1 - $f) * tan(deg2rad($lat2)));
    $sinU1 = sin($U1);
    $cosU1 = cos($U1);
    $sinU2 = sin($U2);
    $cosU2 = cos($U2);

    $lambda = $L;
    $lambdaP = 2 * M_PI;
    $iterLimit = 100;

    while (abs($lambda - $lambdaP) > 1e-12 && --$iterLimit > 0) {
        $sinLambda = sin($lambda);
        $cosLambda = cos($lambda);
        $sinSigma = sqrt(
            ($cosU2 * $sinLambda) * ($cosU2 * $sinLambda) +
            ($cosU1 * $sinU2 - $sinU1 * $cosU2 * $cosLambda) * ($cosU1 * $sinU2 - $sinU1 * $cosU2 * $cosLambda)
        );
        if ($sinSigma == 0) return 0; // Co-incident points
        $cosSigma = $sinU1 * $sinU2 + $cosU1 * $cosU2 * $cosLambda;
        $sigma = atan2($sinSigma, $cosSigma);
        $sinAlpha = $cosU1 * $cosU2 * $sinLambda / $sinSigma;
        $cosSqAlpha = 1 - $sinAlpha * $sinAlpha;
        $cos2SigmaM = $cosU2 * $cosU1 * cos($lambda) - $sinU1 * $sinU2 / $cosSqAlpha;
        if (is_nan($cos2SigmaM)) $cos2SigmaM = 0; // equatorial line
        $C = $f / 16 * $cosSqAlpha * (4 + $f * (4 - 3 * $cosSqAlpha));
        $lambdaP = $lambda;
        $lambda = $L + (1 - $C) * $f * $sinAlpha * (
            $sigma + $C * $sinSigma * ($cos2SigmaM + $C * $cosSigma * (-1 + 2 * $cos2SigmaM * $cos2SigmaM))
        );
    }
    
    if ($iterLimit == 0) return null; // formula failed to converge

    $uSq = $cosSqAlpha * ($a * $a - $b * $b) / ($b * $b);
    $A = 1 + $uSq / 16384 * (4096 + $uSq * (-768 + $uSq * (320 - 175 * $uSq)));
    $B = $uSq / 1024 * (256 + $uSq * (-128 + $uSq * (74 - 47 * $uSq)));
    $deltaSigma = $B * $sinSigma * (
        $cos2SigmaM + $B / 4 * (
            $cosSigma * (-1 + 2 * $cos2SigmaM * $cos2SigmaM) - 
            $B / 6 * $cos2SigmaM * (-3 + 4 * $sinSigma * $sinSigma) * (-3 + 4 * $cos2SigmaM * $cos2SigmaM)
        )
    );

    $s = $b * $A * ($sigma - $deltaSigma); // Distance in meters
    return $s / 1000; // Convert to kilometers
}

// Calculate distance using Vincenty's formula
$distance = vincentyDistance($startCoords[0], $startCoords[1], $endCoords[0], $endCoords[1]);

// Calculate system-generated price
$systemPrice = round($distance * 2 / 10) * 10; // Rounding to nearest 10
$successMessage = "";
$errorMessage = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $origin = $_SESSION['origin'];
    $destination = $_SESSION['destination'];
    $ride_date = $_SESSION['ride_date'];
    $ride_time = $_SESSION['ride_time'];
    $vehicle_type = $_SESSION['vehicle_type'];
    $seats_available = $_SESSION['seats_available'];
    $user_id = $_SESSION['user_id'];
    $price = $_POST['price'];

    // Validate price range
    if ($price >= $systemPrice - 10 && $price <= $systemPrice + 10) {
        $query = "INSERT INTO rides (user_id, origin, destination, ride_date, ride_time, vehicle_type, seats_available, price) 
        VALUES ('$user_id', '$origin', '$destination', '$ride_date', '$ride_time', '$vehicle_type', '$seats_available', '$price')";
        
        if (mysqli_query($conn, $query)) {
            $successMessage = "Ride Submitted! Your custom price is ₹$price. System-generated price is ₹$systemPrice. Distance is $distance km. ";
        } else {
            $errorMessage = "Error: " . mysqli_error($conn);
        }
    } else {
        $errorMessage = "Price must be within ₹10 of the system-generated price (₹$systemPrice).";
    }
}

// Clear session variables

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" href="images\favvi.png" type="image/x-icon">
    <title>Ride Output</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #6b5b95, #c06c84); /* Gradient background */
            color: #fff;
            margin: 0;
            padding: 20px;
            overflow: hidden;
        }
        
        .container {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.8s forwards;
            animation-delay: 0.2s;
            background: rgba(255, 255, 255, 0.1); /* Semi-transparent background for contrast */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        h2 {
            animation: bounceIn 0.8s forwards;
            color: #ffdd57; /* Bright color for header */
        }

        p {
            animation: slideIn 1s forwards;
            opacity: 0;
            transform: translateY(20px);
            animation-delay: 0.4s;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }

        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Button styles */
        button {
            background: linear-gradient(90deg, #ff416c, #ff4b2b); /* Gradient button background */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.2s, background-color 0.2s;
        }

        button:hover {
            transform: scale(1.1);
            background: linear-gradient(90deg, #ff4b2b, #ff416c); /* Inverse gradient on hover */
        }

        button:active {
            transform: scale(0.95);
        }

        /* Input field styles */
        input[type="number"] {
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.2s;
        }

        input[type="number"]:focus {
            border-color: #ffdd57; /* Bright border on focus */
            outline: none;
        }

        /* Additional paragraph styles */
        p.success {
            color: #28a745;
            animation: fadeIn 1s forwards;
        }

        p.error {
            color: #dc3545;
            animation: fadeIn 1s forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Responsive styles */
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            h2 {
                font-size: 1.5em;
            }

            button {
                width: 100%;
                padding: 12px;
            }
        }
        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            color: black;
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px; 
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Ride Details</h1>
    <p>Origin: <?php echo htmlspecialchars($origin); ?></p>
    <p>Destination: <?php echo htmlspecialchars($destination); ?></p>
    <p>Distance: <?php echo round($distance, 2); ?> km</p>
    <p>System-generated Price: ₹<?php echo $systemPrice; ?></p>
    <form method="POST" id="priceForm">
        <label for="price">Enter your price (within ₹10 of the system-generated price): </label>
        <input type="number" name="price" id="price" required>
        <button type="submit">Submit</button>
    </form>
</div>

<!-- The Modal -->
<!-- The Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <!-- Modal message dynamically shows success or error -->
        <p id="modalMessage">
            <?php if ($successMessage): ?>
                <strong>Success:</strong> <?php echo $successMessage; ?><br>
            <?php elseif ($errorMessage): ?>
                <strong>Error:</strong> <?php echo $errorMessage; ?><br>
            <?php endif; ?>
        </p>
        
        <!-- Additional ride details -->
        <p><strong>Origin:</strong> <?php echo htmlspecialchars($origin); ?></p>
        <p><strong>Destination:</strong> <?php echo htmlspecialchars($destination); ?></p>
        <p><strong>Ride Date:</strong> <?php echo htmlspecialchars($_SESSION['ride_date']); ?></p>
        <p><strong>Ride Time:</strong> <?php echo htmlspecialchars($_SESSION['ride_time']); ?></p>
        
        <!-- Back to Home button -->
        <button onclick="window.location.href='index.php'">Back to Home</button>
    </div>
</div>

<script>
    $(document).ready(function() {
        <?php if ($successMessage || $errorMessage): ?>
            $("#myModal").css("display", "block");
        <?php endif; ?>

        // Close modal when the user clicks on <span> (x)
        $(".close").click(function() {
            $("#myModal").css("display", "none");
        });

        // Close modal when the user clicks anywhere outside of the modal
        $(window).click(function(event) {
            if (event.target.id === "myModal") {
                $("#myModal").css("display", "none");
            }
        });
    });
</script>




</body>
</html>
