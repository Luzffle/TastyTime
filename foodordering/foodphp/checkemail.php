<?php
// check_email.php

require 'foodphp/loginconfig.php'; // Adjust the path to your database connection

$email = $_POST['email'] ?? '';

$response = ['exists' => false];

if (!empty($email)) {
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT 1 FROM User WHERE Email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $response['exists'] = true;
    }
}

echo json_encode($response);
?>
