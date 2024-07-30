<?php
include 'connection.php';
session_start();
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

if (!isset($user_name)) {
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
        $product_quantity = 1;

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

$product_id = $_GET['id'];
$product_query = mysqli_query($conn, "SELECT * FROM `products` WHERE `id`='$product_id'");
if (!$product_query) {
    die('Query Failed: ' . mysqli_error($conn));
}

$product = mysqli_fetch_assoc($product_query);

// Fetch similar products based on product detail keywords
$product_keywords = explode(' ', $product['product_detail']);
$keyword_conditions = array_map(function($keyword) {
    return "`product_detail` LIKE '%$keyword%'";
}, $product_keywords);

$similar_products_query = mysqli_query($conn, "SELECT * FROM `products` WHERE `id` != '$product_id' AND (" . implode(' OR ', $keyword_conditions) . ") LIMIT 3");

if (!$similar_products_query) {
    die('Similar Products Query failed: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css'>
    <link rel='stylesheet' type='text/css' href='main.css'>
    <title>Product Details</title>
    <style>
        .container {
            width: 100%;
            max-width: 600px;
            margin: 80px auto 40px;
            padding: 20px;
            border-radius: 40px;
            background-color: #fffffe;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .product-details {
            text-align: center;
            align-content: center;
            justify-content: center;
            border-bottom: 1px solid #ddd;
            border-radius: 20px;
            padding-bottom: 20px;
            background-color: rgb(141,121,104, 0.5);
        }

        .product-details img {
            margin-top: 20px;
            border-radius: 20px;
            max-width: 90%;
            height: auto;
        }

        .product-name {
            font-size: 24px;
            color: #333;
            margin: 10px 0;
        }

        .product-price {
            font-size: 20px;
            font-weight: 550;
            color: #8d7968;
            margin: 10px 0;
        }

        .product-availability {
            font-size: 16px;
            color: #777;
            margin: 10px 0;
        }

        .similar-products {
            background-color: #bab8b1;
            border-radius: 20px;
        }

        .similar-products h2 {
            margin-top: 20px;
            text-align: center;
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }

        .similar-product-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .similar-product {
            text-align: center;
            margin: 10px;
        }

        .similar-product img {
            max-width: 100px;
            height: auto;
            border: 2px solid #8d7968;
            border-radius: 10px;
        }

        .similar-product p {
            font-size: 16px;
            color: #333;
            margin: 10px 0 0;
        }

        .icon {
            display: flex;
            margin: 0 auto;
            padding: 0;
            justify-content: center; 
            align-items: center;
            width: 190px;
            height: 90px;
            background: #8d7968;
            border-radius: 25px;
            border: #8d7968;
            gap: 20px;
        }

        .icon a, .icon button {
            width: 60px; 
            height: 60px;
            border-radius: 30%;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #000;
            background: #bab8b1;
            margin: .5rem;
            box-shadow: var(--box-shadow);
            text-decoration: none; 
        }

        .icon a:hover {
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .container {
                margin-top: 60px;
            }

            .product-image {
                max-width: 80%;
                height: auto;
            }

            .product-name {
                font-size: 20px;
            }

            .product-price {
                font-size: 18px;
            }

            .product-availability {
                font-size: 14px;
            }

            .similar-products h2 {
                font-size: 24px;
            }

            .icon {
                width: 160px;
                height: 80px;
            }

            .icon a, .icon button {
                width: 50px;
                height: 50px;
            }
        }

        @media (max-width: 480px) {
            .container {
                width: 90%;
                padding: 10px;
            }

            .product-image {
                max-width: 100%;
                height: auto;
            }

            .product-name {
                font-size: 18px;
            }

            .product-price {
                font-size: 16px;
            }

            .product-availability {
                font-size: 12px;
            }

            .similar-products h2 {
                font-size: 20px;
            }
        }

            .icon {
                width: 140px;
                height: 70px;
                gap: 10px;
            }

            .icon a, .icon button {
                width: 40px;
                height: 40px;
            }
        
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="form-container1" style="background: linear-gradient(to bottom, #8d7968,#bab8b1); padding:20px; margin-top:-20px;">
        <h1 style="color: #3e3f3e; font-size: 32px; margin-top:100px;">Product Details</h1>
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
        <div class="container">
            <div class="product-details">
                <form method="post" action="product.php?id=<?php echo $product['id']; ?>">
                    <img src="img/<?php echo $product['image']; ?>">
                    <h4 style="font-size: 15px; font-weight: 300; color: #333;"><?php echo $product['name']; ?></h4>
                    <h4 style="font-size: 15px; font-weight: 300; color: #333;">Price: <?php echo $product['price']; ?> Taka</h4>

                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="image" value="<?php echo $product['image']; ?>">
                    <input type="hidden" name="name" value="<?php echo $product['name']; ?>">
                    <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                    <p class="product-availability">Availability: <?php echo ($product['product_quantity'] > 0) ? 'In Stock' : 'Out of Stock'; ?></p>
                    <p class="product-detail"><?php echo $product['product_detail']; ?></p>

                    <div class="icon">
                        <button type="submit" name="wishlist_submit" onclick="return confirm('Want to wishlist this product?')">
                            <i class="bi bi-heart"></i>
                        </button>
                        <a href="product.php?id=<?php echo $product['id']; ?>&cart=<?php echo $product['id']; ?>" class="bi bi-cart" onclick="return confirm('Want to cart this product?');"></a>
                    </div>
                </form>
            </div>
            <div class="similar-products">
                <h2>Similar Products</h2>
                <div class="similar-product-list">
                    <?php while ($similar_product = mysqli_fetch_assoc($similar_products_query)) { ?>
                    <div class="similar-product">
                        <a href="product.php?id=<?php echo $similar_product['id']; ?>">
                            <img src="img/<?php echo $similar_product['image']; ?>" alt="Similar Product">
                        </a>
                        <p><?php echo $similar_product['name']; ?></p>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript" src="script.js"></script>
    <?php include 'footer.php'; ?>
</body>
</html>
