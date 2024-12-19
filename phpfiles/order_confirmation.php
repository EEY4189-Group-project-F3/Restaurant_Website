<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '2001ps,.', 'restaurant');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user ID from session
$user_id = $_SESSION['user_id'] ?? null;

// Initialize cart items and totals
$cart_items = [];
$total = 0;
$address = "User Address"; // Replace with actual address field
$mobile = "User Mobile"; // Replace with actual mobile field
$status = "Pending"; // Default status

// If order is placed (e.g., form submission or session status), insert order and order_items
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    // Insert into order_table
    $order_sql = "INSERT INTO order_table (user_id, total_amount, address, mobile, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($order_sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("iisss", $user_id, $total, $address, $mobile, $status);
    $stmt->execute();
    if ($stmt->error) {
        die("Error executing statement: " . $stmt->error);
    }
    $order_id = $stmt->insert_id; // Get the last inserted order_id
    $stmt->close();

    // Insert items into order_item
    foreach ($_SESSION['cart'] as $food_id => $quantity) {
        // Fetch price for the food item
        $food_sql = "SELECT price FROM food_item WHERE food_id = ?";
        $food_stmt = $conn->prepare($food_sql);
        $food_stmt->bind_param("i", $food_id);
        $food_stmt->execute();
        $food_result = $food_stmt->get_result();
        
        if ($food_result->num_rows == 0) {
            die("Error: Food ID $food_id not found in food_item table.");
        }

        $food_row = $food_result->fetch_assoc();
        $price = $food_row['price'];
        $food_stmt->close();

        // Insert into order_item table
        $order_item_sql = "INSERT INTO order_item (order_id, food_id, quantity, price) VALUES (?, ?, ?, ?)";
        $order_item_stmt = $conn->prepare($order_item_sql);
        if ($order_item_stmt === false) {
            die("Error preparing order_item statement: " . $conn->error);
        }
        $order_item_stmt->bind_param("iiii", $order_id, $food_id, $quantity, $price);
        $order_item_stmt->execute();
        if ($order_item_stmt->error) {
            die("Error executing order_item insertion: " . $order_item_stmt->error);
        }
        $order_item_stmt->close();
    }

    // Clear the cart
    unset($_SESSION['cart']);

    // Redirect to confirmation page with the order ID
    header("Location: confirmation.php?order_id=" . $order_id);
    exit();
}

// Fetch order ID from URL
$order_id = $_GET['order_id'] ?? null;

// Fetch order details
$order_details = [];
$ordered_items = [];
if ($order_id) {
    // Fetch order details from order_table
    $order_sql = "SELECT * FROM order_table WHERE order_id = ?";
    $stmt = $conn->prepare($order_sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order_details = $stmt->get_result()->fetch_assoc();

    // Fetch ordered items from order_item
    $items_sql = "SELECT f.name, oi.quantity, f.price 
                  FROM order_item oi 
                  JOIN food_item f ON oi.food_id = f.food_id 
                  WHERE oi.order_id = ?";
    $items_stmt = $conn->prepare($items_sql);
    $items_stmt->bind_param("i", $order_id);
    $items_stmt->execute();
    $ordered_items = $items_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="../css/styles2.css">
</head>
<body>
    <header>
        <!-- Add your navbar content here -->
    </header>

    <div class="container">
        <div class="order-summary">
            <h2>Thank you for your order!</h2>
            <?php if (!empty($order_details)): ?>
                <p class="order-id">Order ID: <span><?php echo $order_details['order_id']; ?></span></p>
                <p>From: <?php echo htmlspecialchars($order_details['address'] . ', ' . $order_details['city']); ?></p>

                <div class="order-details">
                    <h3>Order Details</h3>
                    <ul>
                        <?php foreach ($ordered_items as $item): ?>
                            <li>
                                <?php echo htmlspecialchars($item['name']); ?> 
                                x<?php echo $item['quantity']; ?> - Rs. <?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="total">
                        <p>Subtotal: <span>Rs. <?php echo number_format($order_details['total_amount'] - 300, 2); ?></span></p>
                        <p>Delivery Fee: <span>Rs. 300.00</span></p>
                        <h4>Order Total: <span>Rs. <?php echo number_format($order_details['total_amount'], 2); ?></span></h4>
                    </div>
                </div>
            <?php else: ?>
                <p>Invalid Order ID or order not found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
