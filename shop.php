<?php

include 'connection.php';
session_start();
$user_id = $_SESSION['user_id'];
$user_id2 = $_SESSION['user_name'];

if (!isset($user_id2)) {
    header('location:login.php');
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
}

if (isset($_POST['wishlist_submit'])) {
    $product_id = $_POST['id'];
    $product_image = $_POST['image'];
    $product_name = $_POST['name'];
    $product_price = $_POST['price'];

    $wishlist_number = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE `id`='$product_id' AND `user_id`='$user_id'") or die('query failed');

    if (mysqli_num_rows($wishlist_number) > 0) {
        $message[] = 'Product already exists in wishlist';
    } else {
        mysqli_query($conn, "INSERT INTO `wishlist` (`user_id`, `id`, `image`, `name`, `price`) VALUES ('$user_id', '$product_id', '$product_image', '$product_name', '$product_price')") or die('query failed1');
        $message[] = 'Product successfully added to your wishlist';
    }
}

if (isset($_POST['buy_now'])) {
    $product_price = mysqli_real_escape_string($conn, $_POST['price']);
    $product_cart_quantity = mysqli_real_escape_string($conn, $_POST['cart_quantity']);
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $product_phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $product_delivery_address = mysqli_real_escape_string($conn, $_POST['delivery_address']);
    $product_quantity = mysqli_real_escape_string($conn, $_POST['product_quantity']);
    $product_quantity2 = $product_quantity - $product_cart_quantity;
    $amount = $product_price * $product_cart_quantity;

    $insert_product = mysqli_query($conn, "INSERT INTO `cart` (`user_id`, `id`, `cart_quantity`, `full_name`, `phone_number`, `delivery_address`, `amount`)
        VALUES ('$user_id', '$product_id', '$product_cart_quantity', '$full_name', '$product_phone_number', '$product_delivery_address', '$amount')") or die('query failed');

    $update_query = mysqli_query($conn, "UPDATE `products` SET `product_quantity`='$product_quantity2' WHERE `id`='$product_id'") or die('query failed');

    header('location:order.php');
}

?>
<style type="text/css">
    <?php
    include 'main.css';
    ?>
</style>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="main.css" />
    <title>Home Page</title>

    <style>
        body {
            margin-top: 30px;
            font-family: Arial, sans-serif;
        }

        .section-title {
            text-align: center;
            padding-bottom: 30px;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <section class="form-container1">
        <h1>Your Desired Hijab Collection</h1>
        <?php
        if (isset($message)) {
            foreach ($message as $message) {
                echo '
                    <div class="message">
                        <span>' . $message . '</span>
                        <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                    </div>
                ';
            }
        }
        ?>

        <div class="box-container">
            <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `products` ORDER BY id DESC") or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
            ?>

                    <div class="box">
                        <form method="post" action="shop.php">
                            <img src="img/<?php echo $fetch_products['image']; ?>">
                            <h4 style="font-size: 15px; font-weight: 300;color: #333;"><?php echo $fetch_products['name']; ?></h4>
                            <h4 style="font-size: 15px; font-weight: 300;color: #333;">Price: <?php echo $fetch_products['price']; ?> Taka</h4>

                            <input type="hidden" name="id" value="<?php echo $fetch_products['id']; ?>">
                            <input type="hidden" name="image" value="<?php echo $fetch_products['image']; ?>">
                            <input type="hidden" name="name" value="<?php echo $fetch_products['name']; ?>">
                            <input type="hidden" name="price" value="<?php echo $fetch_products['price']; ?>">

                            <div class="icon">
                                <a href="product.php?id=<?php echo $fetch_products['id']; ?>" class="bi bi-eye-fill"></a>
                                <button type="submit" name="wishlist_submit" onclick="return confirm('Want to wishlist this product?')">
                                    <i class="bi bi-heart"></i>
                                </button>
                                <a href="shop.php?cart=<?php echo $fetch_products['id']; ?>" class="bi bi-cart" onclick="return confirm('Want to cart this product?');"></a>
                            </div>
                        </form>
                    </div>

            <?php
                }
            } else {
                echo '
                    <div class="empty">
                        <p>No products added yet!</p>
                    </div>
                ';
            }
            ?>
        </div>
    </section>

    <section class="update-container">
        <?php
        if (isset($_GET['view'])) {
            $edit_id = $_GET['view'];

            $edit_query = mysqli_query($conn, "SELECT * FROM `products` WHERE `product_id`='$edit_id'") or die('query failed');
            if (mysqli_num_rows($edit_query) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($edit_query)) {
        ?>

                    <form method="POST" enctype="multipart/form-data">
                        <img src="img/<?php echo $fetch_products['image']; ?>">

                        <h4><br><?php echo $fetch_products['name']; ?></h4>
                        <h4>Price: <?php echo $fetch_products['price']; ?> Taka</h4>
                        <p>Quantity: <?php echo $fetch_products['product_quantity']; ?></p>
                        <details>
                            Category: <?php echo $fetch_products['category']; ?><br>
                            Description: <?php echo $fetch_products['detail']; ?><br>
                        </details>

                        <input type="hidden" name="product_id" value="<?php echo $fetch_products['product_id']; ?>">
                        <div class="icon2">
                            <button type="submit" name="wishlist_submit" onclick="return confirm('Want to wishlist this product?')">
                                <i class="bi bi-heart"></i>
                            </button>
                            <a href="shop.php?cart=<?php echo $fetch_products['product_id']; ?>" class="bi bi-cart" onclick="return confirm('Want to cart this product?');"></a>
                            <a href="shop.php?" class="bi bi-x-lg"></a>
                        </div>
                    </form>
        <?php
                }
            }

            echo "<script>document.querySelector('.update-container').style.display='block'</script>";
        }
        ?>
    </section>

    <section class="update-container2">
        <?php
        if (isset($_GET['cart'])) {
            $edit_id = $_GET['cart'];
            $edit_query = mysqli_query($conn, "SELECT * FROM `products` WHERE product_id='$edit_id'") or die('query failed');

            if (mysqli_num_rows($edit_query) > 0) {
                while ($fetch_edit = mysqli_fetch_assoc($edit_query)) {
        ?>
                    <form method="POST" enctype="multipart/form-data">
                        <h1>Delivery Product And Customer Information</h1><br>

                        <img src="img/<?php echo $fetch_edit['image']; ?>">

                        <h4><br><?php echo $fetch_edit['name']; ?></h4>
                        <h4>Price: <?php echo $fetch_edit['price']; ?> Taka</h4>
                        <p>Quantity: <?php echo $fetch_edit['product_quantity']; ?></p>
                        <details>
                            Category: <?php echo $fetch_edit['category']; ?><br>
                            Description: <?php echo $fetch_edit['detail']; ?><br>
                        </details><br>

                        <input type="hidden" name="product_id" value="<?php echo $fetch_edit['product_id']; ?>">
                        <input type="hidden" name="price" value="<?php echo $fetch_edit['price']; ?>">
                        <input type="hidden" name="product_quantity" value="<?php echo $fetch_edit['product_quantity']; ?>">

                        <p style="margin-left: -76%;">Required Quantity: </p>
                        <input type="number" name="cart_quantity" min="1" max="<?php echo $fetch_edit['product_quantity']; ?>">
                        <p>------------------------Customer Information------------------------</p>
                        <input type="text" name="full_name" placeholder="Enter your name" class="box" required>
                        <input type="tel" name="phone_number" placeholder="Enter your phone number" class="box" required>
                        <textarea name="delivery_address" placeholder="Enter your address" class="box" required></textarea>

                        <div class="icon2">
                            <button type="submit" name="buy_now" onclick="return confirm('Want to buy this product?')">
                                <i class="bi bi-credit-card"></i>Buy Now
                            </button>
                            <a href="shop.php?" class="bi bi-x-lg"></a>
                        </div>
                    </form>
        <?php
                }
            }
            echo "<script>document.querySelector('.update-container2').style.display='block'</script>";
        }
        ?>
    </section>

    <script type="text/javascript" src="script.js"></script>
</body>

</html>
