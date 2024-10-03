<?php
session_start();
$user_id = $_SESSION['user_id']; // Get the logged-in user's ID
$data = json_decode(file_get_contents('php://input'), true);
$amount = $data['amount'];

// Database connection
$conn = new mysqli("localhost", "username", "password", "database");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch the user's current wallet balance
$wallet_query = "SELECT wallet_balance FROM users WHERE id = $user_id";
$wallet_result = $conn->query($wallet_query);

if ($wallet_result->num_rows > 0) {
    $wallet_balance = $wallet_result->fetch_assoc()['wallet_balance'];

    if ($wallet_balance >= $amount) {
        // Deduct the amount from the user's wallet
        $new_balance = $wallet_balance - $amount;
        $update_query = "UPDATE users SET wallet_balance = $new_balance WHERE id = $user_id";
        if ($conn->query($update_query) === TRUE) {
            // Return success response
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update wallet balance.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Insufficient wallet balance.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'User not found.']);
}

$conn->close();
?>
