<?php
// Include this section to process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    
    // Assuming you have a user logged in, and their user_id is available in the session
    session_start();
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // Make sure to handle the user authentication properly

    // Save to database
    $conn = new mysqli('localhost', 'root', '2001ps,.', 'restaurant');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Add the timestamp column in the INSERT query (it will be auto-generated if it's not explicitly passed)
    $stmt = $conn->prepare("INSERT INTO contact (name, email, messsage, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $name, $email, $message, $user_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // Redirect or show a success message
    echo "<script>alert('Message sent successfully!'); window.location.href='contact.php';</script>";
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us</title>
    <link rel="stylesheet" href="../css/contact.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
      rel="stylesheet"
    />
    <link rel="shortcut icon" href="../icon.jpg" type="image/x-icon" />
  </head>
  <body>

    <nav>
        <div class="logo-row">
          <img
            src="../Images/res-logo.png"
            alt=""
            width="25px"
            height="35px"
          />
          <h2 class="title">Feastly</h2>
        </div>
  
        <section>
          <ul>
            <li><a href="../index.php" class="home">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="menu.php">Menu</a></li>
            <li><a href="profile.php">Account</a></li>
            <li><a href="#">Service</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li>
              <a href="booking.php"><button class="booking">Booking</button></a>
            </li>
          </ul>
        </section>
        <a href="cart.php">
  <img src="../Images/cart.png" alt="" width="50px" height="50px"  id="cart">
  </a>
      </nav>
   
    <div class="contact-hero">
      <h1>Contact Us</h1>
      <p>
        We’d love to hear from you! Fill out the form below to get in touch.
      </p>
    </div>

    <section class="contact-section">
      <div class="contact-form">
        <h2>Contact Form</h2>
        <!-- Form is now POSTing to itself to save data -->
        <form id="contactForm" method="POST" onsubmit="return validateContactForm()">
          <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required />
          </div>
          <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required />
          </div>
          <div class="form-group">
            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="5" required></textarea>
          </div>
          <button type="submit">Send Message</button>
        </form>
      </div>

      <div class="contact-info">
        <h2>Our Contact Information</h2>
        <p><strong>Phone:</strong> 011 876 8980</p>
        <p><strong>Email:</strong> contact@feastly.com</p>
        <p><strong>Location:</strong> Avenue 6th floor Nawala, Colombo</p>
      </div>
    </section>

    <div class="bottom">
        <div class="row">
          <div class="info">
            <h1>Feastly</h1>
            <p>
              www.Feastly.com <br />
              +94 45 567 8907 <br />
              Avenue 6th floor Nawala,Colombo
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

    <script>
      function validateContactForm() {
        let name = document.getElementById("name").value.trim()
        let email = document.getElementById("email").value.trim()
        let message = document.getElementById("message").value.trim()

        if (name === "") {
          alert("Please enter your name.")
          return false
        }

        if (email === "") {
          alert("Please enter your email.")
          return false
        }

        if (message === "") {
          alert("Please enter your message.")
          return false
        }

        return true // Submit form if all fields are valid
      }
    </script>
  </body>
</html>
