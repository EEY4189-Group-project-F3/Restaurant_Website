<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Feastly Sign Up</title>
    <link rel="stylesheet" href="../css/SignUp.css" />
    <style>
      /* Modal styling */
      .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.4);
        padding-top: 60px;
      }

      .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
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


      #error-message {
  color: black; /* Set the text color to black */
}
    </style>
  </head>
  <body>
    <div class="container">
      <div class="form-section">
        <div class="logo">
          <h1>Feastly</h1>
        </div>
        <h2>Get Started!</h2>
        <p>Sign Up to Stay Informed and Engage with Our Network</p>
        <form id="signup-form" action="register.php" method="POST">
          <div class="input-group">
            <input
              type="text"
              name="first_name"
              placeholder="First Name"
              required
            />
            <input
              type="text"
              name="last_name"
              placeholder="Last Name"
              required
            />
          </div>
          <input type="email" name="email" placeholder="Email" required />
          <input
            type="password"
            name="password"
            placeholder="Password"
            required
          />
          <button type="submit" class="signup-btn">Sign Up</button>
          <p class="login-text">
            Already have an account? <a href="signin.php">Sign in</a>
          </p>
        </form>
      </div>
      <div class="image-section">
        <img src="../Images/item1.jpg" alt="Burger Image" />
      </div>
    </div>

    <!-- Modal for Error Message -->
    <div id="error-modal" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <p id="error-message"></p>
      </div>
    </div>

    <script>
      // Show error modal if there's an error message
      window.onload = function () {
        <?php if (isset($_SESSION['error_message'])) { ?>
          // Safely output the error message in JavaScript
          document.getElementById("error-message").textContent = "<?php echo htmlspecialchars($_SESSION['error_message'], ENT_QUOTES, 'UTF-8'); ?>";
          document.getElementById("error-modal").style.display = "block";
          <?php unset($_SESSION['error_message']); ?>
        <?php } ?>
      };

      // Close modal when clicking the 'x'
      document.querySelector(".close").addEventListener("click", function () {
        document.getElementById("error-modal").style.display = "none";
      });

      // Close modal when clicking outside of it
      window.onclick = function (event) {
        if (event.target == document.getElementById("error-modal")) {
          document.getElementById("error-modal").style.display = "none";
        }
      };
    </script>
  </body>
</html>
