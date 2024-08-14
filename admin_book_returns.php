<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}

$message = "";

if (isset($_GET['update_status']) && isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = mysqli_real_escape_string($conn, $_GET['status']);

    $query = "UPDATE book_returns SET status='$status' WHERE id=$id";

    if (mysqli_query($conn, $query)) {
        $message = 'Status updated successfully!';
    } else {
        $message = 'Failed to update status. Please try again.';
    }
}

if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);

    $delete_query = "DELETE FROM book_returns WHERE id=$id";

    if (mysqli_query($conn, $delete_query)) {
        $message = 'Return request deleted successfully!';
    } else {
        $message = 'Failed to delete request. Please try again.';
    }
}

$query = "SELECT * FROM book_returns";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Book Returns</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600&display=swap">

   
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .admin-container {
            max-width: 95%;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-top: 20px;
            overflow-x: auto;
        }

        .admin-container h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
            border-bottom: 3px solid #319064;
            padding-bottom: 10px;
        }

        .admin-container table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: auto;
        }

        .admin-container th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center; 
            font-size: 12px;
            word-wrap: break-word; 
        }

        .admin-container th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .status-form select {
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
        }

        .status-form input[type="submit"] {
            background-color: #319064;
            color: #fff;
            border: none;
            padding: 8px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .status-form input[type="submit"]:hover {
            background-color: #1f4037;
        }

        .empty {
            text-align: center;
            color: #666;
            font-style: italic;
            font-size: 14px;
        }

        .btn-success, .btn-danger {
            display: inline-block;
            padding: 8px 10px; 
            text-decoration: none;
            color: #fff;
            border-radius: 5px;
            font-size: 12px; 
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin: 2px 0; 
        }

        .btn-success {
            background: linear-gradient(to bottom, #1f4037, #99f2c8);
        }

        .btn-success:hover {
            background-color: #ffffff;
            color: #000000;
        }

        .btn-danger {
            background-color: #e74c3c;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .status-pending {
            color: #e74c3c;
        }

        .status-completed {
            color: #2ecc71;
        }

        .status-rejected {
            color: #95a5a6;
        }

        .return-method {
            padding: 5px;
            border-radius: 4px;
            color: #fff;
            font-weight: bold;
            text-align: center;
            font-size: 11px; 
        }

        .return-method-standard {
            background-color: #3498db; 
        }

        .return-method-express {
            background-color: #e67e22; 
        }

        .return-method-same-day {
            background-color: #27ae60; 
        }

        .return-method-null {
            background-color: #95a5a6;
        }
    </style>
</head>
<body>

<?php include 'admin_navbar.php'; ?>

    <div class="admin-container">
        <h1>Manage Book Returns</h1>

        <?php if ($message): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Product Name</th>
                    <th>Return Date</th>
                    <th>Name</th>
                    <th>Contact Number</th>
                    <th>Email</th>
                    <th>Return Method</th>
                    <th>Status</th>
                    <th>Card Number</th>
                    <th>Expiry Date</th>
                    <th>CVV</th>
                    <th>Delivery Charge</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $status_class = 'status-pending'; 
                        if ($row['status'] === 'completed') {
                            $status_class = 'status-completed';
                        } elseif ($row['status'] === 'rejected') {
                            $status_class = 'status-rejected';
                        }

                        $method_class = 'return-method-null'; 
                        if (strtolower($row['return_method']) === 'standard') {
                            $method_class = 'return-method-standard';
                        } elseif (strtolower($row['return_method']) === 'express') {
                            $method_class = 'return-method-express';
                        } elseif (strtolower($row['return_method']) === 'same day') {
                            $method_class = 'return-method-same-day';
                        }

                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['user_id']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['product_name']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['return_date']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['number']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                        echo '<td class="return-method ' . $method_class . '">' . htmlspecialchars($row['return_method']) . '</td>';
                        echo '<td class="' . $status_class . '">' . htmlspecialchars(ucfirst($row['status'])) . '</td>';
                        echo '<td>' . htmlspecialchars($row['card_number']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['expiry_date']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['cvv']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['delivery_charge']) . '</td>';
                        echo '<td>
                                <a href="?update_status=completed&id=' . htmlspecialchars($row['id']) . '&status=completed" class="btn-success">Mark as Completed</a>
                                <a href="?delete_id=' . htmlspecialchars($row['id']) . '" class="btn-danger" onclick="return confirm(\'Are you sure you want to delete this request?\');">Delete</a>
                              </td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="14" class="empty">No book returns found</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
