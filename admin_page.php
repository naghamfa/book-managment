<?php
include 'config.php';
include 'admin_navbar.php';
session_start();
$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Rubik', sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        .dashboard {
            padding: 2rem;
        }

        .title {
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 2rem;
            color: #fff;
            background: linear-gradient(to right, #1f4037, #99f2c8); 
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, 0.1);
            border: 2px solid #319064;
        }

        .title span {
            color: #319064; 
        }

        .box-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .box {
            background-color: #fff;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .box:hover {
            transform: translateY(-5px);
            box-shadow: 0 .75rem 1.5rem rgba(0, 0, 0, 0.2);
        }

        .box h3 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: #319064;
        }

        .box p {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 1rem;
        }

        .box a {
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            color: #fff;
            background-color: #319064;
            border-radius: 5px;
            font-size: 1.2rem;
            transition: background-color 0.3s ease;
        }

        .box a:hover {
            background-color: #1f4037;
        }

        @media (max-width: 768px) {
            .title {
                font-size: 2rem;
                padding: 0.75rem;
            }

            .box h3 {
                font-size: 1.8rem;
            }

            .box p {
                font-size: 1rem;
            }

            .box a {
                font-size: 1rem;
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>

    <section class="dashboard">

        <h1 class="title">Admin Dashboard</h1>

        <div class="box-container">

            <div class="box">
                <?php
                $total_pendings = 0;
                $select_pending = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'pending'") or die('query failed');
                if (mysqli_num_rows($select_pending) > 0) {
                    while ($fetch_pendings = mysqli_fetch_assoc($select_pending)) {
                        $total_price = $fetch_pendings['total_price'];
                        $total_pendings += $total_price;
                    };
                };
                ?>
                <h3>₪<?php echo number_format($total_pendings, 2); ?></h3>
                <p>Total Pendings</p>
            </div>

            <div class="box">
                <?php
                $total_completed = 0;
                $select_completed = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'completed'") or die('query failed');
                if (mysqli_num_rows($select_completed) > 0) {
                    while ($fetch_completed = mysqli_fetch_assoc($select_completed)) {
                        $total_price = $fetch_completed['total_price'];
                        $total_completed += $total_price;
                    };
                };
                ?>
                <h3>₪<?php echo number_format($total_completed, 2); ?></h3>
                <p>Completed Payments</p>
            </div>

            <div class="box">
                <?php
                $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
                $number_of_orders = mysqli_num_rows($select_orders);
                ?>
                <h3><?php echo number_format($number_of_orders); ?></h3>
                <p>Orders Placed</p>
            </div>

            <div class="box">
                <?php
                $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
                $number_of_products = mysqli_num_rows($select_products);
                ?>
                <h3><?php echo number_format($number_of_products); ?></h3>
                <p>Products Added</p>
            </div>

            <div class="box">
                <?php
                $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'user'") or die('query failed');
                $number_of_users = mysqli_num_rows($select_users);
                ?>
                <h3><?php echo number_format($number_of_users); ?></h3>
                <p>Normal Users</p>
            </div>

            <div class="box">
                <?php
                $select_admins = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'admin'") or die('query failed');
                $number_of_admins = mysqli_num_rows($select_admins);
                ?>
                <h3><?php echo number_format($number_of_admins); ?></h3>
                <p>Admin Users</p>
            </div>

            <div class="box">
                <?php
                $select_account = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
                $number_of_account = mysqli_num_rows($select_account);
                ?>
                <h3><?php echo number_format($number_of_account); ?></h3>
                <p>Total Accounts</p>
            </div>

            <div class="box">
                <?php
                $select_messages = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
                $number_of_messages = mysqli_num_rows($select_messages);
                ?>
                <h3><?php echo number_format($number_of_messages); ?></h3>
                <p>New Messages</p>
            </div>

            <div class="box">
                <?php
                $select_requests = mysqli_query($conn, "SELECT * FROM `book_requests`") or die('query failed');
                $number_of_requests = mysqli_num_rows($select_requests);
                ?>
                <h3><?php echo number_format($number_of_requests); ?></h3>
                <p>Book Requests</p>
                <a href="admin_requests.php">View Requests</a>
            </div>

            <div class="box">
                <?php
                $select_reviews = mysqli_query($conn, "SELECT * FROM `reviews`") or die('query failed');
                $number_of_reviews = mysqli_num_rows($select_reviews);
                ?>
                <h3><?php echo number_format($number_of_reviews); ?></h3>
                <p>Total Reviews</p>
                <a href="reviews_admin.php">View Reviews</a>
            </div>

            <div class="box">
                <?php
                $select_returns = mysqli_query($conn, "SELECT * FROM `book_returns`") or die('query failed');
                $number_of_returns = mysqli_num_rows($select_returns);
                ?>
                <h3><?php echo number_format($number_of_returns); ?></h3>
                <p>Book Returns</p>
                <a href="admin_book_returns.php">Manage Returns</a>
            </div>

        </div>
    </section>

</body>
</html>
