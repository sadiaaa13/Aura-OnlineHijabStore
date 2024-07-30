<?php

include 'connection.php';
session_start();
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

if (!isset($user_name)) {
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
        VALUES ('$user_id', '$product_id', '$product_cart_quantity', '$full_name', '$product_phone_number', '$product_delivery_address', '$amount')");

    if (!$insert_product) {
        die('Query Failed: ' . mysqli_error($conn));
    }

    $update_query = mysqli_query($conn, "UPDATE `products` SET `product_quantity`='$product_quantity2' WHERE `id`='$product_id'");

    if (!$update_query) {
        die('Query Failed: ' . mysqli_error($conn));
    }

    header('location:order.php');
}

// Handle adding product to cart
if (isset($_GET['cart'])) {
    $product_id = $_GET['cart'];

    // Fetch product details from the database
    $product_query = mysqli_query($conn, "SELECT * FROM `products` WHERE `id`='$product_id'");
    if (!$product_query) {
        die('Query Failed: ' . mysqli_error($conn));
    }

    if (mysqli_num_rows($product_query) > 0) {
        $product = mysqli_fetch_assoc($product_query);
        $product_image = $product['image'];
        $product_name = $product['name'];
        $product_price = $product['price'];
        $product_quantity = 1; // Default quantity to add to cart

        $check_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE `pid`='$product_id' AND `user_id`='$user_id'");
        if (!$check_cart) {
            die('Query Failed: ' . mysqli_error($conn));
        }

        if (mysqli_num_rows($check_cart) > 0) {
            $message[] = 'Product already added to cart';
        } else {
            $insert_cart = mysqli_query($conn, "INSERT INTO `cart` (`user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES ('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')");
            if (!$insert_cart) {
                die('Query Failed: ' . mysqli_error($conn));
            }
            $message[] = 'Product added to cart';
        }
    } else {
        $message[] = 'Product not found';
    }
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
    <meta charset='UTF-8'>
     <meta name='viewport' content='width=device-width, initial-scale=1.θ'>
     <link  rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
     <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css'>
     <link rel='stylesheet' type='text/css' href='main.css'>
    <title>Shop</title>

    <style>
        body {
            margin-top: 30px;
            font-family: Arial, sans-serif;
        }

        .section-title {
            text-align: center;
            padding-bottom: 30px;
        }

        .icon a:hover {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <section class="form-container1" style="background: linear-gradient(to bottom, #8d7968,#bab8b1); padding:20px; margin-top:-20px;">
        <h1 style="color: #3e3f3e;; font-size: 32px; margin-top:70px;">Your Desired Hijab Collection</h1>
        <?php
        if (isset($message)) {
            foreach ($message as $msg) {
                echo '
                    <div class="message">
                        <span>' . $msg . '</span>
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
    <script type="text/javascript" src="script.js"></script>
    <?php include 'footer.php' ;?>
</body>

</html>