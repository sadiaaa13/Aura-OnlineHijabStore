<?php
include 'connection.php';
session_start();
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

if (!isset($user_name)) {
    header('location:login.php');
    exit();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
    exit();
}

if (isset($_POST['wishlist_submit'])) {
    $product_id = $_POST['id'];
    $product_image = $_POST['image'];
    $product_name = $_POST['name'];
    $product_price = $_POST['price'];

    $product_query = mysqli_query($conn, "SELECT `product_quantity` FROM `products` WHERE `id`='$product_id'");
    if (!$product_query) {
        die('Query Failed: ' . mysqli_error($conn));
    }
    $product_data = mysqli_fetch_assoc($product_query);
    if ($product_data['product_quantity'] <= 0) {
        $_SESSION['message'] = "Sorry! The product can't be added to the wishlist as it's unavailable at this moment";
    } else {
        $wishlist_number = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE `id`='$product_id' AND `user_id`='$user_id'") or die('query failed');

        if (mysqli_num_rows($wishlist_number) > 0) {
            $_SESSION['message'] = 'Product already exists in wishlist';
        } else {
            mysqli_query($conn, "INSERT INTO `wishlist` (`user_id`, `id`, `image`, `name`, `price`) VALUES ('$user_id', '$product_id', '$product_image', '$product_name', '$product_price')") or die('query failed');
            $_SESSION['message'] = 'Product successfully added to your wishlist';
        }
    }

    header("Location: search.php?search_query=" . urlencode($_POST['search_query']));
    exit();
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

    $insert_product = mysqli_query($conn, "INSERT INTO `cart` (`user_id`, `id`, `cart_quantity`, `full_name`, `phone_number`, `delivery_address`, `amount`) VALUES ('$user_id', '$product_id', '$product_cart_quantity', '$full_name', '$product_phone_number', '$product_delivery_address', '$amount')");

    if (!$insert_product) {
        die('Query Failed: ' . mysqli_error($conn));
    }

    $update_query = mysqli_query($conn, "UPDATE `products` SET `product_quantity`='$product_quantity2' WHERE `id`='$product_id'");

    if (!$update_query) {
        die('Query Failed: ' . mysqli_error($conn));
    }

    header('location:order.php');
    exit();
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
        $product_quantity = $product['product_quantity'];

        if ($product_quantity <= 0) {
            $_SESSION['message'] = "Sorry! The product can't be added to the wishlist as it's unavailable at this moment";
        } else {
            $check_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE `pid`='$product_id' AND `user_id`='$user_id'");
            if (!$check_cart) {
                die('Query Failed: ' . mysqli_error($conn));
            }

            if (mysqli_num_rows($check_cart) > 0) {
                $_SESSION['message'] = 'Product already added to cart';
            } else {
                $insert_cart = mysqli_query($conn, "INSERT INTO `cart` (`user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES ('$user_id', '$product_id', '$product_name', '$product_price', 1, '$product_image')");
                if (!$insert_cart) {
                    die('Query Failed: ' . mysqli_error($conn));
                }
                $_SESSION['message'] = 'Product added to cart';
            }
        }
    } else {
        $_SESSION['message'] = 'Product not found';
    }

    header("Location: search.php?search_query=" . urlencode($_GET['search_query']));
    exit();
}

$search_message = '';
$products = [];
$search_query = '';

if (isset($_POST['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_POST['search_query']);
    
    if (empty($search_query)) {
        $search_message = '!!Please write something about what you are searching for!!';
    } else {
        $query = "SELECT * FROM `products` WHERE `name` LIKE '%$search_query%' OR `product_detail` LIKE '%$search_query%'";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            $search_message = 'Query failed: ' . mysqli_error($conn);
        } else {
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $products[] = $row;
                }
            } else {
                $search_message = 'No products found.';
            }
        }
    }
}

if (isset($_GET['search_query'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search_query']);
    
    if (!empty($search_query)) {
        $query = "SELECT * FROM `products` WHERE `name` LIKE '%$search_query%' OR `product_detail` LIKE '%$search_query%'";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            $search_message = 'Query failed: ' . mysqli_error($conn);
        } else {
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $products[] = $row;
                }
            } else {
                $search_message = 'No products found.';
            }
        }
    }
}

$messages = [];
if (isset($_SESSION['message'])) {
    $messages[] = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="main.css">
    <title>Search</title>
    <style>
        .search {
            background: linear-gradient(to bottom, #8d7968, #bab8b1);
            margin: 0;
            font-family: Arial, sans-serif;
        }

        header {
            height: 70px;
        }

        .section-title {
            text-align: center;
            padding-bottom: 30px;
        }

        .searchbar {
            padding: 10px 20px;
            color: #bab8b1;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            margin-top: 70px;
        }

        .searchbar form {
            display: flex;
            width: 100%;
            max-width: 800px;
        }

        .searchbar input[type="text"] {
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #bab8b1;
            border-radius: 4px;
            flex: 1;
        }

        .searchbar button {
            padding: 10px;
            width: 100px;
            border: none;
            border-radius: 4px;
            background-color: #000;
            color: white;
        }

        .searchbar button:hover {
            background-color: #8d7968;
        }

        .message {
            color: #8d7968;
            text-align: center;
            padding: 10px;
        }

        .icon a:hover {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <section class="search">
        <div class="searchbar">
            <form method="post" action="search.php">
                <input type="text" name="search_query" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search...">
                <button type="submit" name="search">Search</button>
            </form>
        </div>

        <?php if ($search_message): ?>
            <div class="message"><?php echo $search_message; ?></div>
        <?php endif; ?>

        <?php
        if ($messages) {
            foreach ($messages as $msg) {
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
            <?php foreach ($products as $product): ?>
                <div class="box">
                    <form method="post" action="search.php?search_query=<?php echo urlencode($search_query); ?>">
                        <img src="img/<?php echo $product['image']; ?>">
                        <h4 style="font-size: 15px; font-weight: 300;color: #333;"><?php echo $product['name']; ?></h4>
                        <h4 style="font-size: 15px; font-weight: 300;color: #333;">Price: <?php echo $product['price']; ?> Taka</h4>

                        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="image" value="<?php echo $product['image']; ?>">
                        <input type="hidden" name="name" value="<?php echo $product['name']; ?>">
                        <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                        <input type="hidden" name="search_query" value="<?php echo htmlspecialchars($search_query); ?>">

                        <div class="icon">
                            <a href="product.php?id=<?php echo $product['id']; ?>&search_query=<?php echo urlencode($search_query); ?>" class="bi bi-eye-fill"></a>
                            <button type="submit" name="wishlist_submit" onclick="return confirm('Want to wishlist this product?')">
                                <i class="bi bi-heart"></i>
                            </button>
                            <a href="search.php?cart=<?php echo $product['id']; ?>&search_query=<?php echo urlencode($search_query); ?>" class="bi bi-cart" onclick="return confirm('Want to cart this product?');"></a>
                        </div>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>