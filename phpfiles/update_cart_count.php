<?php
session_start();

// Initialize cart count
$cart_count = 0;

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, get the cart count from the database
    $conn = new mysqli('localhost', 'root', '2001ps,.', 'restaurant');
    $sql = "SELECT COUNT(*) as cart_count FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_count = $result->fetch_assoc()['cart_count'];
    $conn->close();
} elseif (isset($_SESSION['guest_cart'])) {
    // For non-logged-in users (guest), check the session cart
    $cart_count = count($_SESSION['guest_cart']);
}

echo json_encode(['cart_count' => $cart_count]);
?>
