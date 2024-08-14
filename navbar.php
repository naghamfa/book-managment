<?php
session_start(); 

if(isset($_SESSION['message'])){
   foreach($_SESSION['message'] as $msg){
      echo '
      <div class="message">
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
   unset($_SESSION['message']); 
}
?>


   <header class="header">
   <div class="header-1">
      <div class="flex">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fab fa-linkedin"></a>
         </div>
         <p><a href="login.php" style="color: #319064;">login</a> | <a href="register.php" style="color: #319064;">register</a> </p>
      </div>
   </div>

   <div class="header-2">
      <div class="flex">
         <a href="home.php" class="logo" style="color: #319064;">Book Touch!♡</a>

         <nav class="navbar">
            <a href="home.php" style="color: #319064;">home</a>
            <a href="about.php" style="color: #319064;">about</a>
            <a href="shop.php" style="color: #319064;">shop</a>
            <a href="contact.php" style="color: #319064;">contact</a>
            <a href="orders.php" style="color: #319064;">orders</a>
            <a href="requests_book.php" style="color: #319064;">books requests</a>
            <a href="review_page.php" style="color: #319064;">reviews</a>
            <a href="return_book.php" style="color: #319064;">return book</a>
            <a href="requests_user.php" style="color: #319064;">my requests</a>

            <div class="dropdown">
               <button class="dropbtn" style="color: #319064;">🛠️</button>
               <div class="dropdown-content">
                  <a href="#" onclick="changeFontSize('12px')">Small Font</a>
                  <a href="#" onclick="changeFontSize('16px')">Medium Font</a>
                  <a href="#" onclick="changeFontSize('20px')">Large Font</a>
                  <a href="#" onclick="toggleContrast()">Toggle Contrast</a>
               </div>
            </div>
         </nav>

         <div class="icons">
            <div id="menu-btn" class="fas fa-bars" style="color: #319064;"></div>
            <a href="search_page.php" class="fas fa-search" style="color: #319064;"></a>
            <a href="login.php" class="logout-btn" style="color: #319064; text-decoration: none; display: flex; align-items: center; margin-left: 10px;">
               <i class="fas fa-sign-out-alt" style="margin-right: 5px;"></i>
               <span style="color: #319064;">logout</span>
            </a>
            <?php
               $conn = mysqli_connect("localhost", "root", "", "shop_db");
               $user_id = $_SESSION['user_id']; 
               $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
               $cart_rows_number = mysqli_num_rows($select_cart_number); 
            ?>
            <a href="cart.php" style="color: #319064; margin-left: 10px;"> <i class="fas fa-shopping-cart"></i> <span style="color: #319064;">(<?php echo $cart_rows_number; ?>)</span> </a>
         </div>

         <div class="user-box">
            <p style="color: #319064;">username : <span><?php echo $_SESSION['user_name']; ?></span></p>
            <p style="color: #319064;">email : <span><?php echo $_SESSION['user_email']; ?></span></p>
         </div>
      </div>
   </div>
</header>

<script>
   function changeFontSize(size) {
      document.body.style.fontSize = size;
      document.cookie = "fontSize=" + size + "; path=/";
   }

   function toggleContrast() {
      document.body.classList.toggle('high-contrast');
      var contrast = document.body.classList.contains('high-contrast') ? 'high' : 'normal';
      document.cookie = "contrast=" + contrast + "; path=/";
   }

   window.onload = function() {
      var cookies = document.cookie.split(';');
      cookies.forEach(function(cookie) {
         var parts = cookie.split('=');
         var name = parts[0].trim();
         var value = parts[1].trim();

         if (name == 'fontSize') {
            document.body.style.fontSize = value;
         }

         if (name == 'contrast' && value == 'high') {
            document.body.classList.add('high-contrast');
         }
      });
   }
</script>

<style>
.header,
.header a,
.header p,
.header span,
.header .user-box p {
   color: white; 
   transition: color 0.3s ease; 
}

.header a:hover {
   color: #ffffff; 
   background-color: black; 
}

.header .icons a:hover {
   color: #319064;
   background-color: black; 
}

.user-box .logout-btn:hover {
   color: #ffffff;
   background-color: black;
}

.message {
   background: linear-gradient(to bottom, #1f4037, #99f2c8); 
   color: #ffffff;
   padding: 10px 20px;
   border-radius: 5px;
   margin-bottom: 10px;
   position: relative;
   transition: background-color 0.3s ease;
}

.message span {
   color: #ffffff;
}

.message:hover {
   background-color: #319064; 
}

.header .user-box .logout-btn {
   color: #319064; 
   text-decoration: none; 
   display: flex; 
   align-items: center;
}

.high-contrast {
   background-color: #000000;
   color: #ffffff;
}

.high-contrast a {
   color: #ffff00; 
}

.high-contrast .navbar a:hover,
.high-contrast .icons a:hover,
.high-contrast .user-box .logout-btn:hover {
   background-color: #444444;
}

.dropdown {
   position: relative;
   display: inline-block;
}

.dropdown-content {
   display: none;
   position: absolute;
   background-color: #f9f9f9;
   min-width: 160px;
   box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
   z-index: 1;
}

.dropdown-content a {
   color: black;
   padding: 12px 16px;
   text-decoration: none;
   display: block;
}

.dropdown-content a:hover {
   background-color: #f1f1f1;
}

.dropdown:hover .dropdown-content {
   display: block;
}

.dropdown:hover .dropbtn {
   background-color: #3e8e41;
}
</style>
