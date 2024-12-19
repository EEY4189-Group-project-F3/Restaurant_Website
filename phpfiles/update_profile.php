<?php
session_start(); // Start session to check for user data

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php"); // Redirect to login page if not logged in
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "2001ps,."; // Use your MySQL root password
$dbname = "restaurant";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission to update profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get updated user details from form
    $user_id = $_SESSION['user_id'];
    $user_email = $_POST['user_email'];
    $user_first_name = $_POST['user_first_name'];
    $user_last_name = $_POST['user_last_name'];
    $user_mobile = $_POST['user_mobile'];
    $user_facebook = $_POST['user_facebook'];
    $user_instagram = $_POST['user_instagram'];

    // Prepare SQL query to update user details
    $stmt = $conn->prepare("UPDATE user SET email = ?, first_name = ?, last_name = ?, mobile = ?, facebook = ?, instagram = ? WHERE user_id = ?");
    $stmt->bind_param("ssssssi", $user_email, $user_first_name, $user_last_name, $user_mobile, $user_facebook, $user_instagram, $user_id);

    if ($stmt->execute()) {
        // Update session variables
        $_SESSION['user_email'] = $user_email;
        $_SESSION['user_first_name'] = $user_first_name;
        $_SESSION['user_last_name'] = $user_last_name;
        $_SESSION['user_mobile'] = $user_mobile;
        $_SESSION['user_facebook'] = $user_facebook;
        $_SESSION['user_instagram'] = $user_instagram;

        echo "Profile updated successfully.";
    } else {
        echo "Error updating profile: " . $conn->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fast Foods Restaurant</title>
    <link rel="stylesheet" href="styles.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../css/styles-account.css">
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
                    <a href="#">Account Setting</a>
                    <a href="#">Change Password</a>
                    <a href="logout.php">Log Out</a> <!-- Linking to logout script -->
                </div>
            </div>
        </div>

        <form action="update_profile.php" method="POST" class="form">
            <table>
                <tr>
                    <td><label for="user_email">Email</label></td>
                </tr>
                <tr>
                    <td><input type="email" name="user_email" value="<?php echo $_SESSION['user_email']; ?>" required></td>
                </tr>
                <tr>
                    <td><label for="user_first_name">First Name</label></td>
                </tr>
                <tr>
                    <td><input type="text" name="user_first_name" value="<?php echo $_SESSION['user_first_name']; ?>" required></td>
                </tr>
                <tr>
                    <td><label for="user_last_name">Last Name</label></td>
                </tr>
                <tr>
                    <td><input type="text" name="user_last_name" value="<?php echo $_SESSION['user_last_name']; ?>" required></td>
                </tr>
                <tr>
                    <td><label for="user_mobile">Mobile Number</label></td>
                </tr>
                <tr>
                    <td><input type="text" name="user_mobile" value="<?php echo $_SESSION['user_mobile']; ?>" required></td>
                </tr>
                <tr>
                    <td><label for="user_facebook">Facebook</label></td>
                </tr>
                <tr>
                    <td><input type="text" name="user_facebook" value="<?php echo $_SESSION['user_facebook']; ?>"></td>
                </tr>
                <tr>
                    <td><label for="user_instagram">Instagram</label></td>
                </tr>
                <tr>
                    <td><input type="text" name="user_instagram" value="<?php echo $_SESSION['user_instagram']; ?>"></td>
                </tr>
                <tr>
                    <td>
                        <button type="submit">Update Profile</button>
                    </td>
                </tr>
            </table>
        </form>
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
