<?php
include 'config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$requests_query = "SELECT * FROM book_requests WHERE user_id = '$user_id'";
$requests_result = mysqli_query($conn, $requests_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>My Book Requests</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="style.css">
   <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f3f4f6;
            color: #333;
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

        .requests-container {
            max-width: 900px;
            margin: 30px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .requests-container h1 {
            font-size: 28px;
            margin-bottom: 25px;
            text-align: center;
            color: #333;
            
        }

        .request-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .request-item h2 {
            font-size: 22px;
            margin: 0 0 10px;
            color: black;
            font-weight: 600;
            
        }

        .request-item p {
            margin: 10px 0;
            font-size: 16px;
            color: black;

        }

        .request-item p strong {
            color: black;
            font-size: 16px;
        }

        .status {
            font-weight: bold;
            font-size: 16px;
            text-transform: capitalize;
        }

        .status.pending {
            color: #ff9900;
        }

        .status.approved {
            color: #4CAF50;
        }

        .status.declined {
            color: #f44336;
        }

        .request-item:hover {
            background-color: #f1f1f1;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="heading">
    <h3>My Book Requests</h3>
    <p><a href="home.php">Home</a> / My Book Requests</p>
</div>

<div class="requests-container">
    <h1>Your Requests</h1>
    <?php if (mysqli_num_rows($requests_result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($requests_result)): ?>
            <div class="request-item">
                <h2><?php echo $row['product_name']; ?></h2>
                <p><strong>Requested On:</strong> <?php echo $row['request_date']; ?></p>
                <p><strong>Delivery Method:</strong> <?php echo ucfirst($row['delivery_method']); ?></p>
                <p><strong>Status:</strong> <span class="status <?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span></p>
                <?php if ($row['delivery_method'] === 'delivery'): ?>
                    <p><strong>Delivery Charge:</strong> ₪<?php echo $row['delivery_charge']; ?></p>
                <?php endif; ?>
                <?php if ($row['status'] === 'approved'): ?>
                    <p><strong>Reminder:</strong> Please return the book within one month from the date of receipt.</p>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="
            text-align: center;
            font-size: 16px;
            color: #888;
        ">No requests found.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
