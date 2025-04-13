<?php
// process_contact.php
require_once 'config.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Validate inputs
    if (empty($full_name) || empty($email) || empty($message)) {
        $response['message'] = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Please enter a valid email address.';
    } else {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO contact_messages (full_name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $full_name, $email, $message);
        
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Your message has been sent successfully!';
        } else {
            $response['message'] = 'Error sending message. Please try again.';
        }
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
