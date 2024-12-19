<?php
session_start(); // Start session to check for user data

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php"); // Redirect to login page if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fast Foods Restaurant - Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link rel="shortcut icon" href="../icon.jpg" type="image/x-icon" />
    <link rel="stylesheet" href="../css/styles-account.css">
  </head>
  <body>
    <!-- Navbar -->
    <nav>
      <h2 class="title">Restaurant</h2>
      <section>
        <ul>
          <li><a href="../index.php" class="home">Home</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="menu.php">Menu</a></li>
          <li><a href="profile.php">Account</a></li>
          <li><a href="service.php">Service</a></li>
          <li><a href="contact.php">Contact</a></li>
          <li>
        <a href="booking.php"><button class="booking">Booking</button></a>
      </li>
        </ul>
      </section>
      <a href="cart.php">
        <img src="../Images/cart.png" alt="Cart" width="50px" height="50px" id="cart">
      </a>
    </nav>

    <!-- Profile Content -->
    <div class="ac-content">
      <div class="col">
        <div class="category">
          <img src="../Images/owner.png" alt="Profile Image" width="200px">
          <div class="menu">
            <a href="../index.php">Home</a>
            <a href="#">Account Settings</a>
            <a href="change_password.php">Change Password</a>
            <a href="logout.php">Log Out</a>
          </div>
        </div>
      </div>

      <!-- Profile Update Form -->
      <form action="update_profile.php" method="POST" class="form">
        <table>
          <tr>
            <td><label for="user_email">Email</label></td>
          </tr>
          <tr>
            <td><input type="email" name="user_email" value="<?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ''; ?>" required></td>
          </tr>
          <tr>
            <td><label for="user_first_name">First Name</label></td>
          </tr>
          <tr>
            <td><input type="text" name="user_first_name" value="<?php echo isset($_SESSION['user_first_name']) ? $_SESSION['user_first_name'] : ''; ?>" required></td>
          </tr>
          <tr>
            <td><label for="user_last_name">Last Name</label></td>
          </tr>
          <tr>
            <td><input type="text" name="user_last_name" value="<?php echo isset($_SESSION['user_last_name']) ? $_SESSION['user_last_name'] : ''; ?>" required></td>
          </tr>
          <tr>
            <td><label for="user_mobile">Mobile Number</label></td>
          </tr>
          <tr>
            <td><input type="text" name="user_mobile" value="<?php echo isset($_SESSION['user_mobile']) ? $_SESSION['user_mobile'] : ''; ?>" required></td>
          </tr>
          <tr>
            <td><label for="user_facebook">Facebook</label></td>
          </tr>
          <tr>
            <td><input type="text" name="user_facebook" value="<?php echo isset($_SESSION['user_facebook']) ? $_SESSION['user_facebook'] : ''; ?>"></td>
          </tr>
          <tr>
            <td><label for="user_instagram">Instagram</label></td>
          </tr>
          <tr>
            <td><input type="text" name="user_instagram" value="<?php echo isset($_SESSION['user_instagram']) ? $_SESSION['user_instagram'] : ''; ?>"></td>
          </tr>
          <tr>
            <td>
                <button type="submit">Update Profile</button>
            </td>
          </tr>
        </table>
      </form>
    </div>

    <!-- Footer -->
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
