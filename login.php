<?php

include 'config.php';
session_start();

if(isset($_POST['submit'])){

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));

   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if(mysqli_num_rows($select_users) > 0){

      $row = mysqli_fetch_assoc($select_users);

      if($row['user_type'] == 'admin'){

         $_SESSION['admin_name'] = $row['name'];
         $_SESSION['admin_email'] = $row['email'];
         $_SESSION['admin_id'] = $row['id'];
         header('location: admin_page.php');

      }elseif($row['user_type'] == 'user'){

         $_SESSION['user_name'] = $row['name'];
         $_SESSION['user_email'] = $row['email'];
         $_SESSION['user_id'] = $row['id'];
         header('location: home.php');

      }

   }else{
      $message[] = 'Incorrect email or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <link rel="stylesheet" href="style.css">
   <style>
      body {
         margin: 0;
         padding: 0;
         font-family: Arial, sans-serif;
         display: flex;
         justify-content: center;
         align-items: center;
         height: 100vh;
         background: linear-gradient(to bottom, #1f4037, #99f2c8);
      }

      .form-container {
         background: #fff;
         padding: 20px;
         border-radius: 8px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
         width: 300px;
      }

      .form-container h3 {
         text-align: center;
         margin-bottom: 20px;
         color: #1f4037;
      }

      .box {
         width: 100%;
         padding: 10px;
         margin-bottom: 15px;
         border: 1px solid #ccc;
         border-radius: 4px;
         box-sizing: border-box;
         font-size: 14px;
      }

      .btn {
         width: 100%;
         background-color: #1f4037;
         color: #fff;
         border: none;
         padding: 10px;
         border-radius: 4px;
         cursor: pointer;
         font-size: 16px;
      }

      .btn:hover {
         background-color: #99f2c8;
      }

      p {
         text-align: center;
         margin-top: 10px;
         color: #319064;
      }

      a {
         color: #319064;
         text-decoration: none;
      }

      a:hover {
         text-decoration: underline;
      }

      .message {
         background-color: #f8d7da;
         color: #721c24;
         padding: 10px;
         margin-bottom: 10px;
         border-radius: 4px;
         display: flex;
         justify-content: space-between;
         align-items: center;
      }

      .message span {
         flex: 1;
      }

      .message i {
         margin-left: 10px;
         cursor: pointer;
      }
   </style>
</head>
<body>

<div class="form-container">
   <form action="" method="post">
      <h3>Login Now</h3>
      <?php if(isset($message)): ?>
         <div class="message">
            <span><?php echo $message; ?></span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
      <?php endif; ?>
      <input type="email" name="email" placeholder="Enter your email" required class="box">
      <input type="password" name="password" placeholder="Enter your password" required class="box">
      <input type="submit" name="submit" value="Login Now" class="btn">
      <p>Don't have an account? <a href="register.php" style="color: #319064;">Register now</a></p>
   </form>
</div>

</body>
</html>
