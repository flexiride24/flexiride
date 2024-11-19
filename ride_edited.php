<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Circular Loader Example</title> <!-- Link to your CSS file -->
    <style>
        
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


body {
    font-family: Arial, sans-serif; 
    background-color: #f4f4f4; 
    color: #333; 
    text-align: center; 
    padding: 50px; 
}


.loader {
    border: 8px solid #f3f3f3; 
    border-top: 8px solid #4caf50; 
    border-radius: 50%; 
    width: 60px; 
    height: 60px; 
    animation: spin 1s linear infinite;
    margin: 20px auto; 
}


@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

#success-message {
    display: none;   
    color: green;           
    font-size: 18px;         
    margin-top: 20px;        
    font-weight: bold;  
}
button {
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
    padding: 10px;
    margin-top:10%;
}

button:hover {
    background-color: #45a049;
}
    </style>
</head>
<body>
    <div class="load">
    <h1>Updating Your Ride</h1>

    <div class="loader"></div> <!-- Circular Loader -->
    </div>
    <div id="success-message">
    <p>Ride Updated successfully!</p>
    <a href="index.php"><button>Back to home page</button></a>
    </div>
    <script>
        // Simulate loading process
        setTimeout(() => {
            // After 3 seconds, hide the loader and show the success message
            document.querySelector(".load").style.display = "none"; // Hide loader
            document.getElementById("success-message").style.display = "block"; // Show success message
        }, 2000); // Simulate a loading time of 3 seconds
    </script>
</body>
</html>
