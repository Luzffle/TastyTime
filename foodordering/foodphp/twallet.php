<?php
session_start(); // Start the session to keep track of the user
$user_id = $_SESSION['user_id']; // Assume you're using sessions to track the logged-in user

// Database connection (replace with your credentials)
$conn = new mysqli("localhost", "username", "password", "database");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch wallet balance
$wallet_query = "SELECT wallet_balance FROM users WHERE id = $user_id";
$wallet_result = $conn->query($wallet_query);
$wallet_balance = 0;

if ($wallet_result->num_rows > 0) {
    $wallet_balance = $wallet_result->fetch_assoc()['wallet_balance'];
}

// Query to fetch cart items for this user
$sql = "SELECT products.product_name, products.price, cart.quantity 
        FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.user_id = $user_id";

$result = $conn->query($sql);

$cart_items = [];
$total = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total += $row['price'] * $row['quantity'];
    }
}

// Add service charge (let's assume it's $1 for now)
$service_fee = 1.00;
$total += $service_fee;

// Return the data as JSON for the frontend to process
$response = [
    "cart_items" => $cart_items,
    "total" => $total,
    "service_fee" => $service_fee,
    "wallet_balance" => $wallet_balance
];

echo json_encode($response);

$conn->close();
?>
