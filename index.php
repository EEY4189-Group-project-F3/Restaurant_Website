<?php
session_start(); // Start the session to check login status
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fast Foods Restuarant</title>
    <link rel="stylesheet" href="./styles.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
      rel="stylesheet"
    />
    <link rel="shortcut icon" href="icon.jpg" type="image/x-icon" />
  </head>
  <body>

  <nav>
  <div class="logo-row">
    <img
      src="./Images/res-logo.png"
      alt=""
      width="25px"
      height="35px"
    />
    <h2 class="title">Feastly</h2>
  </div>

  <section>
    <ul>
      <li><a href="../index.php" class="home">Home</a></li>
      <li><a href="./phpfiles/about.php">About</a></li>
      <li><a href="./phpfiles/menu.php">Menu</a></li>
      
      <?php if (isset($_SESSION['user_id'])): ?>
        <!-- If user is logged in, link to profile or account page -->
        <li><a href="./phpfiles/profile.php">Account</a></li>
      <?php else: ?>
        <!-- If user is not logged in, link to sign-in page -->
        <li><a href="./phpfiles/signin.php">Account</a></li>
      <?php endif; ?>

      <li><a href="./phpfiles/service.php">Service</a></li>
      <li><a href="./phpfiles/contact.php">Contact</a></li>
      <li>
        <a href="./phpfiles/booking.php"><button class="booking">Booking</button></a>
      </li>
    
    </ul>
  </section>
  <a href="./phpfiles/cart.php">
  <img src="./Images/cart.png" alt="" width="50px" height="50px"  id="cart">
  </a>
  
