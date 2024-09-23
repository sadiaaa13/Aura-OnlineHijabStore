<?php
include 'connection.php';
session_start();

$admin_id = $_SESSION['admin_name'];

if (!isset($admin_id)) {
    header('location:login.php');
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
}

if (isset($_POST['add_product'])) {

    $product_name = mysqli_real_escape_string($conn, $_POST['name']);
    $product_quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $product_price = mysqli_real_escape_string($conn, $_POST['price']);
    $product_detail = mysqli_real_escape_string($conn, $_POST['detail']);
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'img/' . $image;

    $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$product_name'") or die(
        'query failed');
    if (mysqli_num_rows($select_product_name) > 0) {
        $message[] = 'product name already exist';
    } else {
        $insert_product = mysqli_query($conn, "INSERT INTO `products` (`name` ,`product_quantity` , `price` , `product_detail` , `image` )
    VALUES ('$product_name', '$product_quantity', '$product_price', '$product_detail','$image')") or die('query failed');
        if ($insert_product) {
            if ($image_size > 2000000) {
                $message[] = 'image size is too large';
            } else {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'product added succesfully';
            }
        }
    }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $select_delete_image = mysqli_query($conn, "SELECT image FROM `products` WHERE id='$delete_id'") or
        die('query_failed');
    $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
    unlink('image/' . $fetch_delete_image['image']);

    mysqli_query($conn, "DELETE FROM `products` WHERE id='$delete_id' ") or die('query failed');
    mysqli_query($conn, "DELETE FROM `cart` WHERE pid='$delete_id' ") or die('query failed');
    mysqli_query($conn, "DELETE FROM `wishlist` WHERE pid='$delete_id' ") or die('query failed');

    header('location:admin_product.php');
}

if (isset($_POST['update_product'])) {
    $update_id = $_POST['update_id'];
    $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
    $update_quantity = mysqli_real_escape_string($conn, $_POST['update_quantity']);
    $update_price = mysqli_real_escape_string($conn, $_POST['update_price']);
    $update_detail = mysqli_real_escape_string($conn, $_POST['update_detail']);
    
    if (isset($_FILES['update_image']) && $_FILES['update_image']['error'] == 0) {
        $image_name = $_FILES['update_image']['name'];
        $image_tmp = $_FILES['update_image']['tmp_name'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_new_name = 'product_' . $update_id . '.' . $image_extension;
        $image_path = 'img/' . $image_new_name;

        move_uploaded_file($image_tmp, $image_path);
    } else {

        $image_query = mysqli_query($conn, "SELECT image FROM products WHERE id = '$update_id'") or die('query failed');
        $image_fetch = mysqli_fetch_assoc($image_query);
        $image_new_name = $image_fetch['image'];
    }
   
   $update_query = mysqli_query($conn, "UPDATE products SET 
        name = '$update_name',
        product_quantity = '$update_quantity',
        price = '$update_price',
        product_detail = '$update_detail',
        image = '$image_new_name'
        WHERE id = '$update_id'") or 
     die('query failed');
    if ($update_query) {
        move_uploaded_file($update_image_tmp_name, $update_image_folder);
        header('location:admin_product.php');
    }
}
?>

<style type="text/css">
    <?php
    include 'aura.css';
    ?>
</style>
<!DOCTYPE htmL>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.θ'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css'>
    <link rel='stylesheet' type='text/css' href='aura.css'>
    <title>admin pannel</title>
    <style>
    /* Chrome, Safari, Edge, Opera */
    .no-spinner::-webkit-outer-spin-button,
    .no-spinner::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    .no-spinner[type=number] {
        -moz-appearance: textfield;
    }
</style>
</head>

<body>
    <?php include 'admin_header.php';
    ?>
    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo

                '<div class="message">
                <span>' . $message . '</span>
                <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                </div> ';
        }
    }
    ?>
    <section class="add-products form-container">
        <h1 class='title' style="margin-top: 20px; color: #fff; font-size: 32px; font-weight: 400;">Products Addition</h1>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="input-field">
                <label>product name</label>
                <input type=" text" name="name" required>
            </div>
            <div class="input-field">
                <label>product quantity</label>
                <input type="number" name="quantity" min="0" required>
            </div>
            <div class="input-field">
                <label>product price</label>
                <input type="number" name="price" required min="1" step="0.01" placeholder="Enter the price" class="no-spinner">
            </div>
            <div class="input-field">
                <label>product detail</label>
                <textarea name="detail" required></textarea>
            </div>
            <div class=" input-field">
                <label>product image</label>
                <input type="file" name="image" accept="image/jpg, image/jpeg, image/png, image/webp" required>
            </div>
            <input type="submit" name="add_product" value="add product" class="btn">
        </form>

    </section>
    <div class="line3"></div>
    <section class="show-products">
    <h1 class='title' style="margin-top: -40px; color:  #555; font-size: 32px; font-weight: 400;">Products</h1>
        <div class="box-container" style="margin-top: -10px;">
            <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `products` ") or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                    ?>
                    <div class="box">
                        <img src="img/<?php echo $fetch_products['image']; ?>">
                        <p>Price : 
                            <?php echo $fetch_products['price']; ?> Tk
                        </p>
                        <h4>
                            <?php echo $fetch_products['name']; ?>
                        </h4>
                        <details>
                            <?php echo $fetch_products['product_detail']; ?>
                        </details>
                        <a href="admin_product.php?edit=<?php echo $fetch_products['id']; ?>" class="edit">edit</a>
                        <a href="admin_product.php?delete=<?php echo $fetch_products['id']; ?>" class="delete" onclick="
                        return confirm('want to delete this product');">delete</a>

                    </div>
                    <?php
                }

            } else {
                echo '
<div class="empty">
<p>no products added yet!</p>
</div>
';
            }
            ?>
        </div>
    </section>
    <section class="update-container">
        <?php
        if (isset($_GET['edit'])) {
            $edit_id = $_GET['edit'];
            $edit_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$edit_id' ") or 
            die('query failed');
            if (mysqli_num_rows($edit_query) >0) {
                while ($fetch_edit = mysqli_fetch_assoc($edit_query)) {

                    ?>
                    <form method="POST" enctype="multipart/form-data">
                        <img src="img/<?php echo $fetch_edit['image']; ?> ">
                        <input type="hidden" name="update_id" value="<?php echo $fetch_edit['id']; ?> ">
                        <input type="text"  name="update_name" value="<?php echo $fetch_edit['name']; ?> ">
                        <input type="number" name="update_quantity" min="0" value="<?php echo $fetch_edit['product_quantity']; ?>">
                        <input type="number" name="update_price" min="1" value="<?php echo $fetch_edit['price']; ?>" step="0.01" class="no-spinner">
                        <textarea name="update_detail"><?php echo $fetch_edit['product_detail']; ?> </textarea>
                        <input type="file" name="update_image" accept="img/jpg, img/jpeg, img/png, img/webp">
                        <input type="submit" name="update_product" value="update" class="edit">
                        <input type="reset" name="" value="cancel" class="option-btn btn" id="close-form">
                    </form>
                    <?php
                }

            } echo "<script>document.querySelector('.update-container').style.display='block'</script>";
           
        }

        ?>
    </section>

    <div class='' style='height: 100vh;'></div>
    <script type='text/javascript' src="script.js"></script>
</body>

</html>