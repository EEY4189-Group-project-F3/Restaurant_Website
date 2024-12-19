<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '2001ps,.', 'restaurant');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle order status update
// Handle order status update
// Handle order status update
// Handle order status update
// Handle order status update
// Handle order status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    // Update order status
    $stmt = $conn->prepare("UPDATE order_table SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
    $stmt->close();

    // If the order is completed, update earnings
    if ($status === 'Completed') {
        // Fetch the total_amount and order_date from the order
        $order_sql = "SELECT total_amount, DATE(date) AS order_date FROM order_table WHERE order_id = ?";
        $order_stmt = $conn->prepare($order_sql);
        $order_stmt->bind_param("i", $order_id);
        $order_stmt->execute();
        $order_stmt->bind_result($total_amount, $order_date);
        $order_stmt->fetch();
        $order_stmt->close();

        // Fetch the total of all completed orders for the same day
        $earnings_sql = "SELECT SUM(total_amount) AS daily_total FROM order_table WHERE DATE(date) = ?";
        $earnings_stmt = $conn->prepare($earnings_sql);
        $earnings_stmt->bind_param("s", $order_date);
        $earnings_stmt->execute();
        $earnings_stmt->bind_result($daily_total);
        $earnings_stmt->fetch();
        $earnings_stmt->close();

        // Check if daily_total is NULL (no completed orders for this day)
        if ($daily_total === NULL) {
            $daily_total = 0;  // If there are no orders for the day, set to 0
        }


        // Check if there is an earnings record for this date
        $check_earnings_sql = "SELECT id, total, daily_total FROM earning WHERE date = ?";
        $check_earnings_stmt = $conn->prepare($check_earnings_sql);
        $check_earnings_stmt->bind_param("s", $order_date);
        $check_earnings_stmt->execute();
        $check_earnings_stmt->store_result();

        if ($check_earnings_stmt->num_rows > 0) {
            // Earnings record exists, update the daily_total and cumulative total (total)
            $check_earnings_stmt->bind_result($id, $current_total, $current_daily_total);
            $check_earnings_stmt->fetch();

           

            // Update daily_total (current day's cumulative earnings)
            $update_daily_total = $daily_total;

            // Update total (cumulative earnings so far)
            $update_total = $current_total + $total_amount;

            // Update earnings record with new daily_total and total
            $update_earnings_sql = "UPDATE earning SET daily_total = ?, total = ? WHERE id = ?";
            $update_earnings_stmt = $conn->prepare($update_earnings_sql);
            $update_earnings_stmt->bind_param("ddi", $update_daily_total, $update_total, $id);
            $update_earnings_stmt->execute();
            $update_earnings_stmt->close();
        } else {
            // No earnings record for this date, insert a new one with daily_total and total
            $insert_earnings_sql = "INSERT INTO earning (date, daily_total, total) VALUES (?, ?, ?)";
            $insert_earnings_stmt = $conn->prepare($insert_earnings_sql);
            $insert_earnings_stmt->bind_param("sdd", $order_date, $daily_total, $total_amount); // Total for the first day
            $insert_earnings_stmt->execute();
            $insert_earnings_stmt->close();
        }

        $check_earnings_stmt->close();
    }
}





