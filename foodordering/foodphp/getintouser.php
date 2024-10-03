<?php
session_start();
$user_id = $_SESSION['user_id']; // Assume session holds the logged-in user's ID

// Database connection (replace with your actual database credentials)
$conn = new mysqli("localhost", "username", "password", "database");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user's personal details
$user_query = "SELECT first_name, last_name, email FROM User WHERE User_id = $user_id";
$user_result = $conn->query($user_query);

$user_info = [];
if ($user_result->num_rows > 0) {
    $user_info = $user_result->fetch_assoc();
}

// Fetch user's address details
$address_query = "SELECT street, barangay, district, city, province FROM User_address WHERE User_id = $user_id";
$address_result = $conn->query($address_query);

$address_info = [];
if ($address_result->num_rows > 0) {
    $address_info = $address_result->fetch_assoc();
}

// Fetch user's wallet balance
$wallet_query = "SELECT balance FROM Wallet WHERE User_id = $user_id";
$wallet_result = $conn->query($wallet_query);

$wallet_balance = 0;
if ($wallet_result->num_rows > 0) {
    $wallet_balance = $wallet_result->fetch_assoc()['balance'];
}

// Response combining all data
$response = [
    "user_info" => $user_info,
    "address_info" => $address_info,
    "wallet_balance" => $wallet_balance,
    "service_charge" => 1.00 // Fixed service charge
];
// Fetch user's cart items (assuming you have a cart table)
$cart_query = "SELECT products.product_name, products.price, cart.quantity 
               FROM cart 
               JOIN products ON cart.product_id = products.id 
               WHERE cart.user_id = $user_id";

$cart_result = $conn->query($cart_query);

$cart_items = [];
$total = 0;
if ($cart_result->num_rows > 0) {
    while ($row = $cart_result->fetch_assoc()) {
        $cart_items[] = $row;
        $total += $row['price'] * $row['quantity'];
    }
}

// Add cart items to response
$response['cart_items'] = $cart_items;
$response['cart_total'] = $total;

echo json_encode($response);

$conn->close();
?>
