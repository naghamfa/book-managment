<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($check_cart_numbers) > 0) {
        $message[] = 'already added to cart!';
    } else {
        mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
        $message[] = 'product added to cart!';
    }
}

if (isset($_POST['request_book'])) {
    $product_name = $_POST['product_name'];
    $product_image = $_POST['product_image'];
    $request_date = date('Y-m-d');
    $name = $_POST['request_name'];
    $number = $_POST['request_number'];
    $email = $_POST['request_email'];

    mysqli_query($conn, "INSERT INTO `book_requests`(user_id, product_name, request_date, name, number, email) VALUES('$user_id', '$product_name', '$request_date', '$name', '$number', '$email')") or die('query failed');
    $message[] = 'Request sent for book!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Page</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="style.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .heading {
            text-align: center;
            margin: 20px 0;
            font-size: 24px;
            color: #333;
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

        .heading a {
            color: #fff;
            text-decoration: none;
        }

        .btn {
            background: linear-gradient(to bottom, #1f4037, #99f2c8);
            color: #fff;
            border: none;
            border-radius: 25px;
            padding: 12px 24px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-transform: uppercase;
            width: 100%;
            display: inline-block;
            text-align: center;
        }

        .btn:hover {
            background: linear-gradient(to bottom, #99f2c8, #1f4037);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }

        .btn:active {
            transform: translateY(2px);
            box-shadow: 0 3px 4px rgba(0, 0, 0, 0.1);
        }

        .search-form {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 30px 0;
        }

        .search-form .box {
            width: 300px;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 25px;
            margin-right: 10px;
            outline: none;
            font-size: 16px;
            transition: border 0.3s ease;
        }

        .search-form .box:focus {
            border-color: #319064;
        }

        .products {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .box-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .box {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            background-color: #fff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
        }

        .box:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .box .image {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 10px;
            max-height: 200px;
            object-fit: cover;
        }

        .box .name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .box .price {
            color: #319064;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .box input.qty {
            width: 60px;
            padding: 5px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
            outline: none;
            font-size: 14px;
            transition: border 0.3s ease;
        }

        .box input.qty:focus {
            border-color: #319064;
        }

        .empty {
            text-align: center;
            font-size: 18px;
            color: red;
            margin-top: 20px;
            font-size: 20px; 
        }
    </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
    <h3>Search Page</h3>
    <p> <a href="home.php">Home</a> / Search </p>
</div>

<section class="search-form">
    <form action="" method="post">
        <input type="text" name="search" placeholder="Search products..." class="box">
        <input type="submit" name="submit" value="Search" class="btn">
    </form>
</section>

<section class="products" style="padding-top: 0;">

    <div class="box-container">
    <?php
        if (isset($_POST['submit'])) {
            $search_item = $_POST['search'];
            $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%{$search_item}%'") or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_product = mysqli_fetch_assoc($select_products)) {
    ?>
    <form action="" method="post" class="box">
        <img src="uploaded_img/<?php echo $fetch_product['image']; ?>" alt="" class="image">
        <div class="name"><?php echo $fetch_product['name']; ?></div>
        <div class="price">₪<?php echo $fetch_product['price']; ?></div>
        <input type="number" class="qty" name="product_quantity" min="1" value="1">
        <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
        <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
        <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
        <input type="submit" class="btn" value="Add to Cart" name="add_to_cart">
        <input type="submit" class="btn" value="Ask for Book" name="request_book" style="background-color: #e74c3c; margin-top: 10px;">
        <input type="hidden" name="request_name" value="<?php echo $_SESSION['user_name']; ?>">
        <input type="hidden" name="request_number" value="<?php echo $_SESSION['user_number']; ?>">
        <input type="hidden" name="request_email" value="<?php echo $_SESSION['user_email']; ?>">
    </form>
    <?php
                }
            } else {
                echo '<p class="empty">No results found!</p>';
            }
        }
    ?>
    </div>

</section>

<?php include 'footer.php'; ?>

</body>
</html>
