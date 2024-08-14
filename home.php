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

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM cart WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($check_cart_numbers) > 0) {
        $message[] = 'already added to cart!';
    } else {
        mysqli_query($conn, "INSERT INTO cart(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
        $message[] = 'product added to cart!';
    }

}
if (isset($_POST['ask_for_book'])) {
    $product_name = $_POST['product_name'];
    header("location:requests_book.php?product_name=$product_name");
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <link rel="stylesheet" href="style.css">

   <style>
      .home {
         min-height: 70vh;
         background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url(uploaded_img/download.jpeg) no-repeat;
         background-size: cover;
         background-position: center;
         display: flex;
         align-items: center;
         justify-content: center;
         text-align: center;
         color: #ffffff;
         padding: 2rem;
      }

      .home h3 {
         font-size: 2.5rem;
         margin-bottom: 1rem;
      }

      .home p {
         font-size: 1.2rem;
         line-height: 1.6;
         margin-bottom: 2rem;
      }

      .home .white-btn {
         background-color: #ffffff;
         color: #000000;
         padding: 1rem 2rem;
         text-decoration: none;
         border-radius: 5px;
         font-size: 1.2rem;
         transition: all 0.3s ease;
      }

      .home .white-btn:hover {
         background-color: #000000;
         color: #ffffff;
      }

      .products {
         padding: 2rem 0;
      }

      .products .title {
         text-align: center;
         font-size: 2rem;
         margin-bottom: 2rem;
         color: #000000;
      }

      .box-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    gap: 2rem;
}

.box {
    background-color: #ffffff;
    color: #000000;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    width: calc(100% - 1rem);
    height: 100%;
    text-align: center;
    margin-bottom: 2rem;
}

.box:hover {
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
}

.box img {
    width: 70%; 
    max-height: 250px; 
    object-fit: cover; 
    border-radius: 8px;
    margin-bottom: 1rem;
}

.box .name {
    font-size: 1.4rem;
    margin-bottom: 1rem;
}

.box .price {
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 1rem;
}

.box .qty {
    width: 50px;
    margin: 0.5rem 0;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 5px;
    text-align: center;
    font-size: 0.9rem;
}

.box .btn {
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

.box .btn:hover {
    background: #ffffff;
    color: #000000;
}
      .empty {
         color: #ffffff;
         text-align: center;
         margin-top: 2rem;
      }

      .option-btn {
         background-color: #000000;
         color: #ffffff;
         padding: 1rem 2rem;
         border: none;
         border-radius: 5px;
         text-decoration: none;
         display: inline-block;
         transition: all 0.3s ease;
      }

      .option-btn:hover {
         background-color: #ffffff;
         color: #000000;
      }

      .about {
         padding: 4rem 0;
         background-color: #f7f7f7;
         display: flex;
         justify-content: center;
         align-items: center;
         flex-direction: column;
         text-align: center;
      }

      .about .flex {
         display: flex;
         align-items: center;
         justify-content: center;
         gap: 2rem;
         flex-direction: column;
      }

      .about .image {
         max-width: 400px;
         width: 100%;
      }

      .about .image img {
         width: 100%;
         border-radius: 8px;
      }

      .about .content {
    max-width: 600px;
    width: 100%;
    background: linear-gradient(to bottom, #1f4037, #99f2c8); 
    border-radius: 8px; 
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
}

.about .content h3 {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #ffffff; 
}

.about .content p {
    font-size: 1rem;
    line-height: 1.8;
    color: #ffffff; 
    margin-bottom: 1rem;
}

.about .content .btn {
    background: #ffffff; 
    color: #000000;
    padding: 0.8rem 1.5rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    margin-top: 1rem;
}

.about .content .btn:hover {
    background: #000000; 
    color: #ffffff;
}

      .home-contact {
         padding: 4rem 0;
         background: linear-gradient(to bottom right, #e0f7fa, #c1e7e3);
         text-align: center;
      }

      .home-contact .content h3 {
         font-size: 2rem;
         margin-bottom: 1rem;
         color: #333;
      }

      .home-contact .content p {
         font-size: 1rem;
         line-height: 1.8;
         color: #666;
         margin-bottom: 1rem;
      }

      .home-contact .content .white-btn {
         background-color: #ffffff;
         color: #000000;
         padding: 1rem 2rem;
         text-decoration: none;
         border-radius: 5px;
         font-size: 1.2rem;
         transition: all 0.3s ease;
      }

      .home-contact .content .white-btn:hover {
         background-color: #000000;
         color: #ffffff;
      }
   </style>
</head>
<body>
   
<?php include 'navbar.php'; ?>

<section class="home">

   <div class="content">
      <h3>Hand Picked Book to Your Door</h3>
      <p>The system that came to improve your management experience for the world of books.</p>
      <a href="about.php" class="white-btn">Discover More</a>
   </div>

</section>

<section class="products">

   <h1 class="title">Latest Products</h1>

   <div class="box-container">

<?php  
   $select_products = mysqli_query($conn, "SELECT * FROM products LIMIT 6") or die('query failed');
   if (mysqli_num_rows($select_products) > 0) {
      while ($fetch_products = mysqli_fetch_assoc($select_products)) {
?>
   <form action="" method="post" class="box">
      <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
      <div class="name"><?php echo $fetch_products['name']; ?></div>
      <div class="price">₪<?php echo $fetch_products['price']; ?></div>
      <div class="description"><?php echo $fetch_products['description']; ?></div>
      <div class="author">Author: <?php echo $fetch_products['author']; ?></div>
      <div class="book-type">Book Type: <?php echo $fetch_products['book_type']; ?></div>
      <input type="number" min="1" name="product_quantity" value="1" class="qty">
      <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
      <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
      <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
      <input type="submit" value="Add to Cart" name="add_to_cart" class="btn">
      <input type="submit" value="Ask for Book" name="ask_for_book" class="btn">

     </form>
<?php
      }
   } else {
      echo '<p class="empty">no products added yet!</p>';
   }
?>

   </div>

</section>

<section class="about">

   <div class="flex">
      <div class="image">
         <img src="images/about-img.jpg" alt="">
      </div>

      <div class="content" style="color: #ffffff;">
   <h3 style="color: #ffffff;">About Us</h3>
   <p style="color: #ffffff;">The system that came to improve your management experience for the world of books.</p>
   <a href="about.php" class="btn" style="background: linear-gradient(to bottom, #1f4037, #99f2c8); color: #ffffff;">Read More</a>
</div>


   </div>

</section>

<section class="home-contact">

   <div class="content">
      <h3>Have Any Questions?</h3>
      <a href="contact.php" class="white-btn">Contact Us</a>
   </div>

</section>

<?php include 'footer.php'; ?>

</body>
</html>
