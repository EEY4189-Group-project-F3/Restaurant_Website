<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "2001ps,."; // Replace with the actual password
$dbname = "restaurant"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the form data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$password = $_POST['password'];  // Make sure to hash the password before storing it

// Check if the email already exists in the 'user' table
$sql = "SELECT * FROM user WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // If the email exists, set an error message in session and redirect to signup page
    $_SESSION['error_message'] = "User already registered with this email.";
    header("Location: signup.php"); // Redirect back to the sign-up page
    exit();
} else {
    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the 'user' table
    $insert_sql = "INSERT INTO user (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);

    if ($stmt->execute()) {
        // If registration is successful, redirect to the sign-in page
        header("Location: signin.php"); // Redirect to sign-in page after successful registration
        exit();
    } else {
        // Display a generic error message if the registration fails
        $_SESSION['error_message'] = "An error occurred during registration. Please try again.";
        header("Location: signup.php"); // Redirect back to the signup page
        exit();
    }
}

$stmt->close();
$conn->close();
?>
