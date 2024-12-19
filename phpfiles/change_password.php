<?php
session_start(); // Start session to check for user data

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php"); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id']; // Get user ID from session
$current_password_error = ""; // Initialize error variables
$new_password_error = "";
$confirm_password_error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate the form fields
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error_message = "Please fill all fields.";
    } else {
        // Connect to your database (Make sure to replace with your credentials)
        $db = new mysqli("localhost", "root", "2001ps,.", "restaurant");

        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }

        // Prepare the query to get the current password for the logged-in user
        $query = "SELECT password FROM user WHERE user_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $user_id);  // Assuming $user_id holds the current user's ID
        $stmt->execute();
        $stmt->bind_result($stored_password);
        $stmt->fetch();
        $stmt->close();

        // Check if the current password matches
        if (password_verify($current_password, $stored_password)) {
            // Check if the new password and confirm password match
            if ($new_password === $confirm_password) {
                // Hash the new password
                $new_password_hashed = password_hash($new_password, PASSWORD_BCRYPT);

                // Prepare the update query to change the password
                $update_query = "UPDATE user SET password = ? WHERE user_id = ?";
                $stmt = $db->prepare($update_query);
                $stmt->bind_param("si", $new_password_hashed, $user_id);

                if ($stmt->execute()) {
                    $success_message = "Password updated successfully!";
                } else {
                    $error_message = "Failed to update the password. Please try again.";
                }
                $stmt->close();
            } else {
                $error_message = "New password and confirmation password do not match.";
            }
        } else {
            $error_message = "Current password is incorrect.";
        }
        $db->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Change Password - Fast Foods Restaurant</title>
    <link rel="stylesheet" href="../styles.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
    <link rel="shortcut icon" href="icon.jpg" type="image/x-icon" />
    <link rel="stylesheet" href="styles-account.css">
  </head>
  <body>
    <nav>
      <h2 class="title">Restaurant</h2>
      <section>
        <ul>
          <li><a href="../index.php" class="home">Home</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="menu.php">Menu</a></li>
          <li><a href="profile.php">Account</a></li>
          <li><a href="#">Service</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
      </section>
      <a href="cart.php">
  <img src="../Images/cart.png" alt="" width="50px" height="50px"  id="cart">
  </a>
    </nav>

    <div class="ac-content">
        <div class="col">
            <div class="category">
                <img src="./images/owner.png" alt="" width="200px">
                <div class="menu">
                    <a href="index.php">Home</a>
                    <a href="profile.php">Account Setting</a>
                    <a href="change_password.php">Change Password</a>
                    <a href="logout.php">Log Out</a>
                </div>
            </div>
        </div>

        <form action="change_password.php" method="POST" class="form">
            <table>
                <tr>
                    <td><label for="current-password">Current Password</label></td>
                </tr>
                <tr>
                    <td><input type="password" name="current_password" id="current-password" required /></td>
                </tr>
                <tr>
                    <td><label for="new-password">New Password</label></td>
                </tr>
                <tr>
                    <td><input type="password" name="new_password" id="new-password" required /></td>
                </tr>
                <tr>
                    <td><label for="confirm-password">Confirm New Password</label></td>
                </tr>
                <tr>
                    <td><input type="password" name="confirm_password" id="confirm-password" required /></td>
                </tr>
                <tr>
                    <td>
                        <button type="submit">Update Password</button>
                    </td>
                </tr>
            </table>
        </form>

        <?php if (isset($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php elseif (isset($success_message)): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>
    </div>

    <div class="bottom">
        <div class="row">
            <div class="info">
                <h1>Feastly</h1>
                <p>
                    www.Feastly.com <br />
                    +94 45 567 8907 <br />
                    Avenue 6th floor Nawala, Colombo
                </p>
            </div>
            <div class="menu-nav">
                <h1>Our Menu</h1>
                <a href="#">Breakfast</a>
                <a href="#">Lunch</a>
                <a href="#">Dinner</a>
                <a href="#">Appetizer</a>
            </div>
            <div class="info-nav">
                <h1>Information</h1>
                <a href="#">About Us</a>
                <a href="#">Blog</a>
            </div>
            <div class="useful-nav">
                <h1>Useful Links</h1>
                <a href="#">Service</a>
                <a href="#">Help & Support</a>
                <a href="#">Terms & Condition</a>
            </div>
            <div class="useful-nav">
                <h1>Social</h1>
                <a href="#">Facebook</a>
                <a href="#">Instagram</a>
                <a href="#">Youtube</a>
            </div>
        </div>
    </div>
  </body>
</html>
