<?php
// database_connection.php

$host = "localhost"; // Database host
$db = "tastytime"; // Database name
$user = "root"; // Database user
$pass = ""; // Database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}
?>
