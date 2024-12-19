<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Feastly Sign In</title>
    <link rel="stylesheet" href="../css/signin.css" />
    <script src="script.js" defer></script>
  </head>
  <body>
    <div class="container">
      <div class="form-section">
        <div class="logo">
          <h1>Feastly</h1>
        </div>
        <h2>Welcome back!</h2>
        <p>Log in to Access Your Account and Continue Exploring</p>
        <form id="signin-form">
          <input type="email" id="email" name="email" placeholder="Email" required />
          <input type="password" id="password" name="password" placeholder="Password" required />
          <div class="options">
            <label><input type="checkbox" /> Remember me </label>
            <a href="#" class="forgot-password">Forgot password?</a>
          </div>
          <button type="submit" class="signin-btn" id="signin">Sign In</button>
          <p class="signup-text">
            Don't have an account? <a href="SignUp.php">Sign Up</a>
            <a href="../index.php">Home</a>
          </p>
        </form>
      </div>
      <div class="image-section">
        <img src="../Images/item2.jpg" alt="Food Image" />
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
        const errorMessage = localStorage.getItem('error_message');
        if (errorMessage) {
          document.getElementById("error-message").textContent = errorMessage;
          document.getElementById("error-modal").style.display = "block";
          localStorage.removeItem('error_message');
        }
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

      // Handle form submission using AJAX
      document.getElementById("signin-form").addEventListener("submit", function (e) {
        e.preventDefault();
        
        let email = document.getElementById("email").value;
        let password = document.getElementById("password").value;

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "login.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
          if (xhr.status == 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.status === "success") {
              window.location.href = response.redirect; // Redirect to the respective page
            } else {
              localStorage.setItem('error_message', response.message);
              window.location.reload(); // Reload to show the error modal
            }
          }
        };

        xhr.send("email=" + email + "&password=" + password);
      });
    </script>
  </body>
</html>
