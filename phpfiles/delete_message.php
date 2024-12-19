<?php
if (isset($_GET['id'])) {
    $contact_id = $_GET['id'];

    $conn = new mysqli('localhost', 'root', '2001ps,.', 'restaurant');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "DELETE FROM contact WHERE contact_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $contact_id);
    if ($stmt->execute()) {
        echo "Message deleted successfully!";
    } else {
        echo "Error deleting message.";
    }

    $stmt->close();
    $conn->close();

    // Redirect to the admin dashboard
    header("Location: admin_dashboard.php");
    exit();
}
?>
