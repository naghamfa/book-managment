<?php
include 'config.php';
include 'admin_navbar.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}

$query = "SELECT * FROM book_requests";
$result = mysqli_query($conn, $query);

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = mysqli_real_escape_string($conn, $_GET['action']);
    $id = intval($_GET['id']);

    if ($action === 'approve') {
        $update_query = "UPDATE book_requests SET status = 'approved', approval_date = NOW() WHERE id = $id";
    } elseif ($action === 'reject') {
        $update_query = "UPDATE book_requests SET status = 'rejected' WHERE id = $id";
    } elseif ($action === 'delete') {
        $update_query = "DELETE FROM book_requests WHERE id = $id";
    } else {
        $update_query = "";
    }

    if ($update_query) {
        mysqli_query($conn, $update_query);
        echo "<script>setTimeout(function() { window.location.href = '$_SERVER[PHP_SELF]'; }, 1000);</script>"; // Refresh the page after 1 second
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Book Requests</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600&display=swap">

    <style>
        body {
            font-family: 'Rubik', sans-serif;
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

        .status-pending {
            color: #e67e22; 
        }

        .status-approved {
            color: #2ecc71;
        }

        .status-rejected {
            color: #e74c3c; 
        }

        .status-form {
            margin-bottom: 20px;
        }

        .status-form select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .status-form input[type="submit"] {
            background-color: #319064;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .status-form input[type="submit"]:hover {
            background-color: #1f4037;
        }

        .empty {
            text-align: center;
            color: #666;
            font-style: italic;
        }

        .btn-success {
            display: inline-block;
            padding: 8px 12px;
            text-decoration: none;
            color: #fff;
            background: linear-gradient(to bottom, #1f4037, #99f2c8);
            border-radius: 4px;
            font-size: 12px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-right: 5px;
        }

        .btn-success:hover {
            background-color: #ffffff;
            color: #000000;
        }

        .btn-danger {
            display: inline-block;
            padding: 8px 12px;
            text-decoration: none;
            color: #fff;
            background-color: #e74c3c;
            border-radius: 4px;
            font-size: 12px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-right: 5px;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        @media screen and (max-width: 768px) {
            .admin-container table, th, td {
                font-size: 12px;
            }
            
            .admin-container th, td {
                padding: 8px;
            }
            
            .btn-success, .btn-danger {
                font-size: 10px;
                padding: 5px 8px;
            }
        }
    </style>
</head>
<body>

    <div class="admin-container">
        <h1>Manage Book Requests</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Product Name</th>
                    <th>Request Date</th>
                    <th>Name</th>
                    <th>Contact Number</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Approval Date</th>
                    <th>Delivery Method</th>
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
                        // Determine the status class
                        $status_class = 'status-pending'; // Default to pending
                        if ($row['status'] === 'approved') {
                            $status_class = 'status-approved';
                        } elseif ($row['status'] === 'rejected') {
                            $status_class = 'status-rejected';
                        }

                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['user_id']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['product_name']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['request_date']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['number']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                        echo '<td class="' . $status_class . '">' . htmlspecialchars(ucfirst($row['status'])) . '</td>';
                        echo '<td>' . htmlspecialchars($row['approval_date']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['delivery_method']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['card_number']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['expiry_date']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['cvv']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['delivery_charge']) . '</td>';
                        echo '<td>';
                        if ($row['status'] === 'pending') {
                            echo '<a href="?action=approve&id=' . $row['id'] . '" class="btn-success">Approve</a>';
                            echo '<a href="?action=reject&id=' . $row['id'] . '" class="btn-success">Reject</a>';
                        }
                        echo '<a href="?action=delete&id=' . $row['id'] . '" class="btn-danger">Delete</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="15" class="empty">No requests available.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
