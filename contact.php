<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['send'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $number = $_POST['number'];
   $msg = mysqli_real_escape_string($conn, $_POST['message']);

   $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');

   if(mysqli_num_rows($select_message) > 0){
      $message[] = 'message sent already!';
   }else{
      mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('query failed');
      $message[] = 'message sent successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contact</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <link rel="stylesheet" href="style.css">
   <style>
      body {
         font-family: Arial, sans-serif;
         background-color: #f2f2f2;
         margin: 0;
         padding: 0;
      }

      .btn {
        background: linear-gradient(to bottom, #1f4037, #99f2c8);
         padding: 20px;         color: #fff;
         border: none;
         padding: 10px 20px;
         text-align: center;
         text-decoration: none;
         display: inline-block;
         font-size: 16px;
         margin-top: 10px;
         cursor: pointer;
         transition: background-color 0.3s ease;
      }

      .btn:hover {
         background-color: #ffffff;
         color: #000000;
      }

      .box {
         width: 100%;
         padding: 10px;
         margin: 10px 0;
         border: 1px solid #ccc;
         border-radius: 5px;
         box-sizing: border-box;
         font-size: 16px;
      }

      .heading {
        background: linear-gradient(to bottom, #1f4037, #99f2c8);
         padding: 20px;         color: #fff;
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

      .contact {
         max-width: 600px;
         margin: auto;
         background-color: #fff;
         padding: 20px;
         border-radius: 5px;
         box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      }

      .contact h3 {
         text-align: center;
         margin-bottom: 20px;
         font-size: 24px;
      }
   </style>
</head>
<body>
   
<?php include 'navbar.php'; ?>

<div class="heading">
   <h3>contact us</h3>
   <p> <a href="home.php">home</a> / contact </p>
</div>

<section class="contact">

   <form action="" method="post">
      <h3>say something!</h3>
      <input type="text" name="name" required placeholder="enter your name" class="box">
      <input type="email" name="email" required placeholder="enter your email" class="box">
      <input type="number" name="number" required placeholder="enter your number" class="box">
      <textarea name="message" class="box" placeholder="enter your message" id="" cols="30" rows="10"></textarea>
      <br><br><br>
      <input type="submit" value="send message" name="send" class="btn">
   </form>

</section>








<?php include 'footer.php'; ?>
<script src="script.js"></script>

</body>
</html>