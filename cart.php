<?php
include 'connection.php';
include 'CartManager.php';
session_start();

$user_id = $_SESSION['user_id'];
$user_id2 = $_SESSION['user_name'];


if(!isset($user_id2)){
    header('location:login.php');
}

if(isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
}


$cartManager = new CartManager($conn);

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['id'];
    $product_image = $_POST['image'];
    $product_name = $_POST['name'];
    $product_price = $_POST['price'];
    $product_quantity = $_POST['cart_quantity'];

    $check_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE `pid`='$product_id' AND `user_id`='$user_id'") or die('query failed');

    $product_query = mysqli_query($conn, "SELECT * FROM `products` WHERE `id`='$product_id'");
    if ($product_query && mysqli_num_rows($product_query) > 0) {
        $product = mysqli_fetch_assoc($product_query);
        $cartManager->addToCart($user_id, $product_id, $product['name'], $product['price'], 1, $product['image']);
    }

    if (mysqli_num_rows($check_cart) > 0) {
        $message[] = 'Product already added to cart';
    } else {
        mysqli_query($conn, "INSERT INTO `cart`(`user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES ('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
        $message[] = 'Product added to cart';
    }
}

// Updating quantity
if (isset($_POST['update_qty_btn'])) {
    $product_id = $_POST['pid'];
    $quantity = $_POST['quantity'];

    $update_qty_id = $_POST['update_qty_id'];
    $update_value = $_POST['update_qty'];

    $cartManager->updateQuantity($user_id, $product_id, $quantity);

    mysqli_query($conn, "UPDATE `cart` SET quantity='$update_value' WHERE id='$update_qty_id'") or die('query failed');
    header('location:cart.php');
}

// Deleting product from cart
if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id='$delete_id'") or die('query failed');
    $cartManager->deleteItem($user_id, $product_id);
    header('location:cart.php');
}

// Deleting all products from cart
if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id='$user_id'") or die('query failed');
    header('location:cart.php');
}

// Moving product from wishlist to cart
if (isset($_GET['move_to_cart'])) {
    $product_id = $_GET['move_to_cart'];

    // Fetch product details from the wishlist table
    $wishlist_query = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE `pid`='$product_id' AND `user_id`='$user_id'") or die('query failed');
    
    if (mysqli_num_rows($wishlist_query) > 0) {
        $fetch_wishlist = mysqli_fetch_assoc($wishlist_query);
        $product_image = $fetch_wishlist['image'];
        $product_name = $fetch_wishlist['name'];
        $product_price = $fetch_wishlist['price'];
        $product_quantity = 1; // Default quantity to add to cart

        // Check if the product is already in the cart
        $check_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE `pid`='$product_id' AND `user_id`='$user_id'") or die('query failed');
        
        if (mysqli_num_rows($check_cart) > 0) {
            $message[] = 'Product already added to cart';
        } else {
            mysqli_query($conn, "INSERT INTO `cart`(`user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES ('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
            $message[] = 'Product added to cart';
        }
    } else {
        $message[] = 'Product not found in wishlist';
    }
}
$cart_items = $cartManager->getCartItems($user_id);
?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css'>
    <link rel='stylesheet' type='text/css' href='main.css'>
    <title>Cart</title>
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

        .box-container.card .button:hover {
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
        <h1 style="font-size: 32px; color:#3e3f3e; font-weight:400; text-align:center; margin-top:100px">Your Shopping Cart</h1>

<section class="shop">
    <?php
    if (isset($message)) {

        foreach ($message as $msg) {
            echo '<div class="message">
                <span>' . $msg . '</span>
                <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
            </div>';
        }
    }
    ?>
    
    <section class='message-container'>
        <div class='box-container'>
        <?php
            // Fetching all available offers from the offers table
            $offers_query = mysqli_query($conn, "SELECT * FROM `offers` WHERE `valid_to` >= CURDATE()") or die('query failed');
            $offers = array();

            while ($row = mysqli_fetch_assoc($offers_query)) {
                // Extract product IDs and discount from the offers
                $offer_product_ids = explode(',', $row['product_ids']);
                $offers[] = [
                    'product_ids' => $offer_product_ids,
                    'discount' => (float)$row['discount']
                ];
            }

            // Displaying products in the cart
            $grand_total = 0;
            $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id='$user_id'") or die('query failed');

            if (mysqli_num_rows($select_cart) > 0) {
                while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                    $original_price = (float)$fetch_cart['price'];
                    $discounted_price = $original_price; // Default to original price

                    // Check if the product is part of any offer
                    foreach ($offers as $offer) {
                        if (in_array($fetch_cart['pid'], $offer['product_ids'])) {
                            $discount_percentage = $offer['discount'];
                            $discounted_price = $original_price - ($original_price * ($discount_percentage / 100));
                            break; // If matched, no need to check other offers
                        }
                    }

                    // Calculate total price based on quantity
                    $total_amt = $discounted_price * (int)$fetch_cart['quantity'];
                    $grand_total += $total_amt;
            ?>
                    <div class="card">
                        <img style="margin-top:20px;" src="img/<?php echo $fetch_cart['image']; ?>">
                        
                        <!-- Display price -->
                        <div class="price" style="margin-left: 100px; color:#3e3f3e;">
                            <?php 
                                if ($discount_percentage > 0) {
                                    echo "$discounted_price/-"; // Show discounted price
                                } else {
                                    echo "$original_price/-"; // Show original price if no discount
                                }
                            ?>
                        </div>

                        <div class="name"><?php echo $fetch_cart['name']; ?></div>
                        <form method="post">
                            <input type="hidden" name="update_qty_id" value="<?php echo $fetch_cart['id']; ?>">
                            <div class="qty">
                                <input type="number" min="1" name="update_qty" value="<?php echo $fetch_cart['quantity']; ?>">
                                <input class="button" style="align-content: center;" type="submit" name="update_qty_btn" value="update">
                            </div>
                        </form>
                        <div class="total-amt">
                            Total <span><?php echo $total_amt; ?>/-</span>
                        </div>
                        <div class="icon">
                            <a href="product.php?id=<?php echo $fetch_cart['pid']; ?>" class="bi bi-eye-fill"></a>
                            <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="bi bi-x" onclick="return confirm('Do you want to delete this product from your cart?')"></a>
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
            <a href="cart.php?delete_all" class="button" onclick="return confirm('Do you want to delete all items from your cart?')">Delete All</a>
        </div>

        <div class="cart_total">
            <p style="margin-top:20px; margin-bottom:30px; text-align: center; font-size: 24px;">Total amount payable: <span><?php echo $grand_total; ?>/-</span></p>
        </div>

        <div class="pay" style="gap: 40px">
            <a href="shop.php" class="button">Continue Shopping</a>
            <a href="checkout.php" class="button <?php echo ($grand_total > 0) ? '' : 'disabled'; ?>" onclick="return confirm('Proceed to checkout?')">Proceed to Checkout</a>
        </div>
    </section>
</section>

    </div>

    <script type="text/javascript" src="script.js"></script>
    <?php include 'footer.php'; ?>
</body>

</html>
