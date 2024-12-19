<?php
// Get the order ID from the query string
if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);

    // Database connection
    $conn = new mysqli('localhost', 'root', '2001ps,.', 'restaurant');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch food items for the given order
    $food_sql = "SELECT f.name, oi.quantity, f.price
                 FROM order_item oi
                 JOIN food_item f ON oi.food_id = f.food_id
                 WHERE oi.order_id = ?";
    $food_stmt = $conn->prepare($food_sql);
    $food_stmt->bind_param("i", $order_id);
    
    if ($food_stmt->execute()) {
        $food_result = $food_stmt->get_result();
        $food_items = [];

        // Fetch the data into an array
        while ($food = $food_result->fetch_assoc()) {
            $food_items[] = $food;
        }

        // Return the food items as JSON
        if (empty($food_items)) {
            echo json_encode(['error' => 'No food items found for this order.']);
        } else {
            echo json_encode($food_items);
        }
    } else {
        // Handle query execution failure
        echo json_encode(['error' => 'Query execution failed: ' . $food_stmt->error]);
    }

    $food_stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'Order ID is missing']);
}
?>