// Fetch all orders and associated food items
$sql = "SELECT o.order_id, o.total_amount, o.address, u.first_name, u.last_name, u.email, o.mobile, o.status
        FROM order_table o
        LEFT JOIN user u ON o.user_id = u.user_id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="../css/admin_dashboard.css">
    <style>
        body {
            display: flex;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
        }

        /* Sidebar styles */
        .sidebar {
            width: 250px;
            background-color: #333;
            color: white;
            padding-top: 20px;
            position: fixed;
            height: 100%;
            top: 0;
            left: 0;
            transition: 0.3s;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            border-bottom: 1px solid #444;
        }

        .sidebar a:hover {
            background-color: #555;
        }

        /* Main content styles */
        .main-content {
            margin-left: 260px;
            padding: 20px;
            flex-grow: 1;
        }

        header {
            background-color: #333;
            color: white;
            padding: 10px 20px;
        }

        header h1 {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }

        .btn-accept, .btn-reject, .btn-complete {
            padding: 5px 10px;
            border: none;
            color: white;
            cursor: pointer;
        }

        .btn-accept {
            background-color: green;
        }

        .btn-reject {
            background-color: red;
        }

        .btn-complete {
            background-color: blue;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>Admin Dashboard</h2>
    <a href="admin_home.php">Home</a>
    <a href="manage_orders.php">Manage Orders</a>
    <a href="manage_online_reservations.php">Online Reservations</a>
    <a href="earnings.php">Earnings</a>
    <a href="contact_messages.php">Contact Messages</a>
    <a href="logout.php">Logout</a>
</div

<!-- Main content -->
<div class="main-content">
    <header>
        <h1>Manage Orders</h1>
    </header>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Food</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Address</th>
                <th>Phone Number</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php
                        // Get all food items for the current order
                        $food_sql = "SELECT f.name, oi.quantity, f.price
                                     FROM order_item oi
                                     JOIN food_item f ON oi.food_id = f.food_id
                                     WHERE oi.order_id = ?";
                        $food_stmt = $conn->prepare($food_sql);
                        $food_stmt->bind_param("i", $row['order_id']);
                        $food_stmt->execute();
                        $food_result = $food_stmt->get_result();

                        // To calculate total price of the order
                        $total_price = 0;
                        $food_items = [];
                        while ($food = $food_result->fetch_assoc()) {
                            $total_price += $food['price'] * $food['quantity'];
                            $food_items[] = [
                                'name' => $food['name'],
                                'quantity' => $food['quantity'],
                                'price' => $food['price'],
                                'total' => $food['price'] * $food['quantity']
                            ];
                        }
                        $food_stmt->close();
                    ?>
                    <tr>
                        <td><a href="javascript:void(0);" onclick="openModal(<?php echo $row['order_id']; ?>)">#<?php echo htmlspecialchars($row['order_id']); ?></a></td>
                        <td><?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?><br>
                            <small><?php echo htmlspecialchars($row['email']); ?></small>
                        </td>
                        <td>
                            <?php echo implode('<br>', array_map(function ($food) { return $food['name']; }, $food_items)); ?>
                        </td>
                        <td>Rs. <?php echo number_format($row['total_amount'], 2); ?></td>
                        <td>
                            <?php 
                            // Display the quantity of each food item in the order
                            echo implode('<br>', array_map(function ($food) { 
                                return $food['name'] . " (Qty: " . $food['quantity'] . ")";
                            }, $food_items)); 
                            ?>
                        </td>
                        <td>Rs. <?php echo number_format($total_price, 2); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td><?php echo htmlspecialchars($row['mobile']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <?php if ($row['status'] === 'Pending'): ?>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                    <button type="submit" name="status" value="Accepted" class="btn-accept">Accept</button>
                                    <button type="submit" name="status" value="Rejected" class="btn-reject">Reject</button>
                                </form>
                            <?php elseif ($row['status'] === 'Accepted'): ?>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                    <button type="submit" name="status" value="Completed" class="btn-complete">Complete</button>
                                </form>
                            <?php else: ?>
                                <span><?php echo htmlspecialchars($row['status']); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10">No orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Structure -->
<div id="foodModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Order Details</h2>
        <div id="orderDetails"></div>
    </div>
</div>

<script>
    function openModal(orderId) {
        const modal = document.getElementById('foodModal');
        const orderDetails = document.getElementById('orderDetails');
        
        // Fetch order details via AJAX
        fetch(`get_order_details.php?order_id=${orderId}`)
            .then(response => response.json())
            .then(data => {
                orderDetails.innerHTML = `
                    <h3>Order #${data.order_id}</h3>
                    <p><strong>Customer:</strong> ${data.customer_name}</p>
                    <p><strong>Address:</strong> ${data.address}</p>
                    <p><strong>Phone Number:</strong> ${data.mobile}</p>
                    <p><strong>Items:</strong></p>
                    <ul>
                        ${data.food_items.map(item => `<li>${item.name} - Quantity: ${item.quantity} - Rs. ${item.total}</li>`).join('')}
                    </ul>
                    <p><strong>Total Price:</strong> Rs. ${data.total_price}</p>
                `;
            });

        modal.style.display = 'block';
    }

    function closeModal() {
        const modal = document.getElementById('foodModal');
        modal.style.display = 'none';
    }
</script>
</body>
</html>

<?php
$conn->close();
?>
