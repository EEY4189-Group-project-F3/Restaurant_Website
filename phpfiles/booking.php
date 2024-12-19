<?php
session_start(); // Start the session to check login status
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Table Reservation - Fast Foods Restaurant</title>
    <link rel="stylesheet" href="../css/booking.css" />
    <link rel="shortcut icon" href="../icon.jpg" type="image/x-icon" />
    <style>
      /* Basic CSS for Modal */
      .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        
        background: rgba(0, 0, 0, 0.5);
        color:black;
        justify-content: center;
        align-items: center;
      }
      .modal-content {
        background: white;
        padding: 20px;
        border-radius: 5px;
        text-align: center;
      }
      .btn-close {
        background: #007bff;
        color: white;
        padding: 8px 12px;
        border: none;
        cursor: pointer;
        border-radius: 3px;
      }


      .booking {
  background-color: rgb(252, 152, 3);
  color: black;
  border: none;
  font-weight: bold;
  width: 120px;
  font-size: 1.2rem; /* Increased font size for better readability */
  height: 50px; /* Adjusted height */
  border-radius: 25px;
  display: flex; /* Ensures centering of text */
  justify-content: center; /* Centers text horizontally */
  align-items: center; /* Centers text vertically */
  transition: background-color 0.3s, color 0.3s;
}

.booking:hover {
  background-color: rgb(230, 120, 0); /* Darker hover color */
  color: white;
  text-shadow: 0 0 5px rgba(252, 152, 3, 0.6); /* Added glow effect */
}


 
    </style>
  </head>
  <body>
    <!-- Navigation Section -->
    <nav>
      <div class="logo-row">
        <img src="../Images/res-logo.png" alt="Logo" width="25px" height="35px" />
        <h2 class="title">Feastly</h2>
      </div>
      <section>
        <ul>
          <li><a href="../index.php" class="home">Home</a></li>
          <li><a href="./about.php">About</a></li>
          <li><a href="./menu.php">Menu</a></li>
          <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="./profile.php">Account</a></li>
          <?php else: ?>
            <li><a href="./signin.php">Account</a></li>
          <?php endif; ?>
          <li><a href="./service.php">Service</a></li>
          <li><a href="./contact.php">Contact</a></li>
          <li><a href="#" class="booking active">Booking</a></li>
        </ul>
      </section>
      <a href="cart.php">
        <img src="../Images/cart.png" alt="Cart" width="50px" height="50px" id="cart">
      </a>
    </nav>

    <!-- Main Reservation Section -->
    <div class="main">
      <h1 class="main-title">Table Reservation</h1>
      <p>Book your table online to ensure a hassle-free dining experience!</p>
    </div>

    <!-- Reservation Form -->
    <div class="reservation-form-container">
      <h2>Book Your Table</h2>
      <form id="reservationForm" class="reservation-form">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="Your Name" required />

        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" placeholder="Your Phone Number" required />

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Your Email" required />

        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required />

        <label for="time">Time:</label>
        <input type="time" id="time" name="time" required />

        <label for="guests">Number of Guests:</label>
        <input type="number" id="guests" name="guests" min="1" placeholder="Number of Guests" required />

        <!-- Category Selection -->
        <label for="category">Category:</label>
        <select id="category" name="category" required>
          <option value="" disabled selected>Select a category</option>
          <option value="breakfast">Breakfast</option>
          <option value="lunch">Lunch</option>
          <option value="dinner">Dinner</option>
        </select>
        <br />
        <button type="submit" class="btn-reserve">Reserve Table</button>
      </form>
    </div>

    <!-- Login Modal -->
    <div class="modal" id="loginModal">
      <div class="modal-content">
        <p>Please login to book a table.</p>
        <button class="btn-close" onclick="window.location.href='signin.php';">Login</button>
      </div>
    </div>

    <!-- JavaScript for Form Submission -->
    <script>
      document.getElementById("reservationForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch("process_booking.php", {
          method: "POST",
          body: formData,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.status === "error") {
              if (data.message.includes("login")) {
                document.getElementById("loginModal").style.display = "flex";
              } else {
                alert(data.message);
              }
            } else if (data.status === "success") {
              alert("Reservation successful! 🎉");
              window.location.reload();
            }
          })
          .catch((error) => console.error("Error:", error));
      });
    </script>

    <!-- Thank You Section -->
    <h3 class="thanks">
      Thank you for choosing Feastly! We are excited to host you and provide a memorable dining experience.
    </h3>

    <!-- Footer Section -->
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
          <a href="./phpfiles/about.php">About Us</a>
          <a href="#">Blog</a>
        </div>
        <div class="useful-nav">
          <h1>Useful Links</h1>
          <a href="./phpfiles/service.php">Service</a>
          <a href="#">Help & Support</a>
          <a href="#">Terms & Condition</a>
        </div>
        <div class="useful-nav">
          <h1>Social</h1>
          <a href="#">Facebook</a>
          <a href="#">Instagram</a>
          <a href="#">YouTube</a>
        </div>
      </div>
    </div>
  </body>
</html>
