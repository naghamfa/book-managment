<?php

include 'config.php';
include 'admin_navbar.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location: login.php');
    exit();
}

if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']); 

    $delete_query = "DELETE FROM `reviews` WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $delete_query)) {
        mysqli_stmt_bind_param($stmt, 'i', $delete_id);
        if (mysqli_stmt_execute($stmt)) {
            header('location: reviews_admin.php');
            exit();
        } else {
            echo 'Error: ' . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo 'Error preparing statement: ' . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f9fc;
            margin: 0;
            padding: 0;
        }

        .admin-navbar {
            background: #1f4037;
            color: #fff;
            padding: 15px;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .admin-navbar a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-size: 16px;
        }

        .admin-navbar a:hover {
            text-decoration: underline;
        }

        .reviews-container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        .title {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            color: #1f4037;
            border-bottom: 2px solid #319064;
            padding-bottom: 10px;
        }

        .box-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .box {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }

        .box:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
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

        .rating {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }

        .rating i {
            color: #f1c40f;
            font-size: 18px;
            margin-right: 2px;
        }

        .delete-btn {
            display: inline-block;
            padding: 8px 12px;
            text-decoration: none;
            color: #fff;
            background-color: #e74c3c;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            position: absolute;
            bottom: 10px;
            right: 10px;
            border-radius: 5px;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        .empty {
            text-align: center;
            font-size: 18px;
            color: #555;
            margin-top: 20px;
        }
    </style>
</head>
<body>
   
    <section class="reviews-container">
        <h1 class="title">Reviews</h1>

        <div class="box-container">
            <?php
                $select_reviews = mysqli_query($conn, "SELECT * FROM `reviews`") or die('Query failed');
                if (mysqli_num_rows($select_reviews) > 0) {
                    while ($fetch_review = mysqli_fetch_assoc($select_reviews)) {
                        $rating = intval($fetch_review['rating']);
            ?>
            <div class="box">
                <p>User ID: <span><?php echo htmlspecialchars($fetch_review['user_id']); ?></span></p>
                <div class="rating">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star<?php echo ($i <= $rating) ? '' : '-o'; ?>"></i>
                    <?php endfor; ?>
                </div>
                <p>Comment: <span><?php echo htmlspecialchars($fetch_review['comment']); ?></span></p>
                <p>Created At: <span><?php echo htmlspecialchars($fetch_review['created_at']); ?></span></p>
                <a href="reviews_admin.php?delete=<?php echo urlencode($fetch_review['id']); ?>" onclick="return confirm('Are you sure you want to delete this review?');" class="delete-btn">Delete Review</a>
            </div>
            <?php
                    }
                } else {
                    echo '<p class="empty">You have no reviews!</p>';
                }
            ?>
        </div>
    </section>

    <script src="admin_script.js"></script>

</body>
</html>
