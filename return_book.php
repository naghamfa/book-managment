<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $return_method = mysqli_real_escape_string($conn, $_POST['return_method']);
    $return_date = date('Y-m-d');

    $card_number = isset($_POST['card_number']) ? mysqli_real_escape_string($conn, $_POST['card_number']) : '';
    $expiry_date = isset($_POST['card_expiry']) ? mysqli_real_escape_string($conn, $_POST['card_expiry']) : '';
    $cvv = isset($_POST['card_cvc']) ? mysqli_real_escape_string($conn, $_POST['card_cvc']) : '';

    $delivery_charge = ($return_method === 'delivery') ? 30 : 0;

    $query = "INSERT INTO book_returns (user_id, product_name, return_date, name, number, email, status, return_method, card_number, expiry_date, cvv, delivery_charge) 
              VALUES ('$user_id', '$product_name', '$return_date', '$name', '$number', '$email', 'Pending', '$return_method', '$card_number', '$expiry_date', '$cvv', '$delivery_charge')";

    if (mysqli_query($conn, $query)) {
        if ($return_method === 'delivery') {
            $message = 'Return request placed successfully with delivery option. Please make sure your payment is processed.';
        } else {
            $message = 'Return request placed successfully with pickup option!';
        }
    } else {
        $message = 'Failed to place return request. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Book</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
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

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"], input[type="email"], input[type="password"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="submit"] {
            background: linear-gradient(to bottom, #1f4037, #99f2c8);
            color: #ffffff;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #ffffff;
            color: #000000;
        }

        .message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
            color: #fff;
            text-align: center;
        }

        .message.success {
            background-color: #4CAF50;
        }

        .message.error {
            background-color: #f44336; 
        }

        .credit-card-info {
            display: none;
            margin-top: 15px;
        }

        .credit-card-info input {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="heading">
    <h3>Return Book</h3>
    <p><a href="home.php">Home</a> / Return Book</p>
</div>

<div class="container">
    <form action="" method="post">
        <div class="form-group">
            <label for="product_name">Book Name:</label>
            <input type="text" id="product_name" name="product_name" required>
        </div>
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
            <label for="return_method">Return Method:</label>
            <select id="return_method" name="return_method" required onchange="toggleCreditCardFields()">
                <option value="pickup">Pickup</option>
                <option value="delivery">Delivery (+30 ₪)</option>
            </select>
        </div>
        <div id="credit-card-info" class="credit-card-info">
            <div class="form-group">
                <label for="card_number">Card Number:</label>
                <input type="text" id="card_number" name="card_number">
            </div>
            <div class="form-group">
                <label for="card_expiry">Expiry Date (MM/YY):</label>
                <input type="text" id="card_expiry" name="card_expiry">
            </div>
            <div class="form-group">
                <label for="card_cvc">CVC:</label>
                <input type="text" id="card_cvc" name="card_cvc">
            </div>
        </div>
        <input type="hidden" name="order_total" value="<?php echo htmlspecialchars($_GET['order_total']); ?>">
        <input type="submit" value="Submit Return Request">
    </form>
    <?php if (!empty($message)): ?>
        <div class="message <?php echo strpos($message, 'Failed') === false ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<script>
function toggleCreditCardFields() {
    var returnMethod = document.getElementById('return_method').value;
    var creditCardInfo = document.getElementById('credit-card-info');

    if (returnMethod === 'delivery') {
        creditCardInfo.style.display = 'block';
    } else {
        creditCardInfo.style.display = 'none';
    }
}
</script>

</body>
</html>
