<?php
session_start();
require 'PHPMailer/PHPMailerAutoload.php'; // Use PHPMailer to send emails

// Database connection
$conn = new mysqli('localhost', 'your_username', 'your_password', 'your_database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the email already exists
    $checkEmail = "SELECT * FROM User WHERE email='$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email already registered']);
        exit();
    }

    // Generate OTP
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;
    $_SESSION['name'] = $name;
    $_SESSION['password'] = $password;

    // Send OTP via email
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.yourmailprovider.com'; // Your mail provider
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@example.com'; // Your SMTP username
    $mail->Password = 'your_password'; // Your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('no-reply@yourwebsite.com', 'Tasty Time');
    $mail->addAddress($email);
    $mail->Subject = 'Your OTP Code';
    $mail->Body    = "Your OTP code is $otp. It will expire in 10 minutes.";

    if ($mail->send()) {
        echo json_encode(['status' => 'success', 'message' => 'OTP sent']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP']);
    }
}

$conn->close();
?>
