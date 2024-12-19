<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '2001ps,.', 'restaurant');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all contact messages
$sql = "SELECT c.contact_id, c.name, c.email, c.messsage, c.timestamp, u.first_name, u.last_name 
        FROM contact c 
        LEFT JOIN user u ON c.user_id = u.user_id
        ORDER BY c.timestamp DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages</title>
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

        .btn-view {
            padding: 5px 10px;
            border: none;
            color: white;
            cursor: pointer;
            background-color: #007BFF;
        }

        .btn-view:hover {
            background-color: #0056b3;
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
        <h1>Contact Messages</h1>
    </header>

    <table>
        <thead>
            <tr>
                <th>Message ID</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Message</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo htmlspecialchars($row['contact_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo substr(htmlspecialchars($row['messsage']), 0, 50) . '...'; ?></td>
                        <td><?php echo htmlspecialchars($row['timestamp']); ?></td>
                        <td>
                            <button class="btn-view" onclick="openModal(<?php echo $row['contact_id']; ?>)">View</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No messages found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Structure -->
<div id="messageModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Message Details</h3>
        <div id="messageDetails"></div>
    </div>
</div>

<!-- JavaScript -->
<script>
    function openModal(contactId) {
    var modal = document.getElementById("messageModal");
    var messageDetailsContainer = document.getElementById("messageDetails");

    // Fetch message details by contact ID
    fetch('get_message_details.php?contact_id=' + contactId)
        .then(response => response.json())
        .then(data => {
            console.log("Message details:", data);  // Log the message details received from the server

            if (data && data.messsage) {
                messageDetailsContainer.innerHTML = `
                    <p><strong>Message:</strong><br> ${data.messsage}</p>
                    <p><strong>From:</strong> ${data.first_name} ${data.last_name}</p>
                    <p><strong>Email:</strong> ${data.email}</p>
                    <p><strong>Date:</strong> ${data.timestamp}</p>
                `;
            } else {
                messageDetailsContainer.innerHTML = "<p>No details found for this message.</p>";
            }
        })
        .catch(error => {
            console.error("Error fetching message details:", error);
        });

    modal.style.display = "block";
}


    function closeModal() {
        var modal = document.getElementById("messageModal");
        modal.style.display = "none";
    }
</script>

</body>
</html>

<?php
$conn->close();
?>
