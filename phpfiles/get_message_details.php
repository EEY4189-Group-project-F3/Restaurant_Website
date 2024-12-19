<?php
// get_message_details.php
session_start();

// Check if contact_id is set
if (isset($_GET['contact_id'])) {
    $contact_id = $_GET['contact_id'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '2001ps,.', 'restaurant');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to get the message details
    $sql = "SELECT c.contact_id, c.name, c.email, c.messsage, c.timestamp, u.first_name, u.last_name 
            FROM contact c 
            LEFT JOIN user u ON c.user_id = u.user_id
            WHERE c.contact_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $contact_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a message was found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Return the message details as JSON
        echo json_encode($row);
    } else {
        // Return an empty response if no message is found
        echo json_encode([]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode([]);
}
?>
