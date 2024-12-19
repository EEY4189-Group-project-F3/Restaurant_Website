<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '2001ps,.', 'restaurant');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set content type to JSON for AJAX responses
header('Content-Type: text/html; charset=UTF-8');


// Suppress unintended output
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Fetch user ID from session for logged-in users
$user_id = $_SESSION['user_id'] ?? null;

// Handle quantity update (if triggered by AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $food_id = $_POST['food_id'];
    $quantity = intval($_POST['quantity']);

    if ($quantity <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid quantity']);
        exit;
    }

    if ($user_id) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE food_id = ? AND user_id = ?");
        $stmt->bind_param("iii", $quantity, $food_id, $user_id);
        $stmt->execute();
        echo json_encode(['success' => $stmt->affected_rows > 0]);
        $stmt->close();
    } else {
        if (isset($_SESSION['guest_cart'][$food_id])) {
            $_SESSION['guest_cart'][$food_id] = $quantity;
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Item not found in guest cart']);
        }
    }
    exit;
}

// Handle item removal (if triggered by AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item_id'])) {
    $remove_id = $_POST['remove_item_id'];

    if ($user_id) {
        $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $remove_id, $user_id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Item removed successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to remove item']);
        }
        $stmt->close();
    } else {
        if (isset($_SESSION['guest_cart'][$remove_id])) {
            unset($_SESSION['guest_cart'][$remove_id]);
            echo json_encode(['success' => true, 'message' => 'Item removed successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Item not found in guest cart']);
        }
    }
    exit;
}

// Fetch cart items for the user or guest
$cart_items = [];
$total = 0;

if ($user_id) {
    $sql = "SELECT c.cart_id, f.name, f.description, f.price, f.photo_url, c.date_time 
            FROM cart c 
            JOIN food_item f ON c.food_id = f.food_id 
            WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $total += $row['price'];
        $cart_items[] = $row;
    }
    $stmt->close();
} elseif (isset($_SESSION['guest_cart'])) {
    foreach ($_SESSION['guest_cart'] as $food_id => $quantity) {
        $sql = "SELECT food_id, name, description, price, photo_url FROM food_item WHERE food_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $food_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $row['quantity'] = $quantity;
            $total += $row['price'] * $quantity;
            $cart_items[] = $row;
        }
        $stmt->close();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="../css/cart.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <nav>
        <div class="logo-row">
            <img src="../Images/res-logo.png" alt="Logo" width="25px" height="35px">
            <h2 class="title">Feastly</h2>
        </div>
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li><a href="menu.php">Menu</a></li>
            <li><a href="profile.php">Account</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>
</header>
<main>
    <h1>Your Cart</h1>
    <div class="cart-container">
    <?php if (count($cart_items) > 0): ?>
        <?php foreach ($cart_items as $item): ?>
            <div class="cart-item" 
                 data-cart-id="<?php echo htmlspecialchars($item['cart_id'] ?? $item['food_id'] ?? ''); ?>">
                <img src="<?php echo htmlspecialchars($item['photo_url']); ?>" 
                     alt="<?php echo htmlspecialchars($item['name']); ?>">
                <div class="item-details">
                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p><?php echo htmlspecialchars($item['description']); ?></p>
                    <p><strong>Rs. <?php echo number_format($item['price'], 2); ?></strong></p>

                    <p class="quantity">
                        Quantity: 
                        <button class="decrease-btn">-</button>
                        <span class="quantity-value"><?php echo $item['quantity'] ?? 1; ?></span>
                        <button class="increase-btn">+</button>
                    </p>

                    
                    <button class="remove-btn">Remove</button>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="cart-total">
    <h2>Total: Rs. <?php echo number_format($total, 2); ?></h2>
    <!-- Add a form for checkout -->
    <form action="orderpage.php" method="post">
        <input type="hidden" name="cart_total" value="<?php echo htmlspecialchars($total); ?>">
        <button type="submit" class="checkout-btn">Proceed to Checkout</button>
    </form>
</div>

    <?php else: ?>
        <p>Your cart is empty. <a href="menu.php">Browse Menu</a></p>
    <?php endif; ?>
    </div>
</main>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const cartContainer = document.querySelector('.cart-container');
    const totalElement = document.querySelector('.cart-total h2');
    const checkoutButton = document.querySelector('.checkout-btn');

    // Handle checkout button click
    checkoutButton.addEventListener('click', () => {
        window.location.href = "orderpage.php"; // Replace with the actual path to your order page
    });

    cartContainer.addEventListener('click', (event) => {
        const button = event.target;
        const cartItem = button.closest('.cart-item');
        const foodId = cartItem?.getAttribute('data-cart-id'); // ID of the food item
        const quantityElement = cartItem?.querySelector('.quantity-value');
        let quantity = parseInt(quantityElement?.textContent || 1);

        // Handle quantity increase
        if (button.classList.contains('increase-btn')) {
            quantity += 1;
            updateQuantity(foodId, quantity, quantityElement);
        }

        // Handle quantity decrease
        if (button.classList.contains('decrease-btn')) {
            if (quantity > 1) {
                quantity -= 1;
                updateQuantity(foodId, quantity, quantityElement);
            } else {
                alert('Minimum quantity is 1.');
            }
        }

        // Handle item removal
        if (button.classList.contains('remove-btn')) {
            fetch('cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `remove_item_id=${foodId}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cartItem.remove(); // Remove item from DOM
                        updateTotalPrice(); // Update total price
                        if (cartContainer.querySelectorAll('.cart-item').length === 0) {
                            showEmptyCartMessage(); // Show empty cart message if no items left
                        }
                        alert('Item removed successfully.');
                    } else {
                        alert('Failed to remove item: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error removing item:', error);
                    alert('An error occurred. Please try again.');
                });
        }
    });

    // Function to update quantity via AJAX
    function updateQuantity(foodId, quantity, quantityElement) {
        fetch('cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `update_quantity=1&food_id=${foodId}&quantity=${quantity}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    quantityElement.textContent = quantity; // Update quantity in UI
                    updateTotalPrice(); // Update total price
                    
                } else {
                    alert('Failed to update quantity: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error updating quantity:', error);
                alert('An error occurred. Please try again.');
            });
    }

    // Function to update the total price
    function updateTotalPrice() {
        const cartItems = cartContainer.querySelectorAll('.cart-item');
        let total = 0;

        cartItems.forEach(item => {
            const price = parseFloat(item.querySelector('strong').textContent.replace('Rs. ', ''));
            const quantity = parseInt(item.querySelector('.quantity-value').textContent);
            total += price * quantity;
        });

        totalElement.textContent = `Total: Rs. ${total.toFixed(2)}`;
    }

    // Function to show the empty cart message
    function showEmptyCartMessage() {
        cartContainer.innerHTML = `
            <p>Your cart is empty. <a href="menu.php">Browse Menu</a></p>
        `;
        totalElement.textContent = `Total: Rs. 0.00`;
    }
});
</script>

</body>
</html>
