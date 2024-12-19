<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '2001ps,.', 'restaurant');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user ID from session for logged-in users
$user_id = $_SESSION['user_id'] ?? null;

// Get POST data from the form
$full_name = $_POST['full_name'] ?? '';
$address = $_POST['address'] ?? '';
$city = $_POST['city'] ?? '';
$mobile = $_POST['mobile'] ?? '';
$total_amount = $_POST['total_amount'] ?? 0;

// Initialize cart items and totals
$cart_items = [];
$subtotal = 0;
$delivery_fee = 300; 
$total = 0;

// Retrieve cart details from session or database for both logged-in and guest users
if ($user_id) {
    // Fetch cart items for logged-in users
    $sql = "SELECT f.name, f.price, c.quantity, c.food_id
            FROM cart c 
            JOIN food_item f ON c.food_id = f.food_id 
            WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $row['total_price'] = $row['price'] * $row['quantity'];
        $subtotal += $row['total_price'];
        $cart_items[] = $row;
    }
    $stmt->close();
} elseif (isset($_SESSION['guest_cart'])) {
    // Fetch cart items for guest users
    foreach ($_SESSION['guest_cart'] as $food_id => $quantity) {
        $sql = "SELECT name, price, food_id FROM food_item WHERE food_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $food_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $row['quantity'] = $quantity;
            $row['total_price'] = $row['price'] * $quantity;
            $subtotal += $row['total_price'];
            $cart_items[] = $row;
        }
        $stmt->close();
    }
} else {
    die("No cart found. Please add items to the cart.");
}

// Calculate total
$total = $subtotal + $delivery_fee;

// If the user is logged in, use their user_id. For guest, we can set it to null or 0
if ($user_id) {
    // Insert into order_table for logged-in users (date is automatically set to CURRENT_TIMESTAMP)
    $order_sql = "INSERT INTO order_table (user_id, total_amount, address, mobile, status, full_name, city) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($order_sql);
    $status = "Pending"; // Default order status
    $stmt->bind_param("iisssss", $user_id, $total_amount, $address, $mobile, $status, $full_name, $city);
    $stmt->execute();
    if ($stmt->error) {
        die("Error inserting into order_table: " . $stmt->error);
    }
    $order_id = $stmt->insert_id; // Get the last inserted order_id
    $stmt->close();
} else {
    // Insert into order_table for guest users (user_id as NULL)
    $order_sql = "INSERT INTO order_table (user_id, total_amount, address, mobile, status, full_name, city) VALUES (NULL, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($order_sql);
    $status = "Pending"; // Default order status
    $stmt->bind_param("isssss", $total_amount, $address, $mobile, $status, $full_name, $city);
    $stmt->execute();
    if ($stmt->error) {
        die("Error inserting into order_table: " . $stmt->error);
    }
    $order_id = $stmt->insert_id; // Get the last inserted order_id
    $stmt->close();
}

// Insert into order_item table
foreach ($cart_items as $item) {
    $order_item_sql = "INSERT INTO order_item (order_id, food_id, quantity) VALUES (?, ?, ?)";
    $order_item_stmt = $conn->prepare($order_item_sql);
    $order_item_stmt->bind_param("iii", $order_id, $item['food_id'], $item['quantity']);
    $order_item_stmt->execute();
    if ($order_item_stmt->error) {
        die("Error inserting into order_item table: " . $order_item_stmt->error);
    }
    $order_item_stmt->close();
}

// Clear the cart (for logged-in users)
if ($user_id) {
    $clear_cart_sql = "DELETE FROM cart WHERE user_id = ?";
    $clear_stmt = $conn->prepare($clear_cart_sql);
    $clear_stmt->bind_param("i", $user_id);
    $clear_stmt->execute();
    $clear_stmt->close();
} elseif (isset($_SESSION['guest_cart'])) {
    // Clear guest cart
    unset($_SESSION['guest_cart']);
}

// Close the database connection
$conn->close();

// Redirect to confirmation page
header("Location: order_confirmation.php?order_id=" . $order_id);
exit();
?>
