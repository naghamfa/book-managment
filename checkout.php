<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location: login.php');
    exit();
}

if (isset($_POST['order_btn'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn, 'flat no. ' . $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code']);
    $delivery_method = mysqli_real_escape_string($conn, $_POST['delivery_method'] ?? 'pickup'); // Default to 'pickup' if not set
    $placed_on = date('d-M-Y');

    $cart_total = 0;
    $cart_products = [];

    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    if (mysqli_num_rows($cart_query) > 0) {
        while ($cart_item = mysqli_fetch_assoc($cart_query)) {
            $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ')';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }

    if ($delivery_method === 'delivery') {
        $cart_total += 30;
    }

    $total_products = implode(', ', $cart_products);

    $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id' AND name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

    if ($cart_total == 0) {
        $message[] = 'Your cart is empty.';
    } else {
        if (mysqli_num_rows($order_query) > 0) {
            $message[] = 'Order already placed!';
        } else {
            $card_number = !empty($_POST['card_number']) ? mysqli_real_escape_string($conn, $_POST['card_number']) : '';
            $expiry_date = !empty($_POST['expiry_date']) ? mysqli_real_escape_string($conn, $_POST['expiry_date']) : '';
            $cvv = !empty($_POST['cvv']) ? mysqli_real_escape_string($conn, $_POST['cvv']) : '';

            mysqli_query($conn, "INSERT INTO `orders` (user_id, name, number, email, method, address, total_products, total_price, placed_on, payment_status, delivery_method, card_number, expiry_date, cvv) VALUES ('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on', 'pending', '$delivery_method', '$card_number', '$expiry_date', '$cvv')") or die('query failed');
            $message[] = 'Order placed successfully!';
            mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="style.css">
   <style>
       .btn {
            background: linear-gradient(to bottom, #1f4037, #99f2c8);
            color: #fff;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #ffffff;
            color: #000000;
        }
      .card-details {
         display: none; 
      }
   </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="heading">
   <h3>Checkout</h3>
   <p><a href="home.php">Home</a> / Checkout</p>
</div>

<section class="display-order">

   <?php
   $grand_total = 0;
   $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   if (mysqli_num_rows($select_cart) > 0) {
       while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
           $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
           $grand_total += $total_price;
   ?>
   <p><?php echo htmlspecialchars($fetch_cart['name']); ?> <span>(<?php echo '₪' . htmlspecialchars($fetch_cart['price']) . '/-' . ' x ' . htmlspecialchars($fetch_cart['quantity']); ?>)</span></p>
   <?php
       }
   } else {
       echo '<p class="empty">Your cart is empty.</p>';
   }
   ?>
   <div class="grand-total"> Total: <span id="grand-total">₪<?php echo htmlspecialchars($grand_total); ?></span> </div>

</section>

<section class="checkout">

   <form action="" method="post" id="order-form">
      <h3>Place Your Order</h3>
      <div class="flex">
         <div class="inputBox">
            <span>Your Name:</span>
            <input type="text" name="name" required placeholder="Enter your name">
         </div>
         <div class="inputBox">
            <span>Your Number:</span>
            <input type="number" name="number" required placeholder="Enter your number">
         </div>
         <div class="inputBox">
            <span>Your Email:</span>
            <input type="email" name="email" required placeholder="Enter your email">
         </div>
         <div class="inputBox">
            <span>Payment Method:</span>
            <select name="method" id="payment-method">
               <option value="cash on delivery">Cash on Delivery</option>
               <option value="credit card">Credit Card</option>
               <option value="paypal">PayPal</option>
               <option value="paytm">PayTM</option>
            </select>
         </div>
         <div id="card-details" class="card-details">
            <div class="inputBox">
               <span>Card Number:</span>
               <input type="text" name="card_number" placeholder="Card Number">
            </div>
            <div class="inputBox">
               <span>Expiry Date:</span>
               <input type="text" name="expiry_date" placeholder="Expiry Date (MM/YY)">
            </div>
            <div class="inputBox">
               <span>CVV:</span>
               <input type="text" name="cvv" placeholder="CVV">
            </div>
         </div>
         <div class="inputBox">
            <span>Delivery Method:</span>
            <select name="delivery_method" id="delivery-method">
               <option value="pickup">Pickup</option>
               <option value="delivery">Delivery+30</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Address:</span>
            <input type="text" name="flat" placeholder="Flat No." required>
            <input type="text" name="street" placeholder="Street" required>
            <input type="text" name="city" placeholder="City" required>
            <input type="text" name="country" placeholder="Country" required>
            <input type="text" name="pin_code" placeholder="PIN Code" required>
         </div>
      </div>
      <input type="submit" name="order_btn" value="Place Order" class="btn">
   </form>

</section>

<script>
   document.addEventListener('DOMContentLoaded', function() {
      const paymentMethod = document.getElementById('payment-method');
      const cardDetails = document.getElementById('card-details');
      const deliveryMethod = document.getElementById('delivery-method');
      const grandTotalElement = document.getElementById('grand-total');
      const baseTotal = parseFloat(grandTotalElement.textContent.replace('₪', '').replace(',', ''));

      paymentMethod.addEventListener('change', function() {
         cardDetails.style.display = this.value === 'credit card' ? 'block' : 'none';
      });

      deliveryMethod.addEventListener('change', function() {
         let total = baseTotal;
         if (this.value === 'delivery') {
            total += 30;
         }
         grandTotalElement.textContent = '₪' + total.toFixed(2);
      });
   });
</script>

</body>
</html>
