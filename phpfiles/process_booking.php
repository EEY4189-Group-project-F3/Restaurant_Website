<?php
session_start();
header('Content-Type: application/json'); // Set header for JSON response

// Database connection (update with your own credentials)
$host = 'localhost';
$dbname = 'restaurant';
$user = 'root';
$pass = '2001ps,.';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please login to book a table.']);
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $guests = (int)$_POST['guests'];
    $category = $_POST['category'];
    $user_id = $_SESSION['user_id'];

    // Simple validation
    if (empty($name) || empty($phone) || empty($email) || empty($date) || empty($time) || empty($category)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    try {
        // Insert data into the online_reservation table
        $stmt = $pdo->prepare("
            INSERT INTO online_reservation (date, time, n_of_guests, category, user_id)
            VALUES (:date, :time, :guests, :category, :user_id)
        ");

        $stmt->execute([
            ':date' => $date,
            ':time' => $time,
            ':guests' => $guests,
            ':category' => $category,
            ':user_id' => $user_id,
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Reservation successful!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to reserve table.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
