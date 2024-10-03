<?php
if(isset($_POST['payment_success'])) {
    $user_id = $_SESSION['user_id'];
    $order_id = $_POST['order_id']; // Assuming you pass the order ID via POST after checkout
    $payment_type = "credit_card"; // This can be dynamically set based on the user's choice
    $total_amount = $_POST['total']; // Calculate the total from the cart or pass as a hidden field

    // Insert into Payment table
    $sql_payment = "INSERT INTO Payment (User_id, order_id, type, payment_date, total) 
                    VALUES ('$user_id', '$order_id', '$payment_type', NOW(), '$total_amount')";

    if ($conn->query($sql_payment) === TRUE) {
        $payment_id = $conn->insert_id;

        // Insert into Receipt table
        $sql_receipt = "INSERT INTO Receipt (order_id, User_id, payment_id, date, total) 
                        VALUES ('$order_id', '$user_id', '$payment_id', NOW(), '$total_amount')";
        $conn->query($sql_receipt);

        echo "Payment successful and receipt generated!";
    } else {
        echo "Error: " . $sql_payment . "<br>" . $conn->error;
    }
}
?>
