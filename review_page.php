<?php

include 'config.php';
include 'navbar.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = (int) $_POST['rating'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    $query = "INSERT INTO reviews (user_id, rating, comment) VALUES ('$user_id', '$rating', '$comment')";
    
    if (mysqli_query($conn, $query)) {
        $message = 'Thank you for your feedback!';
    } else {
        $message = 'Failed to submit your review. Please try again.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
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
        .review-container {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
            resize: vertical;
        }

        .star-rating {
            direction: rtl;
            font-size: 24px;
            unicode-bidi: bidi-override;
            display: inline-block;
            margin-bottom: 15px;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            color: #ddd;
            cursor: pointer;
        }

        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #f5a623;
        }

        .btn {
            background: linear-gradient(to bottom, #1f4037, #99f2c8);
            color: #fff;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #ffffff;
            color: #000000;
        }

        .notification {
            background-color: #319064;
            color: white;
            padding: 15px;
            text-align: center;
            margin-bottom: 20px;
            display: none;
        }

    </style>
</head>
<body>


<div class="heading">
    <h3>Leave a Review</h3>
    <p><a href="home.php">Home</a> / Leave a Review</p>
</div>

<div class="review-container">
    <div id="notification" class="notification"><?php echo $message; ?></div>

    <form action="" method="post">
        <div class="form-group">
            <label for="rating">Rating:</label>
            <div class="star-rating">
                <input type="radio" id="star5" name="rating" value="5" required><label for="star5" title="5 stars">★</label>
                <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4 stars">★</label>
                <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3 stars">★</label>
                <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2 stars">★</label>
                <input type="radio" id="star1" name="rating" value="1"><label for="star1" title="1 star">★</label>
            </div>
        </div>
        <div class="form-group">
            <label for="comment">Comment:</label>
            <textarea id="comment" name="comment" rows="5" required></textarea>
        </div>
        <input type="submit" value="Submit Review" class="btn">
    </form>
</div>

<script>
    function showNotification() {
        const notification = document.getElementById('notification');
        if (notification.textContent.trim() !== '') {
            notification.style.display = 'block';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 5000);
        }
    }

    showNotification();
</script>

<?php include 'footer.php'; ?>

</body>
</html>
