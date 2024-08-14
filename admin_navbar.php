<?php
include 'config.php';
session_start();

if(isset($_SESSION['admin_name'])) {
    $admin_name = $_SESSION['admin_name'];
    $admin_email = $_SESSION['admin_email'];
} else {
    header('location:login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Panel</title>

   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600&display=swap">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <style>
      body {
         font-family: 'Rubik', sans-serif;
         background-color: #f5f5f5;
         color: #333;
         line-height: 1.6;
         overflow-x: hidden;
         margin: 0;
         padding: 0;
      }

      .header {
         padding: 1rem 0;
      }

      .header .flex {
         display: flex;
         justify-content: space-between;
         align-items: center;
         padding: 0 2rem;
      }

      .header .logo {
         color: #000;
         font-size: 1.8rem;
         text-decoration: none;
         font-weight: bold;
      }

      .header .logo span {
         font-weight: normal;
         color:  linear-gradient(to bottom, #1f4037, #99f2c8); 
      }

      .header .navbar {
         display: flex;
         gap: 2rem;
         margin-left: auto;
      }

      .header .navbar a {
         color: #000; 
         text-decoration: none;
         font-size: 1.2rem;
         transition: color 0.3s ease, background 0.3s ease;
         padding: 0.5rem 1rem;
         border-radius: 0.3rem;
      }

      .header .navbar a:hover {
         color: #1f4037; 
         background: linear-gradient(to bottom, #1f4037, #99f2c8);
         -webkit-background-clip: text;
         -webkit-text-fill-color: transparent;
      }

      .header .icons {
         display: flex;
         gap: 1rem;
         align-items: center;
      }

      .header .icons .fas {
         color: #000;
         font-size: 1.5rem;
         cursor: pointer;
         transition: color 0.3s ease;
      }

      .header .icons .fas:hover {
         color: #1f4037;
      }

      .account-box {
         position: absolute;
         top: 5rem;
         right: 2rem;
         background-color: #fff;
         width: 15rem;
         padding: 1rem;
         border-radius: 0.5rem;
         box-shadow: 0 .5rem 1rem rgba(0, 0, 0, 0.1);
         display: none;
         z-index: 1;
      }

      .account-box p {
         margin-bottom: 0.5rem;
      }

      .account-box span {
         font-weight: bold;
         color: #000; 
      }

      .account-box a {
         color: #000;
         text-decoration: none;
         font-weight: bold;
         transition: color 0.3s ease;
      }

      .account-box a:hover {
         color: #1f4037; 
      }

      .account-box .delete-btn {
         color: #fff;
         background-color: #c0392b;
         padding: 0.5rem 1rem;
         border: none;
         border-radius: 0.3rem;
         cursor: pointer;
         margin-top: 1rem;
         display: block;
         text-align: center;
         transition: background-color 0.3s ease, color 0.3s ease;
      }

      .account-box .delete-btn:hover {
         background-color: #e74c3c;
         color: #fff;
      }

      .account-box div {
         font-size: 0.8rem;
         margin-top: 1rem;
         text-align: center;
      }

      .account-box div a {
         color: #000;
         text-decoration: none;
         font-weight: bold;
         transition: color 0.3s ease;
      }

      .account-box div a:hover {
         color: #1f4037; 
      }

      #menu-btn, #user-btn {
         position: relative;
         cursor: pointer;
      }

      #user-btn:focus + .account-box,
      .account-box:hover {
         display: block;
      }

      .account-box.visible {
         display: block;
      }
   </style>
</head>
<body>

<header class="header">
   <div class="flex">
   <a href="admin_page.php" class="logo">Admin<span style="color:linear-gradient(to bottom, #1f4037, #99f2c8)";>Panel</span></a>
      <nav class="navbar">
         <a href="admin_page.php">Home</a>
         <a href="admin_products.php">Products</a>
         <a href="admin_orders.php">Orders</a>
         <a href="admin_users.php">Users</a>
         <a href="admin_contacts.php">Messages</a>
         <a href="admin_requests.php">Requests</a>
         <a href="reviews_admin.php">Reviews</a>
         <a href="admin_book_returns.php">Returns</a>

      </nav>
      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>
      <div class="account-box">
         <p>Username: <span><?php echo $admin_name; ?></span></p>
         <p>Email: <span><?php echo $admin_email; ?></span></p>
         <a href="login.php" class="delete-btn">Logout</a>
         <div>New <a href="login.php">Login</a> | <a href="register.php">Register</a></div>
      </div>
   </div>
</header>


<script>
   document.getElementById('user-btn').addEventListener('click', function() {
      document.querySelector('.account-box').classList.toggle('visible');
   });
</script>

</body>
</html>
