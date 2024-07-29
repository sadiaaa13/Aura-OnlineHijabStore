<?php
include 'connection.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die('Error: User is not logged in.');
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Check if product ID is set in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Error: Product ID is not specified. Please ensure the URL contains the "id" parameter.');
}

// Fetch the product ID from the URL
$product_id = $_GET['id'];
echo "Product ID received: " . htmlspecialchars($product_id) . "<br>";

// Fetch product details from the database
$product_query = mysqli_query($conn, "SELECT * FROM `products` WHERE `id` = '$product_id'");

if (!$product_query) {
    die('Product Query failed: ' . mysqli_error($conn));
}

if (mysqli_num_rows($product_query) == 0) {
    die('Error: Product not found.');
}

$product = mysqli_fetch_assoc($product_query);

// Handle adding the product to the cart
if (isset($_GET['cart'])) {
    $cart_product_id = $_GET['cart']; // This is the product ID from the URL

    // Check if the product is already in the cart
    $check_cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE `pid` = '$cart_product_id' AND `user_id` = '$user_id'");

    if (mysqli_num_rows($check_cart_query) > 0) {
        $message[] = 'Product already added to cart';
    } else {
        // Fetch product details to add to cart
        $cart_product_query = mysqli_query($conn, "SELECT * FROM `products` WHERE `id` = '$cart_product_id'");
        $cart_product = mysqli_fetch_assoc($cart_product_query);

        $product_name = $cart_product['name'];
        $product_price = $cart_product['price'];
        $product_image = $cart_product['image'];
        $product_quantity = 1; // Default quantity to add to cart

        // Add product to cart
        $add_to_cart_query = mysqli_query($conn, "INSERT INTO `cart` (`user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES ('$user_id', '$cart_product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')");

        if ($add_to_cart_query) {
            $message[] = 'Product added to cart';
        } else {
            die('Failed to add product to cart: ' . mysqli_error($conn));
        }
    }
}

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="main.css">
    <title>Product Page</title>
    <style>
        .Product{
            height:max-content
            margin: 0;
            padding: 30px;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 100px auto 40px;
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

        .product-image {
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
    <section class="Product" style= "background: linear-gradient(to bottom, #8d7968,#bab8b1);">
    <div class="container">
        <div class="product-details">
            <img src="img/<?php echo $product['image']; ?>" alt="Product Image" class="product-image">
            <h1 class="product-name"><?php echo $product['name']; ?></h1>
            <p class="product-price"><?php echo $product['price']; ?> Taka</p>
            <p class="product-availability">Availability: <?php echo ($product['product_quantity'] > 0) ? 'In Stock' : 'Out of Stock'; ?></p>
            <p class="product-detail"><?php echo $product['product_detail']; ?></p>
            <div class="icon">
                <button type="submit" name="wishlist_submit" onclick="return confirm('Want to wishlist this product?')">
                    <i class="bi bi-heart"></i> 
                </button>
                <a href="product.php?id=<?php echo $product['id']; ?>&cart=<?php echo $product['id']; ?>" class="bi bi-cart" onclick="return confirm('Want to cart this product?');"></a>
            </div>
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
    <?php include 'footer.php'; ?>
</body>
</html>
