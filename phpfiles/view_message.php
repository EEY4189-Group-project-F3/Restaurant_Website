<?php
if (isset($_GET['id'])) {
    $contact_id = $_GET['id'];

    $conn = new mysqli('localhost', 'root', '2001ps,.', 'restaurant');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM contact WHERE contact_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $contact_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $message = $result->fetch_assoc();

    if ($message) {
        // Display the message details
        $name = htmlspecialchars($message['name']);
        $email = htmlspecialchars($message['email']);
        $user_message = nl2br(htmlspecialchars($message['messsage']));
        $user_id = htmlspecialchars($message['user_id']);
    } else {
        $error_message = "Message not found.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 800px;
        }
        h2 {
            color: #333;
            text-align: center;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
            margin-bottom: 15px;
        }
        strong {
            color: #333;
        }
        .btn-back {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
            text-align: center;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            text-align: center;
            font-size: 18px;
        }
    </style>
</head>
<body>

<div class="container">
    <?php if (isset($error_message)): ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php else: ?>
        <h2>Message from <?php echo $name; ?></h2>
        <p><strong>Email:</strong> <?php echo $email; ?></p>
        <p><strong>Message:</strong> <?php echo $user_message; ?></p>
        <p><strong>User ID:</strong> <?php echo $user_id; ?></p>
    <?php endif; ?>

    <a href="admin_dashboard.php" class="btn-back">Back to Dashboard</a>
</div>

</body>
</html>
