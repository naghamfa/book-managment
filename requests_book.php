<?php
include 'config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$product_name = isset($_GET['product_name']) ? mysqli_real_escape_string($conn, $_GET['product_name']) : '';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $delivery_method = mysqli_real_escape_string($conn, $_POST['delivery_method']);
    $request_date = date('Y-m-d');
    $status = 'pending'; 

    $card_number = !empty($_POST['card_number']) ? mysqli_real_escape_string($conn, $_POST['card_number']) : '';
    $expiry_date = !empty($_POST['expiry_date']) ? mysqli_real_escape_string($conn, $_POST['expiry_date']) : '';
    $cvv = !empty($_POST['cvv']) ? mysqli_real_escape_string($conn, $_POST['cvv']) : '';

    $delivery_charge = 0;

    if ($delivery_method === 'delivery') {
        $delivery_charge = 30; 
    }

    $query = "INSERT INTO book_requests (user_id, product_name, request_date, name, number, email, status, delivery_method, card_number, expiry_date, cvv, delivery_charge) 
              VALUES ('$user_id', '$product_name', '$request_date', '$name', '$number', '$email', '$status', '$delivery_method', '$card_number', '$expiry_date', '$cvv', '$delivery_charge')";
    
    if (mysqli_query($conn, $query)) {
        $message = 'Book request submitted successfully! Please return the book within one month from the date of receipt. For more details about the borrowing policy, please visit our <a href="about.php">About Us</a> page.';
    } else {
        $message = 'Failed to submit book request. Please try again.';
    }
}

$product = mysqli_query($conn, "SELECT * FROM products WHERE name = '$product_name'") or die('query failed');
$product_data = mysqli_fetch_assoc($product);

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Request Book</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="style.css">
    <style>
         body {
         background: #f0f2f5;
         color: #333;
         font-family: Arial, sans-serif;
         margin: 0;
         padding: 0;
      }

        .heading {
            background: linear-gradient(to bottom, #1f4037, #99f2c8);
            color: #fff;
            padding: 15px 0;
            text-align: center;
            margin-bottom: 20px;
        }

        .heading h3 {
            font-size: 28px;
            margin: 0;
        }

        .heading p {
            font-size: 16px;
            margin: 5px 0 0;
        }

        .heading p a:hover {
            text-decoration: underline;
        }

        .request-container {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .request-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

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

        #notification {
    background-color: #dff0d8;
    color: #3c763d;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
    display: none;
    font-size: 16px; 
    font-weight: bold; 
}

        
        .payment-details {
            display: none;
        }

        .request-container img {
            width: 30%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="heading">
    <h3>Request a Book</h3>
    <p><a href="home.php">Home</a> / Request a Book</p>
</div>

<div class="request-container">
    <?php if ($message): ?>
        <div id="notification"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if ($product_data): ?>
        <img src="uploaded_img/<?php echo $product_data['image']; ?>" alt="<?php echo $product_data['name']; ?>">
        <h1><?php echo $product_data['name']; ?></h1>
        <p>Price: ₪<?php echo $product_data['price']; ?></p>
        <p><?php echo $product_data['description']; ?></p>
        <p>Author: <?php echo $product_data['author']; ?></p>
        <p>Book Type: <?php echo $product_data['book_type']; ?></p>

        <form action="" method="post">
            <input type="hidden" name="product_name" value="<?php echo $product_data['name']; ?>">
            <div class="form-group">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="number">Contact Number:</label>
                <input type="text" id="number" name="number" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="delivery_method">Delivery Method:</label>
                <select id="delivery_method" name="delivery_method" required>
                    <option value="pickup">Pickup</option>
                    <option value="delivery">Delivery (₪30)</option>
                </select>
            </div>
            
            <div class="payment-details" id="payment-details">
                <div class="form-group">
                    <label for="card_number">Card Number:</label>
                    <input type="text" id="card_number" name="card_number" placeholder="Enter your card number">
                </div>
                <div class="form-group">
                    <label for="expiry_date">Expiry Date:</label>
                    <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY">
                </div>
                <div class="form-group">
                    <label for="cvv">CVV:</label>
                    <input type="text" id="cvv" name="cvv" placeholder="Enter CVV">
                </div>
            </div>
            
            <input type="submit" value="Submit Request" class="btn">
        </form>
    <?php else: ?>
        <p>You have not selected a book to request!</p>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deliveryMethod = document.getElementById('delivery_method');
        const paymentDetails = document.getElementById('payment-details');
        const notification = document.getElementById('notification');
        
        deliveryMethod.addEventListener('change', function() {
            if (this.value === 'delivery') {
                paymentDetails.style.display = 'block';
            } else {
                paymentDetails.style.display = 'none';
            }
        });

        if (notification.textContent.trim() !== '') {
            notification.style.display = 'block';
        }
    });
</script>

<?php include 'footer.php'; ?>
</body>
</html>
