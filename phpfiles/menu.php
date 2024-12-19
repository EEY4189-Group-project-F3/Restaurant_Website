<?php
session_start();

// Initialize cart count
$cart_count = 0;

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, get the cart count from the database
    $conn = new mysqli('localhost', 'root', '2001ps,.', 'restaurant');
    $sql = "SELECT COUNT(*) as cart_count FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_count = $result->fetch_assoc()['cart_count'];
    $conn->close();
} elseif (isset($_SESSION['guest_cart'])) {
    // For non-logged-in users (guest), check the session cart
    $cart_count = count($_SESSION['guest_cart']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Feastly Menu</title>
  <link rel="stylesheet" href="../css/menu.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet" />


    <link rel="shortcut icon" href="../icon.jpg" type="image/x-icon" />
  <style>
       #cart {
      position: relative;
      display: inline-block;
      width: 50px; /* Ensures consistent size */
      height: 50px; /* Ensures consistent size */
      margin: 0 15px; /* Optional: Adjust space around the icon */
    }

    /* Cart Icon Image */
    #cart img {
      width: 100%; /* Make sure it fits within the container */
      height: 100%; /* Ensure it's perfectly sized */
      object-fit: contain; /* Prevents stretching/distortion */
    }

/* Cart Count Badge */
#cart-count {
  position: absolute;
  top: 15px;  /* Adjusted for better placement */
  right: 45px; /* Adjusted for better placement */
  background-color: red;
  color: white;
  border-radius: 50%;
  padding: 5px; /* Equal padding to ensure the circle shape */
  font-size: 14px;
  width: 20px;  /* Set a fixed width */
  height: 20px; /* Set a fixed height */
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 10; /* Ensures the count is always on top */
  font-weight: bold; /* Ensures better visibility */
  border: none; /* Optional: No border for cleaner look */
}



    .cart-message {
      display: none;
      margin-top: 10px;
      font-size: 14px;
    }
  </style>
  </style>
</head>
<body>
  <header>
    <nav>
      <div class="logo-row">
        <img src="../Images/res-logo.png" alt="Logo" width="25px" height="35px" />
        <h2 class="title">Feastly</h2>
      </div>
      <section>
        <ul>
          <li><a href="../index.php" class="home">Home</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="menu.php">Menu</a></li>
          <li><a href="profile.php">Account</a></li>
          <li><a href="service.php">Service</a></li>
          <li><a href="contact.php">Contact</a></li>
          <li><a href="booking.php"><button class="booking">Booking</button></a></li>
        </ul>
      </section>
      <a href="cart.php">
        <img src="../Images/cart.png" alt="Cart" width="50px" height="50px" id="cart">
        <span id="cart-count"><?= $cart_count ?></span> 
      </a>
    </nav>
  </header>

  <main>
    <div class="filter">
      <button class="filter-btn active" onclick="filterMenu('all')">All</button>
      <button class="filter-btn" onclick="filterMenu('Breakfast')">Breakfast</button>
      <button class="filter-btn" onclick="filterMenu('Lunch')">Lunch</button>
      <button class="filter-btn" onclick="filterMenu('Dinner')">Dinner</button>
      <button class="filter-btn" onclick="filterMenu('Drinks')">Appetizers</button>
    </div>

    <div class="menu-grid">
      <?php
      $conn = new mysqli('localhost', 'root', '2001ps,.', 'restaurant');

      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      $sql = "SELECT food_id, name, description, price, category, photo_url FROM food_item WHERE availability = 'Available'";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $price = number_format($row['price'], 2);
              $image_url = !empty($row['photo_url']) ? $row['photo_url'] : '../Images/placeholder.jpg';

              echo "
                <div class='menu-item' data-category='" . strtolower($row['category']) . "'>
                  <img src='{$image_url}' alt='Image of {$row['name']} - {$row['category']}' />
                  <h3>{$row['name']}</h3>
                  <p>{$row['description']}</p>
                  <p><strong>Rs. {$price}</strong></p>
                  <button class='add-to-cart' data-food-id='{$row['food_id']}'>Add to Cart</button>
                  <div class='cart-message'></div> 
                </div>
              ";
          }
      } else {
          echo "<p>No items available at the moment.</p>";
      }

      $conn->close();
      ?>
    </div>
  </main>

  <script>
    function filterMenu(category) {
      const items = document.querySelectorAll('.menu-item');
      const buttons = document.querySelectorAll('.filter-btn');
      buttons.forEach(button => {
        button.classList.remove('active');
        if (button.textContent.trim().toLowerCase() === category.toLowerCase()) {
          button.classList.add('active');
        }
      });
      items.forEach(item => {
        const itemCategory = item.dataset.category.trim().toLowerCase();
        item.style.display = (category === 'all' || itemCategory === category.toLowerCase()) ? 'block' : 'none';
      });
    }

    document.addEventListener('DOMContentLoaded', () => {
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', () => {
            const foodId = button.getAttribute('data-food-id');
            const cartMessage = button.nextElementSibling;

            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ food_id: foodId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update message text and style
                    cartMessage.textContent = 'Added item to cart';
                    cartMessage.style.display = 'block';
                    cartMessage.style.color = '#00ff08';
                } else {
                    cartMessage.textContent = data.message;
                    cartMessage.style.display = 'block';
                    cartMessage.style.color = 'red';
                }

                // Hide the message after 5 seconds
                setTimeout(() => {
                    cartMessage.style.display = 'none';
                }, 5000);

                updateCartCount();
            })
            .catch(error => {
                cartMessage.textContent = 'An error occurred: ' + error.message;
                cartMessage.style.display = 'block';
                cartMessage.style.color = 'red';

                // Hide the error message after 5 seconds
                setTimeout(() => {
                    cartMessage.style.display = 'none';
                }, 5000);
            });
        });
    });

    function updateCartCount() {
        fetch('update_cart_count.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('cart-count').textContent = data.cart_count;
            });
    }
});

// script.js
document.addEventListener('mousemove', (e) => {
    // Create a snowflake element
    const snowflake = document.createElement('div');
    snowflake.classList.add('snowflake');
    snowflake.textContent = '❄'; // Snowflake character
    document.body.appendChild(snowflake);

    // Set the snowflake's position
    snowflake.style.left = `${e.pageX}px`;
    snowflake.style.top = `${e.pageY}px`;

    // Remove the snowflake after the animation
    setTimeout(() => {
        snowflake.remove();
    }, 2000); // Match animation duration
});


  </script>
</body>
</html>
