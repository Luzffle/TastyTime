<?php
if(isset($_POST['checkout'])) {
    $user_id = $_SESSION['user_id']; // User ID from session

    // Create new order header
    $sql_order_header = "INSERT INTO Order_header (User_id, order_date) VALUES ('$user_id', NOW())";

    if ($conn->query($sql_order_header) === TRUE) {
        $order_id = $conn->insert_id; // Get the order ID for the inserted order

        // Retrieve cart items for the user
        $sql_cart = "SELECT * FROM cart WHERE User_id='$user_id'";
        $result_cart = $conn->query($sql_cart);

        while($row_cart = $result_cart->fetch_assoc()) {
            $product_id = $row_cart['Product_id'];
            $quantity = $row_cart['Quantity'];
            $price = $row_cart['Price'];

            // Insert into order_line
            $sql_order_line = "INSERT INTO Order_line (order_id, product_id, quantity) 
                               VALUES ('$order_id', '$product_id', '$quantity')";
            $conn->query($sql_order_line);
        }

        // Clear the user's cart after placing the order
        $sql_clear_cart = "DELETE FROM cart WHERE User_id='$user_id'";
        $conn->query($sql_clear_cart);
        
        echo "Order placed successfully!";
    } else {
        echo "Error: " . $sql_order_header . "<br>" . $conn->error;
    }
}
?>
