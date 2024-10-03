<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_otp = $_POST['otp'];
    
    // Check if the OTP matches the one sent
    if ($_SESSION['otp'] == $input_otp) {
        // Store the user in the database
        $conn = new mysqli('localhost', 'your_username', 'your_password', 'your_database');
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $name = $_SESSION['name'];
        $email = $_SESSION['email'];
        $password = $_SESSION['password'];

        // Insert user into User table
        $sql = "INSERT INTO User (first_name, email, PasswordHash) VALUES ('$name', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['status' => 'success', 'message' => 'Account created']);
            // Clear session data
            session_unset();
            session_destroy();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to create account']);
        }
        $conn->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid OTP']);
    }
}
?>
