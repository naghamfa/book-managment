<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['update_cart'])){
   $cart_id = $_POST['cart_id'];
   $cart_quantity = $_POST['cart_quantity'];
   mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
   $message[] = 'cart quantity updated!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
   header('location:cart.php');
}

if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:cart.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>cart</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <link rel="stylesheet" href="style.css">
   <style>
      body {
         font-family: Arial, sans-serif;
         background-color: #f2f2f2;
         margin: 0;
         padding: 0;
      }

      .btn, .option-btn, .delete-btn, .disabled {
         background-color: #319064;
         color: #fff;
         border: none;
         padding: 10px 20px;
         text-align: center;
         text-decoration: none;
         display: inline-block;
         font-size: 16px;
         margin-top: 10px;
         cursor: pointer;
         transition: background-color 0.3s ease;
         border-radius: 5px;
         margin: 5px;
      }

      .btn:hover, .option-btn:hover, .delete-btn:hover {
         background-color: #1f4037;
      }

      .disabled {
         background-color: #ccc;
         cursor: not-allowed;
      }

      .heading {
        background: linear-gradient(to bottom, #1f4037, #99f2c8);
         color: #fff;
         padding: 20px;
         text-align: center;
         margin-bottom: 30px;
      }

      .heading h3 {
         margin: 0;
         font-size: 28px;
         font-weight: 700;
      }

      .heading p {
         margin: 5px 0;
         font-size: 16px;
         color: #ccc;
      }

      .shopping-cart {
         max-width: 800px;
         margin: auto;
         background-color: #fff;
         padding: 20px;
         border-radius: 5px;
         box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      }

      .shopping-cart h1 {
         font-size: 24px;
         margin-bottom: 20px;
         text-align: center;
      }

      .box-container {
         display: flex;
         flex-wrap: wrap;
         gap: 20px;
      }

      .box {
         position: relative;
         max-width: 300px;
         padding: 20px;
         background-color: #fff;
         border-radius: 5px;
         box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      }

      .box img {
         width: 100%;
         border-radius: 5px;
         margin-bottom: 10px;
      }

      .box .name {
         font-size: 18px;
         margin-bottom: 10px;
         font-weight: bold;
      }

      .box .price {
         color: #319064;
         font-size: 16px;
         margin-bottom: 10px;
      }

      .box .option-btn {
        background: linear-gradient(to bottom, #1f4037, #99f2c8);
         padding: 20px;         color: #fff;
         border: none;
         padding: 8px 12px;
         text-align: center;
         text-decoration: none;
         display: inline-block;
         font-size: 14px;
         margin-top: 5px;
         cursor: pointer;
         border-radius: 3px;
         transition: background-color 0.3s ease;
      }

      .box .option-btn:hover {
         background-color: #1f4037;
      }

      .box .sub-total {
         font-size: 14px;
         margin-top: 10px;
      }

      .cart-total {
         margin-top: 20px;
         text-align: right;
      }

      .cart-total p {
         font-size: 18px;
         margin-bottom: 10px;
      }

      .cart-total .flex {
         display: flex;
         justify-content: flex-end;
         gap: 10px;
         margin-top: 10px;
      }

      .cart-total .btn {
        background: linear-gradient(to bottom, #1f4037, #99f2c8);
         padding: 20px;         color: #fff;
         border: none;
         padding: 10px 20px;
         text-align: center;
         text-decoration: none;
         display: inline-block;
         font-size: 16px;
         cursor: pointer;
         border-radius: 5px;
         transition: background-color 0.3s ease;
      }

      .cart-total .btn:hover {
         background-color: #1f4037;
      }

      .empty {
         text-align: center;
         font-size: 18px;
         margin-top: 20px;
      }
   </style>
</head>
<body>
   
<?php include 'navbar.php'; ?>

<div class="heading">
   <h3>shopping cart</h3>
   <p> <a href="home.php">home</a> / cart </p>
</div>

<section class="shopping-cart">

   <h1 class="title">products added</h1>

   <div class="box-container">
      <?php
         $grand_total = 0;
         $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
         if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){   
      ?>
      <div class="box">
         <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>
         <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_cart['name']; ?></div>
         <div class="price">₪<?php echo $fetch_cart['price']; ?></div>
         <form action="" method="post">
            <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
            <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
            <input type="submit" name="update_cart" value="update" class="option-btn">
         </form>
         <div class="sub-total"> sub total : <span>₪<?php echo $sub_total = ($fetch_cart['quantity'] * $fetch_cart['price']); ?></span> </div>
      </div>
      <?php
      $grand_total += $sub_total;
         }
      }else{
         echo '<p class="empty">your cart is empty</p>';
      }
      ?>
   </div>

   <div style="margin-top: 2rem; text-align:center;">
      <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('delete all from cart?');">delete all</a>
   </div>

   <div class="cart-total">
      <p> total : <span>₪<?php echo $grand_total; ?></span></p>
      <div class="flex">
         <a href="shop.php" class="option-btn">continue shopping</a>
         <a href="checkout.php" class="btn <?php echo ($grand_total > 1)?'':'disabled'; ?>">proceed to checkout</a>
      </div>
   </div>

</section>








<?php include 'footer.php'; ?>

<script src="script.js"></script>

</body>
</html>