<?php
session_start();

// Assuming the admin's name is stored in session or database
$admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : "Admin";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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

        .welcome-message {
            background-color: #007BFF;
            color: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .welcome-message h2 {
            margin: 0;
        }

        .dashboard-content {
            display: flex;
            gap: 20px;
            margin-top: 30px;
        }

        .dashboard-box {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 30%;
            padding: 20px;
            text-align: center;
        }

        .dashboard-box h3 {
            margin-bottom: 10px;
        }

        .dashboard-box p {
            font-size: 16px;
        }

        .btn {
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #0056b3;
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
        <h1>Welcome to the Admin Dashboard</h1>
    </header>

    <!-- Welcome Message -->
    <div class="welcome-message">
        <h2>Welcome, <?php echo htmlspecialchars($admin_name); ?>!</h2>
        <p>Here you can manage orders, view contact messages, and monitor the restaurant's activities.</p>
    </div>

    <!-- Dashboard Content -->
    <div class="dashboard-content">
        <!-- Box 1: Orders -->
        <div class="dashboard-box">
            <h3>Manage Orders</h3>
            <p>View and manage all customer orders.</p>
            <a href="manage_orders.php" class="btn">Go to Orders</a>
        </div>

        <!-- Box 2: Contact Messages -->
        <div class="dashboard-box">
            <h3>Contact Messages</h3>
            <p>View messages from customers and respond accordingly.</p>
            <a href="contact_messages.php" class="btn">View Messages</a>
        </div>

        <!-- Box 3: Earnings (new box) -->
        <div class="dashboard-box">
            <h3>Earnings</h3>
            <p>View the restaurant's earnings overview.</p>
            <a href="earnings.php" class="btn">Go to Earnings</a>
        </div>

        <!-- Box 4: Settings -->
        <div class="dashboard-box">
            <h3>Online Reservations</h3>
            <p>View & Manage Your Online Reservations</p>
            <a href="manage_online_reservations.php" class="btn">Go to Online Reservations</a>
        </div>
    </div>
</div>

</body>
</html>
