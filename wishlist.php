<?php
include 'connection.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}
if (isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
}

// Updating qty
if (isset($_POST['update_qty_btn'])) {
    $update_qty_id = $_POST['update_qty_id'];
    $update_value = $_POST['variable'];
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
?>
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.θ'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css'>
    <link rel='stylesheet' href='main.css'>
    <title>My Wishlist</title>
</head>

<body>
<?php include 'header.php';?>
    <div style="background: linear-gradient(to top, #ff98bc, #fff); margin-top:100px; padding:20px; margin-bottom:0px">
        <h1 style="font-size: 40px; color: #ff98bc; font-weight:400; margin-top:50px">Products Added in Wishlist</h1>

    <section class="shop">
        <?php
        if (isset($message)) {
            foreach ($message as $message) {
                echo '
                <div class="message">
                    <span>' . $message . '</span>
                    <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                </div>';
            }
        }
        ?>

<div class='box-container'>
            <?php
            $grand_total = 0;
            $select_wishlist = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE `user_id`='$user_id'") or die('query failed');
            if (mysqli_num_rows($select_wishlist) > 0) {
                while ($fetch_wishlist = mysqli_fetch_assoc($select_wishlist)) {
            ?>
                    <form method="post" class="box">
                        <img src="img/<?php echo $fetch_wishlist['image']; ?>">
                        <div class='price'><?php echo $fetch_wishlist['price']; ?>/- </div>
                      
                        <div class='name'><?php echo $fetch_wishlist['name']; ?></div>

                        <input type="hidden" name="product_id" value="<?php echo $fetch_wishlist['id']; ?>">
                        <input type="hidden" name="product_name" value="<?php echo $fetch_wishlist['name']; ?>">
                        <input type="hidden" name="product_price" value="<?php echo $fetch_wishlist['price']; ?>">
                        <input type="hidden" name="product_image" value="<?php echo $fetch_wishlist['image']; ?>">

                        <div class="icon">
                            <a href="view_page.php?pid=<?php echo $fetch_wishlist['id']; ?>" class="bi bi-eye-fill"></a>
                            <a href="wishlist.php?delete=<?php echo $fetch_wishlist['id']; ?>" class="bi bi-x" onclick="return confirm('Do you want to delete this product from your wishlist?')"></a>
                            <button type="submit" name="add_to_cart" class="bi bi-cart"></button>
                        </div>
                    </form>

            <?php
                    $grand_total += (float)$fetch_wishlist['price'];
                }
            } else {
                echo '<p class="empty">No products added yet!</p>';
            }
            ?>
        </div>

        <div class='wishlist_total'>
        <p style="margin-bottom:30px;">Total amount payable: <span><?php echo $grand_total; ?>/-</span></p>
            <a href='shop.php' class='btn'>Continue Shopping</a>
            <a href="wishlist.php?delete_all" class="btn <?php echo ($grand_total) ? '' : 'disabled'; ?>" onclick="return confirm('Do you want to delete all items in your wishlist?')">Delete All</a>
        </div>
    </section>
   </div>
   
    <script type='text/javascript' src='script.js'></script>
    <?php include 'footer.php' ;?>
</body>

</html>
   