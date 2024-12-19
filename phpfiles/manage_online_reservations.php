<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '2001ps,.', 'restaurant');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle reservation status update
// Handle reservation status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id']) && isset($_POST['status'])) {
    $reservation_id = intval($_POST['reservation_id']);
    $status = $_POST['status'];

    // Check if the status is 'Accept' or 'Reject' and update the corresponding ENUM value
    if ($status == 'Accepted') {
        $status = 'Confirmed'; // Change to valid ENUM value
    } elseif ($status == 'Rejected') {
        $status = 'Completed'; // Change to valid ENUM value
    }

    // Update reservation status
    $stmt = $conn->prepare("UPDATE online_reservation SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $reservation_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all reservations
$sql = "SELECT r.id, r.date, r.time, r.n_of_guests, r.category, u.first_name, u.last_name, u.email, r.status
        FROM online_reservation r
        LEFT JOIN user u ON r.user_id = u.user_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Online Reservations</title>
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

        .btn-accept, .btn-reject {
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
</div>

<!-- Main content -->
<div class="main-content">
    <header>
        <h1>Manage Online Reservations</h1>
    </header>

    <table>
        <thead>
            <tr>
                <th>Reservation ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Time</th>
                <th>Guests</th>
                <th>Category</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><a href="javascript:void(0);" onclick="openModal(<?php echo $row['id']; ?>)">#<?php echo htmlspecialchars($row['id']); ?></a></td>
                        <td><?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?><br>
                            <small><?php echo htmlspecialchars($row['email']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['time']); ?></td>
                        <td><?php echo htmlspecialchars($row['n_of_guests']); ?></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <?php if ($row['status'] === 'Pending'): ?>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="reservation_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="status" value="Accepted" class="btn-accept">Accept</button>
                                    <button type="submit" name="status" value="Rejected" class="btn-reject">Reject</button>
                                </form>
                            <?php else: ?>
                                <span><?php echo htmlspecialchars($row['status']); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No reservations found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Structure -->
<div id="reservationModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Reservation Details</h2>
        <div id="reservationDetails"></div>
    </div>
</div>

<script>
    function openModal(reservationId) {
        const modal = document.getElementById('reservationModal');
        const reservationDetails = document.getElementById('reservationDetails');
        
        // Fetch reservation details via AJAX
        fetch(`get_reservation_details.php?reservation_id=${reservationId}`)
            .then(response => response.json())
            .then(data => {
                reservationDetails.innerHTML = `
                    <h3>Reservation #${data.id}</h3>
                    <p><strong>Customer:</strong> ${data.customer_name}</p>
                    <p><strong>Date:</strong> ${data.date}</p>
                    <p><strong>Time:</strong> ${data.time}</p>
                    <p><strong>Guests:</strong> ${data.n_of_guests}</p>
                    <p><strong>Category:</strong> ${data.category}</p>
                `;
            });

        modal.style.display = 'block';
    }

    function closeModal() {
        const modal = document.getElementById('reservationModal');
        modal.style.display = 'none';
    }
</script>

</body>
</html>

<?php
$conn->close();
?>
