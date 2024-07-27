<?php
include 'connection.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

// Adding product to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['id'];
    $product_image = $_POST['image'];
    $product_name = $_POST['name'];
    $product_price = $_POST['price'];
    $product_quantity = $_POST['cart_quantity'];

    $check_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE `pid`='$product_id' AND `user_id`='$user_id'") or die('query failed');

    if (mysqli_num_rows($check_cart) > 0) {
        $message[] = 'Product already added to cart';
    } else {
        mysqli_query($conn, "INSERT INTO `cart`(`user_id`, `pid`, `name`, `price`, `quantity`, `image`, `cart_quantity`) VALUES ('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image', '$product_quantity')") or die('query failed');
        $message[] = 'Product added to cart';
    }
}

// Updating quantity
if (isset($_POST['update_qty_btn'])) {
    $update_qty_id = $_POST['update_qty_id'];
    $update_value = $_POST['update_qty'];

    mysqli_query($conn, "UPDATE `cart` SET cart_quantity='$update_value' WHERE id='$update_qty_id'") or die('query failed');
    header('location:cart.php');
}

// Deleting product from cart
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id='$delete_id'") or die('query failed');
    header('location:cart.php');
}

// Deleting all products from cart
if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id='$user_id'") or die('query failed');
    header('location:cart.php');
}

?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css'>
    <link rel='stylesheet' type='text/css' href='main.css'>
    <title>Cart</title>
</head>

<body>
    <div class="banner">
        <div class="detail">
            <h1>Cart</h1>
            <a href="index.php">home</a><span>/cart</span>
        </div>
    </div>
    <div class="line"></div>
    <section class="shop">
        <h1 class="title">Products added in cart</h1>
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
        <div class='line4'></div>
        <section class='message-container'>
            <h1 class='title'>Total user account</h1>
            <div class='box-container'>
                <?php
                $grand_total = 0;
                $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id='$user_id'") or die('query failed');
                if (mysqli_num_rows($select_cart) > 0) {
                    while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                        $total_amt = $fetch_cart['price'] * $fetch_cart['cart_quantity'];
                        $grand_total += $total_amt;
                ?>
                        <div class="box">
                            <div class="icon">
                                <a href="view_page.php?pid=<?php echo $fetch_cart['pid']; ?>" class="bi bi-eye-fill"></a>
                                <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="bi bi-x" onclick="return confirm('Do you want to delete this product from your cart?')"></a>
                            </div>
                            <img src="img/<?php echo $fetch_cart['image']; ?>">
                            <div class="price"><?php echo $fetch_cart['price']; ?>/-</div>
                            <div class="name"><?php echo $fetch_cart['name']; ?></div>
                            <form method="post">
                                <input type="hidden" name="update_qty_id" value="<?php echo $fetch_cart['id']; ?>">
                                <div class="qty">
                                    <input type="number" min="1" name="update_qty" value="<?php echo $fetch_cart['cart_quantity']; ?>">
                                    <input type="submit" name="update_qty_btn" value="update">
                                </div>
                            </form>
                            <div class="total-amt">
                                Total Amount: <span><?php echo $total_amt; ?>/-</span>
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
                <a href="cart.php?delete_all" class="btn2" onclick="return confirm('Do you want to delete all items in your cart?')">Delete All</a>
            </div>
            <div class="cart_total">
                <p>Total amount payable: <span><?php echo $grand_total; ?>/-</span></p>
                <a href="shop.php" class="btn">Continue Shopping</a>
                <a href="checkout.php" class="btn <?php echo ($grand_total > 0) ? '' : 'disabled'; ?>" onclick="return confirm('Proceed to checkout?')">Proceed to Checkout</a>
            </div>
        </section>
    </section>

    <script type="text/javascript" src="script.js"></script>
</body>

</html>
