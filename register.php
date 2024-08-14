<?php

include 'config.php';

$message = '';

if(isset($_POST['submit'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
   $user_type = $_POST['user_type'];

   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if(mysqli_num_rows($select_users) > 0){
      $message = 'User already exists!';
   }else{
      if($pass != $cpass){
         $message = 'Confirm password not matched!';
      }else{
         mysqli_query($conn, "INSERT INTO `users`(name, email, password, user_type) VALUES('$name', '$email', '$cpass', '$user_type')") or die('query failed');
         $message = 'Registered successfully!';
         echo '<script>setTimeout(function() { window.location.href = "login.php"; }, 3000);</script>';
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
   <title>Register</title>

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
      <h3>Register Now</h3>
      <?php if(!empty($message)): ?>
         <div class="message">
            <span><?php echo $message; ?></span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
      <?php endif; ?>
      <input type="text" name="name" placeholder="Enter your name" required class="box">
      <input type="email" name="email" placeholder="Enter your email" required class="box">
      <input type="password" name="password" placeholder="Enter your password" required class="box">
      <input type="password" name="cpassword" placeholder="Confirm your password" required class="box">
      <select name="user_type" class="box">
         <option value="user">User</option>
         <option value="admin">Admin</option>
      </select>
      <input type="submit" name="submit" value="Register Now" class="btn">
      <p>Already have an account? <a href="login.php" style="color: #319064;">Login now</a></p>
   </form>
</div>

</body>
</html>
