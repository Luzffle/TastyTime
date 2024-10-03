<?php 
// Database configuration 
$dbHost     = "localhost"; 
$dbUsername = "root"; 
$dbPassword = ""; 
$dbName     = "tastytime"; 
 
// Create database connection 
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName); 
 
// Check connection 
if ($db->connect_error) { 
    die("Connection failed: " . $db->connect_error); 
} 

// Retrieve JSON from POST body 
$jsonStr = file_get_contents('php://input'); 

// Check if JSON is valid
if (!$jsonStr) {
    echo json_encode(['error' => 'No data received!']);
    exit();
}

$jsonObj = json_decode($jsonStr); 

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['error' => 'Invalid JSON input!']);
    exit();
}

// Handle OAuth sign-in
if (!empty($jsonObj->request_type) && $jsonObj->request_type == 'user_auth') {
    $credential = !empty($jsonObj->credential) ? $jsonObj->credential : ''; 
    
    // Decode JWT response payload 
    list($header, $payload, $signature) = explode(".", $credential); 
    $responsePayload = json_decode(base64_decode($payload)); 
    
    if (!empty($responsePayload)) {
        // User's profile info
        $oauth_provider = 'google'; // You can adjust based on the provider.
        $oauth_uid  = !empty($responsePayload->sub) ? $responsePayload->sub : ''; 
        $first_name = !empty($responsePayload->given_name) ? $responsePayload->given_name : ''; 
        $last_name  = !empty($responsePayload->family_name) ? $responsePayload->family_name : ''; 
        $email      = !empty($responsePayload->email) ? $responsePayload->email : ''; 

        // Insert or update the user in the `User` table first
        $stmt = $db->prepare("SELECT User_id FROM User WHERE email = ?"); 
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) { 
            // If user exists, retrieve User_id
            $row = $result->fetch_assoc();
            $user_id = $row['User_id'];
        } else {
            // Insert new user into the `User` table
            $stmt = $db->prepare("INSERT INTO User (first_name, last_name, email, PasswordHash) VALUES (?, ?, ?, ?)"); 
            $hash = ''; // Since OAuth users don't have a password, leave this empty
            $stmt->bind_param("ssss", $first_name, $last_name, $email, $hash);
            $stmt->execute();
            $user_id = $stmt->insert_id; // Get the new User_id
        }

        // Now check if the OAuth user exists in `Sign_on_oauth` table
        $stmt = $db->prepare("SELECT oauth_id FROM Sign_on_oauth WHERE oauth_provider = ? AND oauth_uid = ?"); 
        $stmt->bind_param("ss", $oauth_provider, $oauth_uid);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) { 
            // Update existing OAuth user data if found
            $stmt = $db->prepare("UPDATE Sign_on_oauth SET User_id = ?, created_at = NOW() WHERE oauth_provider = ? AND oauth_uid = ?");
            $stmt->bind_param("iss", $user_id, $oauth_provider, $oauth_uid);
            $stmt->execute();
        } else {
            // Insert new OAuth user data
            $stmt = $db->prepare("INSERT INTO Sign_on_oauth (User_id, oauth_provider, oauth_uid, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("iss", $user_id, $oauth_provider, $oauth_uid);
            $stmt->execute();
        }
        
        // Output a successful response
        $output = [
            'status' => 1,
            'msg' => 'OAuth data inserted/updated successfully!',
            'user_data' => [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email
            ]
        ];
        echo json_encode($output);

    } else {
        echo json_encode(['error' => 'Account data is not available!']);
    }
} else {
    echo json_encode(['error' => 'Invalid request!']);
}
