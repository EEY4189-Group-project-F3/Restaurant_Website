<?php
session_start();

$response = ['logged_in' => false];  // Default response

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $response['logged_in'] = true;
}

echo json_encode($response);
?>
