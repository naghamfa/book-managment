<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

$message = array();

if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($check_cart_numbers) > 0) {
        $message[] = 'Product already added to cart!';
    } else {
        mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
        $message[] = 'Product added to cart successfully!';
    }
}

if (isset($_POST['ask_for_book'])) {
    $product_name = $_POST['product_name'];
    header('Location: requests_book.php?product_name=' . urlencode($product_name));
}

$selected_type = isset($_GET['book_type']) ? $_GET['book_type'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>

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


        .btn, .option-btn, .delete-btn, .disabled {
            background: linear-gradient(to bottom, #1f4037, #99f2c8);
            color: #ffffff;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
            display: block;
            width: 100%;
        }

        .btn:hover, .option-btn:hover, .delete-btn:hover {
            background-color: #ffffff;
            color: #000000;
        }

        .disabled {
            background-color: #ccc;
            cursor: not-allowed;
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

        .main-container {
            display: flex;
            padding: 20px;
        }

        .sidebar {
            width: 250px;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin-right: 20px;
            transition: all 0.3s ease;
        }

        .sidebar:hover {
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            transform: scale(1.02);
        }

        .sidebar-title {
            font-size: 22px;
            margin-bottom: 15px;
            color: #1f4037;
            border-bottom: 2px solid #1f4037;
            padding-bottom: 5px;
        }

        .category-list {
            list-style: none;
            padding: 0;
        }

        .category-list li {
            margin-bottom: 10px;
        }

        .category-list a {
            text-decoration: none;
            color: #333;
            font-size: 18px;
            padding: 10px 15px;
            border-radius: 5px;
            display: block;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .category-list a:hover, .category-list a.active {
            background: linear-gradient(to bottom, #1f4037, #99f2c8);
            color: #ffffff;
            font-weight: bold;
        }

        .products {
            flex: 1;
            max-width: 800px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .products h1 {
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
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .box:hover {
            transform: scale(1.02);
        }

        .box img {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .box .name {
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .box .price {
            color: #1f4037;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .box .description,
        .box .author,
        .box .book-type {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .box .qty {
            width: 50px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            margin-bottom: 10px;
        }

        .empty {
            text-align: center;
            font-size: 18px;
            margin-top: 20px;
        }

        .message {
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
   
<?php include 'navbar.php'; ?>

<div class="heading">
    <h3>Our Shop</h3>
    <p><a href="home.php">Home</a> / Shop</p>
</div>

<div class="main-container">

    <aside class="sidebar">
        <h2 class="sidebar-title">Book Categories</h2>
        <ul class="category-list">
            <li><a href="?book_type=children's book" class="<?php echo $selected_type == "children's book" ? 'active' : ''; ?>">Children's Book</a></li>
            <li><a href="?book_type=fiction" class="<?php echo $selected_type == "fiction" ? 'active' : ''; ?>">Fiction</a></li>
            <li><a href="?book_type=non-fiction" class="<?php echo $selected_type == "non-fiction" ? 'active' : ''; ?>">Non-Fiction</a></li>
            <li><a href="?book_type=mystery" class="<?php echo $selected_type == "mystery" ? 'active' : ''; ?>">Mystery</a></li>
            <li><a href="?book_type=fantasy" class="<?php echo $selected_type == "fantasy" ? 'active' : ''; ?>">Fantasy</a></li>
        </ul>
    </aside>

    <section class="products">
        <h1 class="title"></h1>

        <div class="box-container">
        <?php  
            $query = "SELECT * FROM `products`";
            if (!empty($selected_type)) {
                $query .= " WHERE `book_type` = '" . mysqli_real_escape_string($conn, $selected_type) . "'";
            }

            $select_products = mysqli_query($conn, $query) or die('query failed: ' . mysqli_error($conn));

            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
        ?>
        <form action="" method="post" class="box">
            <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
            <div class="name"><?php echo $fetch_products['name']; ?></div>
            <div class="price"><?php echo $fetch_products['price']; ?> ₪</div>
            <div class="author">Author:<?php echo $fetch_products['author']; ?></div>
            <div class="description"><?php echo $fetch_products['description']; ?></div>
            <div class="book-type">book type:<?php echo $fetch_products['book_type']; ?></div>
            <input type="number" name="product_quantity" min="1" value="1" class="qty">
            <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
            <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
            <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
            <input type="submit" name="add_to_cart" value="Add to Cart" class="btn">
            <input type="submit" name="ask_for_book" value="Ask for Book" class="btn">
        </form>
        <?php
                }
            } else {
                echo '<p class="empty">No books found!</p>';
            }
        ?>
        </div>
    </section>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
