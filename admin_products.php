<?php
include 'config.php';
session_start();
$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location: login.php');
    exit;
}
$message = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_product'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $price = $_POST['price'];
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $author = mysqli_real_escape_string($conn, $_POST['author']);
        $book_type = mysqli_real_escape_string($conn, $_POST['book_type']);
        $image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];

        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'uploaded_img/' . $image;

        $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name='$name'") or die('query failed');
        if (mysqli_num_rows($select_product_name) > 0) {
            $message[] = 'Product name already added';
        } else {
            if ($image_size > 2000000) {
                $message[] = 'Image size is too large';
            } else {
                move_uploaded_file($image_tmp_name, $image_folder);
                $add_product_query = mysqli_query($conn, "INSERT INTO `products` (name, price, description, author, book_type, image) VALUES ('$name','$price', '$description', '$author', '$book_type', '$image')") or die('query failed');
                if ($add_product_query) {
                    $message[] = 'Product added successfully!';
                } else {
                    $message[] = 'Product could not be added!';
                }
            }
        }
    }

    if (isset($_POST['update_product'])) {
        $update_p_id = $_POST['update_p_id'];
        $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
        $update_price = $_POST['update_price'];
        $update_description = mysqli_real_escape_string($conn, $_POST['update_description']);
        $update_author = mysqli_real_escape_string($conn, $_POST['update_author']);
        $update_book_type = mysqli_real_escape_string($conn, $_POST['update_book_type']);
        $update_image = $_FILES['update_image']['name'];
        $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
        $update_image_size = $_FILES['update_image']['size'];
        $update_folder = 'uploaded_img/' . $update_image;
        $update_old_image = $_POST['update_old_image'];

        if (!empty($update_image)) {
            if ($update_image_size > 2000000) {
                $message[] = 'Image size is too large';
            } else {
                move_uploaded_file($update_image_tmp_name, $update_folder);
                unlink('uploaded_img/' . $update_old_image);
            }
        } else {
            $update_image = $update_old_image;
        }

        mysqli_query($conn, "UPDATE `products` SET name='$update_name', price='$update_price', description='$update_description', author='$update_author', book_type='$update_book_type', image='$update_image' WHERE id='$update_p_id'") or die('query failed');
        header('location: admin_products.php');
        exit;
    }

    if (isset($_GET['delete'])) {
        $delete_id = $_GET['delete'];
        $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id='$delete_id'") or die('query failed');
        $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
        $image_to_delete = 'uploaded_img/' . $fetch_delete_image['image'];
        unlink($image_to_delete);
        $delete_product_query = mysqli_query($conn, "DELETE FROM `products` WHERE id='$delete_id'") or die('query failed');
        header('location: admin_products.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .add-products {
            background: linear-gradient(to bottom, #1f4037, #99f2c8);
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .add-products .title {
            color: #fff;
            text-align: center;
            margin-bottom: 20px;
        }

        .add-products .box {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            border-radius: 5px;
        }

        .add-products .btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #319064;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }

        .add-products .btn:hover {
            background-color: #1f4037;
        }

        .show-products {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .show-products .box-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .show-products .box {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 200px;
        }

        .show-products .box img {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .show-products .box .name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .show-products .box .price {
            color: #319064;
            font-size: 18px;
            font-weight: bold;
        }

        .show-products .box .option-btn,
        .show-products .box .delete-btn {
            display: block;
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            text-decoration: none;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .show-products .box .author,
        .show-products .box .book-type,
        .show-products .box .description {
            font-size: 14px;
            margin-bottom: 5px;
            color: #555;
        }

        .show-products .box .option-btn {
            background-color: #319064;
        }

        .show-products .box .option-btn:hover {
            background-color: #1f4037;
        }

        .show-products .box .delete-btn {
            background-color: #e74c3c;
        }

        .show-products .box .delete-btn:hover {
            background-color: #c0392b;
        }

        .edit-product-form {
            display: none;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .edit-product-form img {
            max-width: 100px;
            margin-bottom: 10px;
        }

        .edit-product-form .box {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            border-radius: 5px;
        }

        .edit-product-form .option-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #319064;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }

        .edit-product-form .option-btn:hover {
            background-color: #1f4037;
        }

        .message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }

    </style>
</head>
<body>

<?php include 'admin_navbar.php'; ?>

<section class="container">
    <div class="add-products">
        <h1 class="title">Add New Product</h1>
        <?php
        if (!empty($message)) {
            foreach ($message as $msg) {
                echo '<p class="message">' . $msg . '</p>';
            }
        }
        ?>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="text" name="name" required placeholder="Product Name" class="box">
            <input type="number" name="price" required placeholder="Product Price" class="box">
            <textarea name="description" required placeholder="Product Description" class="box"></textarea>
            <input type="text" name="author" required placeholder="Author" class="box">
            <input type="text" name="book_type" required placeholder="Book Type" class="box">
            <input type="file" name="image" required class="box">
            <input type="submit" name="add_product" value="Add Product" class="btn">
        </form>
    </div>

    <div class="show-products">
        <div class="box-container">
            <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_product = mysqli_fetch_assoc($select_products)) {
                    ?>
                    <div class="box">
                        <img src="uploaded_img/<?php echo $fetch_product['image']; ?>" alt="">
                        <div class="name"><?php echo $fetch_product['name']; ?></div>
                        <div class="author">Author: <?php echo $fetch_product['author']; ?></div>
                        <div class="book-type">Type: <?php echo $fetch_product['book_type']; ?></div>
                        <div class="description"><?php echo $fetch_product['description']; ?></div>
                        <div class="price">₪<?php echo $fetch_product['price']; ?></div>
                        <a href="admin_products.php?edit=<?php echo $fetch_product['id']; ?>" class="option-btn">Edit</a>
                        <a href="admin_products.php?delete=<?php echo $fetch_product['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                    </div>
                    <?php
                }
            } else {
                echo '<p>No products added yet.</p>';
            }
            ?>
        </div>
    </div>

    <?php
    if (isset($_GET['edit'])) {
        $edit_id = $_GET['edit'];
        $edit_product_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id='$edit_id'") or die('query failed');
        if (mysqli_num_rows($edit_product_query) > 0) {
            $fetch_edit_product = mysqli_fetch_assoc($edit_product_query);
            ?>
            <div class="edit-product-form" id="edit-product-form">
                <h1 class="title">Edit Product</h1>
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="update_p_id" value="<?php echo $fetch_edit_product['id']; ?>">
                    <input type="text" name="update_name" value="<?php echo $fetch_edit_product['name']; ?>" required class="box">
                    <input type="number" name="update_price" value="<?php echo $fetch_edit_product['price']; ?>" required class="box">
                    <textarea name="update_description" required class="box"><?php echo $fetch_edit_product['description']; ?></textarea>
                    <input type="text" name="update_author" value="<?php echo $fetch_edit_product['author']; ?>" required class="box">
                    <input type="text" name="update_book_type" value="<?php echo $fetch_edit_product['book_type']; ?>" required class="box">
                    <input type="hidden" name="update_old_image" value="<?php echo $fetch_edit_product['image']; ?>">
                    <input type="file" name="update_image" class="box">
                    <img src="uploaded_img/<?php echo $fetch_edit_product['image']; ?>" alt="">
                    <input type="submit" name="update_product" value="Update Product" class="option-btn">
                </form>
            </div>
            <script>
                document.getElementById('edit-product-form').style.display = 'flex';
            </script>
            <?php
        }
    }
    ?>

</section>

</body>
</html>
