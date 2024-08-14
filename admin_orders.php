<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'] ?? null;

if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}

$message = [];

if (isset($_POST['update_order'])) {
    $order_update_id = $_POST['order_id'];
    $update_payment = $_POST['update_payment'];
    $update_delivery_method = $_POST['update_delivery_method'] ?? null;

    $stmt = $conn->prepare("UPDATE orders SET payment_status = ?, delivery_method = ? WHERE id = ?");
    $stmt->bind_param("ssi", $update_payment, $update_delivery_method, $order_update_id);

    if ($stmt->execute()) {
        $message[] = 'Order has been updated!';
    } else {
        $message[] = 'Query failed: ' . $stmt->error;
    }

    $stmt->close();
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        header('location:admin_orders.php');
        exit();
    } else {
        $message[] = 'Query failed: ' . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .title {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            font-size: 32px;
            border-bottom: 4px solid #319064;
            padding-bottom: 15px;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .box-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .box {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            flex: 1 1 calc(33.333% - 20px);
            margin-bottom: 20px;
            transition: box-shadow 0.3s ease;
        }

        .box:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .box p {
            margin: 10px 0;
            font-size: 16px;
            color: #333;
        }

        .box span {
            font-weight: bold;
            color: #319064;
        }

        .form-controls {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 10px;
        }

        select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
            background-color: #f9f9f9;
        }

        .button-group {
            display: flex;
            gap: 10px;
        }

        .option-btn,
        .delete-btn {
            padding: 12px 20px;
            text-decoration: none;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            width: 50%;
            text-align: center;
        }

        .option-btn {
            background: linear-gradient(to bottom, #1f4037, #99f2c8);
            width: 50%;
        }

        .option-btn:hover {
            background-color: #1f4037;
        }

        .delete-btn {
            background-color: #e74c3c;
            width: 50%;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        .message {
            color: #d9534f;
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .empty {
            text-align: center;
            color: #666;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .box {
                flex: 1 1 100%;
            }
        }
    </style>
</head>

<body>

    <?php include 'admin_navbar.php'; ?>

    <section class="orders">
        <h1 class="title">Placed Orders</h1>

        <?php
        if (!empty($message)) {
            foreach ($message as $msg) {
                echo "<p class='message'>$msg</p>";
            }
        }
        ?>

        <div class="box-container">
            <?php
            $select_orders = $conn->prepare("SELECT * FROM orders");
            $select_orders->execute();
            $result = $select_orders->get_result();

            if ($result->num_rows > 0) {
                while ($fetch_orders = $result->fetch_assoc()) {
            ?>
                    <div class="box">
                        <p>User ID: <span><?php echo htmlspecialchars($fetch_orders['user_id']); ?></span></p>
                        <p>Placed On: <span><?php echo htmlspecialchars($fetch_orders['placed_on']); ?></span></p>
                        <p>Name: <span><?php echo htmlspecialchars($fetch_orders['name']); ?></span></p>
                        <p>Number: <span><?php echo htmlspecialchars($fetch_orders['number']); ?></span></p>
                        <p>Email: <span><?php echo htmlspecialchars($fetch_orders['email']); ?></span></p>
                        <p>Address: <span><?php echo htmlspecialchars($fetch_orders['address']); ?></span></p>
                        <p>Total Products: <span><?php echo htmlspecialchars($fetch_orders['total_products']); ?></span></p>
                        <p>Total Price: <span>₪<?php echo htmlspecialchars($fetch_orders['total_price']); ?></span></p>
                        <p>Payment Method: <span><?php echo htmlspecialchars($fetch_orders['method']); ?></span></p>
                        <p>Delivery Method: <span><?php echo htmlspecialchars($fetch_orders['delivery_method']); ?></span></p>
                        <form action="" method="post">
                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($fetch_orders['id']); ?>">
                            <select name="update_payment">
                                <option value="" disabled selected><?php echo htmlspecialchars($fetch_orders['payment_status']); ?></option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                            
                            <input type="submit" value="Update" name="update_order" class="option-btn">
                            <a href="admin_orders.php?delete=<?php echo htmlspecialchars($fetch_orders['id']); ?>" onclick="return confirm('Delete this order?');" class="delete-btn">Delete</a>
                        </form>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">No orders placed yet!</p>';
            }

            $select_orders->close();
            ?>
        </div>

    </section>

    <script src="admin_script.js"></script>

</body>

</html>