</nav>

    <div class="main">
      <h1 class="main-title">Fast Food Restuarant</h1>
      <p>
        Indulge in a Variety of Quick and Delicious<br />
        Meals, Perfectly Prepared for Your Busy<br />Lifestyle
      </p>
      <button onclick="window.location.href='./phpfiles/booking.php';" >Order Now</button>
    </div>

    <div class="story">
      <h3>Our Story</h3>
      <h1>We Are Providing Great & Delicious Food About Twenty Years</h1>
      <p>
        For twenty years, we have been dedicated to serving delicious,
        high-quality fast food. From our burgers and savory rice and curry to
        our delightful egg rice, creamy pasta, flavorful kottu, and crispy
        chicken wings, every dish is crafted with care and passion. <br />
        <br />Our commitment to excellent cuisine and warm hospitality has made
        us a beloved spot for friends and families. We thank our loyal customers
        for their support and look forward to many more years of serving you.
        Thank you for being part of our journey.
      </p>
    </div>

    <div class="cards">
      <h1>We Offer Top Notch</h1>
      <p>
        Indulge in Exquisite Dishes Crafted with Fresh Ingredients and
        Passionate Expertise
      </p>
      <div class="row">
        <div class="card">
          <img src="./Images/food.jpg" alt="" />
          <h3>Appetizers</h3>
          
        </div>
        <div class="card">
          <img src="./Images/breakfast.jpg" alt="" />
          <h3>Breakfast</h3>
          
        </div>
        <div class="card">
          <img src="./Images/drinks.jpg" alt="" />
          <h3>Drinks</h3>
        
        </div>
      </div>
    </div>

   <!--  <div class="menu">
      <h2 class="menu-title">Delicious Menu</h2>
      <div class="column">
        <div class="item">
          <img src="item1.jpg" alt="" class="item-img" />
          <div class="description">
            <div class="title-row">
              <h4>Juicy Classic Burger</h4>

              <p>50$</p>
            </div>

            <p>
              A perfectly grilled patty with fresh lettuce, ripe tomatoes,
              <br />
              and tangy sauce on a toasted bun.
            </p>
          </div>
        </div>

        <div class="item">
          <img src="item3.jpg" class="item-img" alt="" />
          <div class="description">
            <div class="title-row">
              <h4>Heavenly Pasta Delight</h4>
              <p>50$</p>
            </div>
            <p>
              Al dente noodles in a creamy or tomato sauce, topped <br />
              with fresh herbs and Parmesan.
            </p>
          </div>
        </div>

        <div class="item">
          <img src="item1.jpg" alt="" class="item-img" />
          <div class="description">
            <div class="title-row">
              <h4>Juicy Classic Burger</h4>
              <p>50$</p>
            </div>

            <p>
              A perfectly grilled patty with fresh lettuce, ripe tomatoes,
              <br />
              and tangy sauce on a toasted bun.
            </p>
          </div>
        </div>

        <div class="item">
          <img src="item3.jpg" class="item-img" alt="" />
          <div class="description">
            <div class="title-row">
              <h4>Heavenly Pasta Delight</h4>
              <p>50$</p>
            </div>
            <p>
              Al dente noodles in a creamy or tomato sauce, topped <br />
              with fresh herbs and Parmesan.
            </p>
          </div>
        </div>

        <div class="item">
          <img src="item1.jpg" alt="" class="item-img" />
          <div class="description">
            <div class="title-row">
              <h4>Juicy Classic Burger</h4>
              <p>50$</p>
            </div>

            <p>
              A perfectly grilled patty with fresh lettuce, ripe tomatoes,
              <br />
              and tangy sauce on a toasted bun.
            </p>
          </div>
        </div>

        <div class="item">
          <img src="item3.jpg" class="item-img" alt="" />
          <div class="description">
            <div class="title-row">
              <h4>Heavenly Pasta Delight</h4>
              <p>50$</p>
            </div>
            <p>
              Al dente noodles in a creamy or tomato sauce, topped <br />
              with fresh herbs and Parmesan.
            </p>
          </div>
        </div>
      </div>
    </div> -->

    <div class="menu-container">
      <h1 class="menu-title">Delicious Menu</h1>
      <div class="menu-grid">
          <div class="menu-item">
              <img src="./Images/string_hopper_biriyani.jpg" alt="Juicy Classic Burger">
              <div class="menu-text">
                  <h2>String Hopper Biriyani</h2>
                  <p>String hoppers layered with spiced meat curry and boiled eggs.</p>
              </div>
              <span class="price">Rs.550</span>
          </div>

          <div class="menu-item">
              <img src="./Images/faluda.jpg" alt="Crispy Chicken Wings">
              <div class="menu-text">
                  <h2>Faluda</h2>
                  <p>A refreshing mix of rose syrup, jelly, and basil seeds with milk</p>
              </div>
              <span class="price">Rs.250</span>
          </div>

          <div class="menu-item">
              <img src="./Images/coconut_sambol.jpg" alt="Authentic Rice and Curry">
              <div class="menu-text">
                  <h2>Coconut Sambol Platter</h2>
                  <p>Coconut sambol with vegetarian curries and steamed rice</p>
              </div>
              <span class="price">Rs.300</span>
          </div>

          <div class="menu-item">
              <img src="./Images/egg_curry.png" alt="Savory Egg Fried Rice">
              <div class="menu-text">
                  <h2>Spicy Egg Curry</h2>
                  <p>Egg curry served with freshly baked bread</p>
              </div>
              <span class="price">Rs.350</span>
          </div>

          <div class="menu-item">
              <img src="./Images/milk_rice.jpg" alt="Heavenly Pasta Delight">
              <div class="menu-text">
                  <h2>Milk Rice</h2>
                  <p>Served with lunu miris or fish curry</p>
              </div>
              <span class="price">Rs.450</span>
          </div>

          <div class="menu-item">
              <img src="./images/kottu_roti.jpeg" alt="Traditional Kottu Roti">
              <div class="menu-text">
                  <h2>Traditional Kottu Roti</h2>
                  <p>A spicy stir-fry of chopped flatbread, vegetables, eggs, and meat, straight from Sri Lanka.</p>
              </div>
              <span class="price">Rs.500</span>
          </div>
      </div>
  </div>

    <h3 class="thanks">
      Thank you for choosing our restaurant! We are excited to have you dine
      with us and want to make the reservation process as easy as possible.
      Please complete the form below to book your table. We look forward to
      providing you with a memorable dining experience.
      <br />
      <br />
      See you soon!
    </h3>

    <div class="owner">
      <img src="./images/owner.png" alt="" width="150px" />
    </div>

  

    <div class="bottom-section">
      <h1>Our Strength</h1>
      <div class="bottom-cards">
        <div class="bottom-card">
          <img src="./images/logo.jpg" alt="" />
          <h3>Online Reservations</h3>
          <p>
            Planning your visit has never been easier. With our simple online
            reservation system, you can effortlessly book your table and look
            forward to a hassle-free dining experience.
          </p>
        </div>

        <div class="bottom-card">
          <img src="./images/safe.jpg" alt="" />
          <h3>Hygiene and Safety</h3>
          <p>
            We adhere to the highest standards of cleanliness and food safety,
            ensuring a safe and healthy environment for our guests.
          </p>
        </div>

        <div class="bottom-card">
          <img src="./images/foodmenu.jpg" alt="" />
          <h3>Delicious and Diverse Menu</h3>
          <p>
            Savor our juicy burgers, rice and curry, egg fried rice, pasta,
            kottu roti, and chicken wings. Fresh, high-quality ingredients in
            every dish.
          </p>
        </div>

        <div class="bottom-card">
          <img src="./images/customer_service.jpg" alt="" />
          <h3>Exceptional Customer Service</h3>
          <p>
            Your satisfaction is our top priority. Our team is attentive,
            professional, and ready to cater to your needs, ensuring that every
            visit is a pleasant and memorable one.
          </p>
        </div>
      </div>
    </div>

    <div class="outro">
      <h1>Feastly</h1>
      <p>Avenue 6th floor Nawala,Colombo</p>
    </div>
  </body>

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
        <a href="#">Youtube</a>
      </div>
    </div>
  </div>
</html>
