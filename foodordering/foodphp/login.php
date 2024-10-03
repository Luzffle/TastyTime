<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'your_username', 'your_password', 'your_database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get email and password from POST request
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Check if the user exists in the User table
    $sql = "SELECT * FROM User WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the user record
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['PasswordHash'])) {
            // Login successful, set session variables
            $_SESSION['user_id'] = $user['User_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];

            // Redirect to the dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            // Invalid password
            $error = "Invalid email or password.";
        }
    } else {
        // User not found
        $error = "No account found with that email.";
    }
}
$conn->close();
?>
