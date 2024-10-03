<?php
if(isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id']; // Assuming the user ID is stored in the session after login
    $quantity = 1; // Default quantity to 1, or capture from user input
    $price = $_POST['price'];

    // Insert into cart
    $sql = "INSERT INTO cart (Product_id, Quantity, Price, User_id) 
            VALUES ('$product_id', '$quantity', '$price', '$user_id')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Added to cart successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
