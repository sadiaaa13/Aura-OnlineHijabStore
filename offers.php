<?php
include 'connection.php';
session_start();

$user_id = $_SESSION['user_id'];
$user_id2 = $_SESSION['user_name'];

if(isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
}

$sql = "SELECT * FROM offers WHERE valid_to >= CURDATE()";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>Offers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
        }

        .offers-container {
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 50px;
        }

        .offer {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            width: 300px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .offer h2 {
            margin-top: 0;
        }

        .offer p {
            margin: 5px 0;
        }

        .product-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .product-card {
            padding: 10px;
            text-align: center;
            width: 100px;
        }

        .product-card img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }

        .product-card p {
            font-size: 14px;
            color: black;
            text-decoration: none;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<section class="form-container1" style="background: linear-gradient(to bottom, #8d7968, #bab8b1); padding:20px; margin-top:-20px;">
    <h1 style="color: #3e3f3e; font-size: 32px; margin-top:80px;">Ongoing  Offers</h1>
    <div class="offers-container">
        <?php
        if ($result->num_rows > 0) {
            while($offer = $result->fetch_assoc()) {
                echo "<div class='offer'>";
                echo "<h2>" . htmlspecialchars($offer['title']) . "</h2>";
                echo "<p>" . htmlspecialchars($offer['description']) . "</p>";
                echo "<p>Discount: " . htmlspecialchars($offer['discount']) . "%</p>";
                echo "<p>Valid From: " . htmlspecialchars($offer['valid_from']) . " To: " . htmlspecialchars($offer['valid_to']) . "</p>";

                // Get product IDs
                $product_ids = explode(',', $offer['product_ids']);
                
                if (!empty($product_ids)) {
                    echo "<h3>Products:</h3>";
                    echo "<div class='product-list'>";
                    
                    // Fetch and display products
                    foreach ($product_ids as $product_id) {
                        $product_id = mysqli_real_escape_string($conn, $product_id);
                        $product_sql = "SELECT id, image, name FROM products WHERE id = '$product_id'";
                        $product_result = $conn->query($product_sql);
                        
                        if ($product_result->num_rows > 0) {
                            while ($product = $product_result->fetch_assoc()) {
                                $product_url = "product.php?id=" . htmlspecialchars($product['id']);
                                echo "<div class='product-card'>";
                                echo "<a href='$product_url'><img src='img/" . htmlspecialchars($product['image']) . "' alt='Product Image'></a>";
                                echo "<a href='$product_url'><p>" . htmlspecialchars($product['name']) . "</p></a>";
                                echo "</div>";
                            }
                        }
                    }
                    
                    echo "</div>";
                } else {
                    echo "<p>No products for this offer.</p>";
                }

                echo "</div>";
            }
        } else {
            echo "<p>No current offers available.</p>";
        }
        ?>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>

<?php
$conn->close();
?>
