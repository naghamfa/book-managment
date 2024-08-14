<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location: login.php');
   exit();
}

$reviews_query = "SELECT rating, comment, created_at FROM reviews ORDER BY created_at DESC";
$reviews_result = mysqli_query($conn, $reviews_query);
$reviews = mysqli_fetch_all($reviews_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About Us</title>

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

      .about {
         padding: 20px;
         background: #fff;
         margin: 0 auto;
         max-width: 900px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
         border-radius: 8px;
      }

      .about .flex {
         display: flex;
         flex-wrap: wrap;
         gap: 20px;
         align-items: center;
      }

      .about .image {
         flex: 1;
         min-width: 300px;
      }

      .about .image img {
         width: 100%;
         border-radius: 8px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }

      .about .content {
         flex: 2;
         color: #555;
      }

      .about h3 {
         font-size: 26px;
         margin-top: 0;
         margin-bottom: 20px;
         color: #1f4037;
      }

      .about p {
         line-height: 1.6;
         margin-bottom: 15px;
         font-size: 16px;
      }

      .about .btn {
         display: inline-block;
         padding: 12px 20px;
         background-color: #319064;
         color: #fff;
         text-decoration: none;
         border-radius: 5px;
         font-size: 16px;
         transition: background-color 0.3s ease;
         margin-top: 20px;
      }

      .about .btn:hover {
         background-color: #1f4037;
      }

      .reviews {
         padding: 20px;
         background: #fff;
         margin: 20px auto;
         max-width: 900px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
         border-radius: 8px;
      }

      .reviews h1 {
         font-size: 28px;
         margin-bottom: 20px;
         color: #1f4037;
         border-bottom: 2px solid #319064;
         padding-bottom: 10px;
      }

      .review-box {
         background: #f9f9f9;
         padding: 15px;
         margin-bottom: 15px;
         border-radius: 8px;
         box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }

      .review-box p {
         margin: 5px 0;
         font-size: 16px;
      }

      .stars {
         color: #f5a623;
      }

      .authors {
         padding: 20px;
         background: #fff;
         margin: 20px auto;
         max-width: 900px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
         border-radius: 8px;
      }

      .authors .title {
         font-size: 28px;
         margin-bottom: 20px;
         color: #1f4037;
         border-bottom: 2px solid #319064;
         padding-bottom: 10px;
         text-align: center;
      }

      .authors .box-container {
         display: flex;
         flex-wrap: wrap;
         gap: 20px;
         justify-content: space-between;
      }

      .authors .box {
         width: calc(50% - 20px);
         background: #fff;
         padding: 10px;
         border-radius: 8px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
         text-align: center;
      }

      .authors .box img {
         width: 100%;
         border-radius: 8px;
         box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }

      .authors .box h3 {
         margin-top: 10px;
         font-size: 20px;
         font-weight: bold;
         color: #333;
      }

      .authors .share {
         margin-top: 10px;
      }

      .authors .share a {
         color: #1f4037;
         background: #fff;
         padding: 5px;
         margin-right: 5px;
         border-radius: 50%;
         display: inline-block;
         text-decoration: none;
         font-size: 16px;
         transition: background 0.3s ease;
      }

      .authors .share a:hover {
         background: #99f2c8;
         color: #1f4037;
      }
   </style>
</head>

<body>

   <?php include 'navbar.php'; ?>

   <div class="heading">
      <h3>About Us</h3>
      <p><a href="home.php" style="color: #99f2c8;">Home</a> / About</p>
   </div>

   <section class="about">
      <div class="flex">
         <div class="image">
            <img src="uploaded_img/why choose us.jpeg" alt="About Us">
         </div>
         <div class="content">
            <h3>Why Choose Us?</h3>
            <p>At BOOKTOUCH, we focus on quality and service to our customers. With a wide selection of books in all genres, we guarantee you an impressive shopping experience.</p>
            <p>An advanced and designed website that provides you with maximum accessibility and convenience to our variety of books.</p>
            <p>At BOOKTOUCH, we make sure that our customers enjoy a pleasant and personalized reading experience, including the possibility to borrow and return books.</p>
            <p><strong>Books can be borrowed for a period of one month from the date of receipt. There is a shipping fee of ₪30. If the book is not returned by the due date, a late fee of ₪100 will be charged.</strong></p>
            <p style="margin-top: 20px; border-top: 1px solid #ccc; padding-top: 20px;">
                <strong>Returning the book is easy:</strong><br>
                <strong>You can either return it at our branch or arrange a pickup delivery with our courier service. To arrange a pickup delivery, please contact our courier service at <span style="color: #319064;">057-4638289</span>.</strong>
            </p>
            <p><strong>Our service is available online and you can also visit our branch. ♡</strong></p>
            <a href="contact.php" class="btn">Contact Us</a>
         </div>
      </div>
   </section>

   <section class="reviews">
      <h1>Client's Reviews</h1>
      <?php if ($reviews): ?>
         <?php foreach ($reviews as $review): ?>
            <div class="review-box">
               <p class="stars">
                  <?php for ($i = 1; $i <= 5; $i++): ?>
                     <?php if ($i <= $review['rating']): ?>
                        <i class="fas fa-star"></i>
                     <?php else: ?>
                        <i class="fas fa-star" style="color: #ddd;"></i>
                     <?php endif; ?>
                  <?php endfor; ?>
               </p>
               <p><?php echo htmlspecialchars($review['comment']); ?></p>
               <p><em><?php echo date('F j, Y', strtotime($review['created_at'])); ?></em></p>
            </div>
         <?php endforeach; ?>
      <?php else: ?>
         <p>No reviews yet.</p>
      <?php endif; ?>
   </section>

   <section class="authors">

   <h1 class="title" style="color: #000; text-align: center; font-size: 32px; margin-bottom: 20px;">Popular Authors</h1>

      <div class="box-container">

         <div class="box">
            <img src="uploaded_img/Ezra Jack Keats.jpeg" alt="">
            <div class="share">
               <a href="#" class="fab fa-facebook-f"></a>
               <a href="#" class="fab fa-twitter"></a>
               <a href="#" class="fab fa-instagram"></a>
               <a href="#" class="fab fa-linkedin"></a>
            </div>
            <h3>Ezra Jack Keats</h3>
         </div>

        

         <div class="box">
            <img src="uploaded_img/dr seuss.webp" alt="">
            <div class="share">
               <a href="#" class="fab fa-facebook-f"></a>
               <a href="#" class="fab fa-twitter"></a>
               <a href="#" class="fab fa-instagram"></a>
               <a href="#" class="fab fa-linkedin"></a>
            </div>
            <h3>Dr. Seuss</h3>
         </div>

        
      </div>

   </section>


   <?php include 'footer.php'; ?>

</body>
</html>
