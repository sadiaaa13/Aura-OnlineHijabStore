<?php
include 'connection.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_POST['add_to_wishlist'])) {
    $product_id = $_POST['id'];
    $product_image = $_POST['image'];
    $product_name = $_POST['name'];
    $product_price = $_POST['price'];

    $check_cart = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE `pid`='$product_id' AND `user_id`='$user_id'") or die('query failed');

    if (mysqli_num_rows($check_cart) > 0) {
        $message[] = 'Product already added to Wishlist';
    } else {
        mysqli_query($conn, "INSERT INTO `wishlist`(`user_id`, `pid`, `name`, `price`, `image`) VALUES ('$user_id', '$product_id', '$product_name', '$product_price', '$product_image')") or die('query failed');
        $message[] = 'Product added to Wishlist';
    }
}

// Delete product from wishlist
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `wishlist` WHERE id = '$delete_id'") or die('query failed');
    header('location:wishlist.php');
}

// Delete all products from wishlist
if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `wishlist` WHERE user_id = '$user_id'") or die('query failed');
    header('location:wishlist.php');
}

// Add product to cart from wishlist
if (isset($_GET['move_to_cart'])) {
    $product_id = $_GET['move_to_cart'];

    // Fetch product details from the wishlist table
    $wishlist_query = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE id='$product_id' AND user_id='$user_id'") or die('query failed');
    if (mysqli_num_rows($wishlist_query) > 0) {
        $fetch_wishlist = mysqli_fetch_assoc($wishlist_query);
        $product_image = $fetch_wishlist['image'];
        $product_name = $fetch_wishlist['name'];
        $product_price = $fetch_wishlist['price'];
        $product_quantity = 1; // Default quantity to add to cart

        // Check if the product is already in the cart
        $check_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE pid='$product_id' AND user_id='$user_id'") or die('query failed');
        if (mysqli_num_rows($check_cart) > 0) {
            $message[] = 'Product already added to cart';
        } else {
            // Insert product into cart
            mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
            $message[] = 'Product added to cart';

            // Delete product from wishlist
            mysqli_query($conn, "DELETE FROM `wishlist` WHERE id='$product_id' AND user_id='$user_id'") or die('query failed');
        }
    } else {
        $message[] = 'Product not found in wishlist';
    }
}
?>
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css'>
    <link rel='stylesheet' type='text/css' href='main.css'>
    <title>Wishlist</title>
    <style>
        .container {
            width: 100%;
            max-width: 600px;
            margin: 100px auto 40px;
            padding: 20px;
            border-radius: 40px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .shop {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .message-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .box-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            width: 100%;
        }

        .card {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 300px;
            margin: 10px;
            padding: 20px;
            border-radius: 10px;
            background: #bab8b1;
            box-shadow: var(--box-shadow2);
            padding: 2rem;
            margin: 1rem;
            line-height: 2;
            text-transform: uppercase;
            position: relative;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .box-container .card img {
            width: 100%;
            height: 250px;
        }

        .box-container .card .icon {
            display: flex;
            padding: 0;
            justify-content: center;
            align-items: center;
            background: #8d7968;
            border-radius: 25px;
            gap: 10px;
            margin: 1rem;
        }

        .box-container .card .icon button,
        .box-container .card .icon a {
            padding: .8rem 2.5em;
            text-transform: uppercase;
            border-radius: 10px;
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #000;
            background: #bab8b1;
            margin: .5rem;
            box-shadow: var(--box-shadow);
        }

        .box-container .card .button:hover {
            background: #545c54;
            color: #bab8b1;
            text-decoration: none;
        }

        .button {
            padding: .8rem 2.5em;
            text-transform: uppercase;
            background: #8d7968;
            color: #bab8b1;
            border-radius: 10px;
            cursor: pointer;
        }

        .button:hover {
            text-decoration: none;
        }

        .icon a:hover {
            text-decoration: none;
        }

        .dlt,
        .cart_total,
        .pay {
            display: flex;
            justify-content: center;
            width: 100%;
            margin-top: 5px;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <div style="background: linear-gradient(to bottom, #8d7968, #bab8b1); margin-top:-20px; padding:20px;">
        <h1 style="font-size: 32px; color:#3e3f3e; font-weight:400; text-align:center; margin-top:100px">Products Added in Wishlist</h1>

        <section class="shop">
            <?php
            if (isset($message)) {
                foreach ($message as $msg) {
                    echo '
                    <div class="message">
                        <span>' . $msg . '</span>
                        <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                    </div>';
                }
            }
            ?>
            <section class='message-container'>
                <div class='box-container'>
                    <?php
                    $select_wishlist = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE `user_id`='$user_id'") or die('query failed');
                    if (mysqli_num_rows($select_wishlist) > 0) {
                        while ($fetch_wishlist = mysqli_fetch_assoc($select_wishlist)) {
                    ?>
                            <div class="card">
                                <img src="img/<?php echo $fetch_wishlist['image']; ?>">
                                <div class='price'><?php echo $fetch_wishlist['price']; ?>/- </div>
                                <div class='name'><?php echo $fetch_wishlist['name']; ?></div>
                                <form method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $fetch_wishlist['id']; ?>">
                                    <input type="hidden" name="product_name" value="<?php echo $fetch_wishlist['name']; ?>">
                                    <input type="hidden" name="product_price" value="<?php echo $fetch_wishlist['price']; ?>">
                                    <input type="hidden" name="product_image" value="<?php echo $fetch_wishlist['image']; ?>">
                                </form>
                                <div class="icon">
                                    <a href="product.php?id=<?php echo $fetch_wishlist['id']; ?>" class="bi bi-eye-fill"></a>
                                    <a href="wishlist.php?move_to_cart=<?php echo $fetch_wishlist['id']; ?>" class="bi bi-cart" onclick="return confirm('Want to add this product to the cart?');"></a>
                                    <a href="wishlist.php?delete=<?php echo $fetch_wishlist['id']; ?>" class="bi bi-x" onclick="return confirm('Do you want to delete this product from your wishlist?')"></a>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo '<p class="empty">No products added yet!</p>';
                    }
                    ?>
                </div>

                <div class="dlt">
                    <a href="wishlist.php?delete_all" class="button" onclick="return confirm('Do you want to delete all items from your Wishlist?')">Delete All</a>
                </div>
                <div class="pay" style="gap: 40px">
                    <a href="shop.php" class="button">Continue Shopping</a>
                </div>
            </section>
        </section>
    </div>

    <script type='text/javascript' src='script.js'></script>
    <?php include 'footer.php'; ?>
</body>

</html>