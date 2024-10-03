<?php 

// Database configuration 
$dbHost     = "localhost"; 
$dbUsername = "root"; 
$dbPassword = ""; 
$dbName     = "tastytime"; 

// Enable detailed error reporting for mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Create a function to establish the connection
function connectDB($dbHost, $dbUsername, $dbPassword, $dbName) {
    // Create a new connection
    $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
    
    // Check for connection errors and log them
    if ($db->connect_error) {
        error_log("Connection failed: " . $db->connect_error); // Log the error
        die("Database connection error. Please try again later."); // Hide detailed error from the user
    }
    
    // Return the database connection
    return $db;
}

// Establish the connection
$db = connectDB($dbHost, $dbUsername, $dbPassword, $dbName);

?>
