<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'] ?? null;

if (!isset($admin_id)) {
    header('location: login.php');
    exit();
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `users` WHERE id = '$delete_id'") or die('Query failed');
    header('location: admin_users.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Accounts</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }

        .box-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px;
        }

        .box {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
            position: relative;
        }

        .box:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            transform: translateY(-5px);
        }

        .box p {
            margin: 10px 0;
            font-size: 16px;
            color: #333;
        }

        .box span {
            font-weight: bold;
            color: #555;
        }

        .box a.delete-btn {
            display: inline-block;
            padding: 8px 12px;
            text-decoration: none;
            color: #fff;
            background-color: #e74c3c;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            position: absolute;
            bottom: 10px;
            right: 10px;
            font-size: 14px;
            text-align: center;
        }

        .box a.delete-btn:hover {
            background-color: #c0392b;
        }

        .title {
            text-align: center;
            margin: 30px 0;
            font-size: 28px;
            color: #333;
            border-bottom: 3px solid #319064;
            padding-bottom: 10px;
        }
    </style>
</head>
<body>
   
    <?php include 'admin_navbar.php'; ?>

    <section class="users">
        <h1 class="title">User Accounts</h1>

        <div class="box-container">
            <?php
            $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('Query failed');
            while ($fetch_users = mysqli_fetch_assoc($select_users)) {
            ?>
            <div class="box">
                <p>User ID: <span><?php echo htmlspecialchars($fetch_users['id']); ?></span></p>
                <p>Username: <span><?php echo htmlspecialchars($fetch_users['name']); ?></span></p>
                <p>Email: <span><?php echo htmlspecialchars($fetch_users['email']); ?></span></p>
                <p>User Type: <span style="color:<?php echo ($fetch_users['user_type'] == 'admin') ? '#e67e22' : '#555'; ?>"><?php echo htmlspecialchars($fetch_users['user_type']); ?></span></p>
                <a href="admin_users.php?delete=<?php echo $fetch_users['id']; ?>" onclick="return confirm('Delete this user?');" class="delete-btn">Delete User</a>
            </div>
            <?php
            }
            ?>
        </div>
    </section>

    <script src="admin_script.js"></script>

</body>
</html>
