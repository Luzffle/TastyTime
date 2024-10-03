<?php
// Database connection
$dbHost = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "tastytime_database";

// Create database connection
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $query = "SELECT * FROM login_google WHERE email = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a unique token for password reset
        $token = bin2hex(random_bytes(50));

        // Store token in the database with the email
        $updateQuery = "UPDATE users SET reset_token = ?, reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?";
        $updateStmt = $db->prepare($updateQuery);
        $updateStmt->bind_param("ss", $token, $email);
        $updateStmt->execute();

        // Send the password reset email
        $resetLink = "http://127.0.0.1:5500/forgotpass.html" . $token;
        $subject = "Password Reset Request";
        $message = "Click on the following link to reset your password: " . $resetLink;
        $headers = "From: no-reply@tastytime.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "Password reset link has been sent to your email.";
        } else {
            echo "Failed to send the reset email.";
        }
    } else {
        echo "No account found with that email.";
    }
}
?>
