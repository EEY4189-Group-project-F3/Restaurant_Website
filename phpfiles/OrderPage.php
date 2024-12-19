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
$subtotal = 0;
$delivery_fee = 300; 
$total = 0;

// Retrieve cart details
if ($user_id) {
    // Fetch cart items for logged-in users
    $sql = "SELECT f.name, f.price, c.quantity 
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
        $sql = "SELECT name, price FROM food_item WHERE food_id = ?";
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
}

// Calculate total
$total = $subtotal + $delivery_fee;

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cash On Delivery</title>
    <link rel="stylesheet" href="../css/styles4.css">
</head>
<body>
<header>
    <div class="navbar">
        <h1 class="logo">Feastly</h1>
        <nav>
            <ul>
                <li><a href="../index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="#">Pages</a></li>
                <li><a href="service.php">Service</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
        <button class="book-table">BOOK TABLE</button>
    </div>
</header>

<div class="container">
    <div class="order-summary">
        <h2>Cash On Delivery <span>🛒</span></h2>
        <div class="order-details">
            <h3>Your Order</h3>
            <ul>
                <?php foreach ($cart_items as $item): ?>
                    <li>
                        <?php echo htmlspecialchars($item['name']); ?> 
                        x<?php echo $item['quantity']; ?> - Rs. <?php echo number_format($item['total_price'], 2); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="total">
                <p>Subtotal: <span>Rs. <?php echo number_format($subtotal, 2); ?></span></p>
                <p>Delivery Fee: <span>Rs. <?php echo number_format($delivery_fee, 2); ?></span></p>
                <h4>Total: <span>Rs. <?php echo number_format($total, 2); ?></span></h4>
            </div>
        </div>
    </div>

    <div class="delivery-details">
        <h3>Delivery Details</h3>
        <form action="process_order.php" method="post">
            <label for="full-name">Full Name</label>
            <input type="text" id="full-name" name="full_name" placeholder="John Smith" required>
            
            <label for="address">Address</label>
            <input type="text" id="address" name="address" placeholder="Nawala, Colombo" required>
            
            <!-- Removed the Province field -->
            
            <label for="city">City</label>
            <input type="text" id="city" name="city" placeholder="Nawala" required>

            <label for="mobile">Mobile Number</label>
            <input type="text" id="mobile" name="mobile" placeholder="+94 70 567 8765" required>
            
            <div class="terms">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">I agree to the <a href="#">terms and conditions</a>.</label>
            </div>

            <input type="hidden" name="total_amount" value="<?php echo $total; ?>">
            <button type="submit" class="order-now">Order Now</button>
        </form>
    </div>
</div>
</body>
</html>
