<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '2001ps,.', 'restaurant');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the incoming JSON data
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['food_id'])) {
    $food_id = $data['food_id'];

    // Uncomment for debugging
    // echo "Food ID: " . $food_id;
    
    // Handle add to cart for logged-in users
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Check if item is already in the cart
        $sql = "SELECT * FROM cart WHERE user_id = ? AND food_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $food_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // If item is already in the cart, update the quantity
            $sql = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND food_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $food_id);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Item quantity updated']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update item quantity']);
            }
        } else {
            // If item is not in the cart, add a new entry
            $sql = "INSERT INTO cart (user_id, food_id, quantity) VALUES (?, ?, 1)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $food_id);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Item added to cart']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add item to cart']);
            }
        }

    } else {
        // Handle add to cart for guest users (non-logged-in)
        if (!isset($_SESSION['guest_cart'])) {
            $_SESSION['guest_cart'] = [];
        }

        // Add the item to the guest cart
        if (isset($_SESSION['guest_cart'][$food_id])) {
            $_SESSION['guest_cart'][$food_id] += 1;  // Increment quantity if the item is already in the cart
            $response = ['success' => true, 'message' => 'Item quantity updated in guest cart'];
        } else {
            $_SESSION['guest_cart'][$food_id] = 1;  // Add new item to the cart
            $response = ['success' => true, 'message' => 'Item added to guest cart'];
        }

        // Update the cart count for the guest
        $cart_count = count($_SESSION['guest_cart']);
        $response['cart_count'] = $cart_count;  // Add the cart count to the response

        // Send a single JSON response
        echo json_encode($response);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Food ID is missing']);
}

$conn->close();
?>
