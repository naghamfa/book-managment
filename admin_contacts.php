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
    mysqli_query($conn, "DELETE FROM `message` WHERE id = '$delete_id'") or die('Query failed');
    header('location: admin_contacts.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>

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

        .delete-btn {
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

        .delete-btn:hover {
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

        .empty {
            text-align: center;
            font-size: 18px;
            color: #555;
        }
    </style>
</head>
<body>
   
    <?php include 'admin_navbar.php'; ?>

    <section class="messages">
        <h1 class="title">Messages</h1>

        <div class="box-container">
            <?php
            $select_message = mysqli_query($conn, "SELECT * FROM `message`") or die('Query failed');
            if (mysqli_num_rows($select_message) > 0) {
                while ($fetch_message = mysqli_fetch_assoc($select_message)) {
            ?>
            <div class="box">
                <p>User ID: <span><?php echo htmlspecialchars($fetch_message['user_id']); ?></span></p>
                <p>Name: <span><?php echo htmlspecialchars($fetch_message['name']); ?></span></p>
                <p>Number: <span><?php echo htmlspecialchars($fetch_message['number']); ?></span></p>
                <p>Email: <span><?php echo htmlspecialchars($fetch_message['email']); ?></span></p>
                <p>Message: <span><?php echo htmlspecialchars($fetch_message['message']); ?></span></p>
                <a href="admin_contacts.php?delete=<?php echo $fetch_message['id']; ?>" onclick="return confirm('Delete this message?');" class="delete-btn">Delete Message</a>
            </div>
            <?php
                }
            } else {
                echo '<p class="empty">You have no messages!</p>';
            }
            ?>
        </div>
    </section>

    <script src="admin_script.js"></script>

</body>
</html>
