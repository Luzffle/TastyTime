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

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists and is not expired
    $query = "SELECT * FROM user WHERE reset_token = ? AND reset_token_expiry > NOW()";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

            // Update the password in the database
            $updateQuery = "UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->bind_param("ss", $new_password, $token);
            $updateStmt->execute();

            echo "Password reset successful. You can now log in.";
        }
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        /* Add similar styling here as needed */
    </style>
</head>
<body>

    <form method="POST">
        <label for="new_password">New Password</label>
        <input type="password" id="new_password" name="new_password" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>
