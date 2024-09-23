<?php
include 'connection.php';
session_start();

$admin_id = $_SESSION['admin_name'];

if (!isset($admin_id)) {
    header('location:login.php');
}

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
}

// Handle offer deletion
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    // Deleting the offer from database
    $delete_offer_query = mysqli_query($conn, "DELETE FROM offers WHERE id='$delete_id'") or die('query failed');

    if ($delete_offer_query) {
        // If there are any associated product images or data related to the offer, handle them here
        // (Assuming no separate image deletion logic is needed for offers, but add if required)
        header('location:admin_add_offer_products.php');
    } else {
        die('Failed to delete the offer');
    }
}

// Handle offer addition
if (isset($_POST['add_offer'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $discount = mysqli_real_escape_string($conn, $_POST['discount']);
    $valid_from = mysqli_real_escape_string($conn, $_POST['valid_from']);
    $valid_to = mysqli_real_escape_string($conn, $_POST['valid_to']);
    $product_ids = isset($_POST['product_ids']) ? explode(',', $_POST['product_ids']) : [];
    $product_ids_string = implode(',', $product_ids);

    $add_offer_query = mysqli_query($conn, "INSERT INTO offers (title, description, discount, valid_from, valid_to, product_ids, status) 
        VALUES ('$title', '$description', '$discount', '$valid_from', '$valid_to', '$product_ids_string', 'current')") or die('query failed');
    
    if ($add_offer_query) {
        header('location:admin_add_offer_products.php');
    } else {
        die('Failed to add the offer');
    }
}

// Handle offer updating
if (isset($_POST['update_offer'])) {
    $offer_id = mysqli_real_escape_string($conn, $_POST['offer_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $discount = mysqli_real_escape_string($conn, $_POST['discount']);
    $valid_from = mysqli_real_escape_string($conn, $_POST['valid_from']);
    $valid_to = mysqli_real_escape_string($conn, $_POST['valid_to']);
    $product_ids = isset($_POST['product_ids']) ? explode(',', $_POST['product_ids']) : [];
    $product_ids_string = implode(',', $product_ids);

    $update_offer_query = mysqli_query($conn, "UPDATE offers SET title='$title', description='$description', discount='$discount', valid_from='$valid_from', valid_to='$valid_to', product_ids='$product_ids_string' 
        WHERE id='$offer_id'") or die('query failed');
    
    if ($update_offer_query) {
        header('location:admin_add_offer_products.php');
    } else {
        die('Failed to update the offer');
    }
}

// Fetch offer for editing
$offer_to_edit = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $select_offer = mysqli_query($conn, "SELECT * FROM offers WHERE id='$edit_id'") or die('query failed');
    if (mysqli_num_rows($select_offer) > 0) {
        $offer_to_edit = mysqli_fetch_assoc($select_offer);
    }
}

// Fetch current and old offers
$select_offers = mysqli_query($conn, "SELECT * FROM offers WHERE status = 'current'") or die('query failed');
$select_old_offers = mysqli_query($conn, "SELECT * FROM offers WHERE status = 'old'") or die('query failed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="aura.css">
    <title>Offers Management</title>
    <style>
        .offer-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .offer-images {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .offer-images img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
        .offer-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .offer-actions {
            margin-top: 10px;
        }
        .product-tile {
            display: inline-block;
            margin: 10px;
        }
        .product-tile img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .product-tile img.selected {
            border: 2px solid green;
        }
        .no-border-button {
        text-transform: uppercase;
        background: #fff;
        border: none;
        cursor: pointer;
        width: 150px;
        padding: 0.5rem 0; 
        margin: 10px;
        outline: none; 
        box-shadow: none; 
    }

    .no-border-button:focus {
        outline: none; /* Ensure no outline when focused */
    }
    </style>
</head>
<body>
    <?php include 'admin_header.php'; ?>

    <section class="add-offer form-container">
        <h1 class='title' style="margin-top: 20px;"><?php echo $offer_to_edit ? 'Edit Offer' : 'Add New Offer'; ?></h1>
        <form action="" method="post">
            <input type="hidden" name="offer_id" value="<?php echo $offer_to_edit ? $offer_to_edit['id'] : ''; ?>">
            <input type="text" name="title" placeholder="Enter offer title" required class="box" value="<?php echo $offer_to_edit ? htmlspecialchars($offer_to_edit['title']) : ''; ?>">
            <textarea name="description" placeholder="Enter offer description" required class="box"><?php echo $offer_to_edit ? htmlspecialchars($offer_to_edit['description']) : ''; ?></textarea>
            <input type="number" name="discount" placeholder="Enter discount percentage" required class="box" value="<?php echo $offer_to_edit ? htmlspecialchars($offer_to_edit['discount']) : ''; ?>">
            <input type="date" name="valid_from" required class="box" value="<?php echo $offer_to_edit ? htmlspecialchars($offer_to_edit['valid_from']) : ''; ?>">
            <input type="date" name="valid_to" required class="box" value="<?php echo $offer_to_edit ? htmlspecialchars($offer_to_edit['valid_to']) : ''; ?>">

            <h2>Select Products</h2>
            <div class="product-selection">
                <?php
                $selected_products = $offer_to_edit ? explode(',', $offer_to_edit['product_ids']) : [];
                $select_products = mysqli_query($conn, "SELECT * FROM products") or die('query failed');
                if (mysqli_num_rows($select_products) > 0) {
                    while ($product = mysqli_fetch_assoc($select_products)) {
                        $selected = in_array($product['id'], $selected_products) ? 'selected' : '';
                        echo '<div class="product-tile">
                                <img src="img/' . htmlspecialchars($product['image']) . '" alt="Product Image" data-id="' . htmlspecialchars($product['id']) . '" class="' . $selected . '">
                                <p>Price: ' . htmlspecialchars($product['price']) . ' Tk</p>
                              </div>';
                    }
                } else {
                    echo '<p>No products available</p>';
                }
                ?>
            </div>
            <input type="hidden" name="product_ids" id="product_ids" value="<?php echo $offer_to_edit ? htmlspecialchars($offer_to_edit['product_ids']) : ''; ?>">
            <input type="submit" name="<?php echo $offer_to_edit ? 'update_offer' : 'add_offer'; ?>" value="<?php echo $offer_to_edit ? 'Update Offer' : 'Add Offer'; ?>" class="btn">
        </form>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectedProducts = new Set(<?php echo $offer_to_edit ? json_encode($selected_products) : '[]'; ?>);
            const productTiles = document.querySelectorAll('.product-tile img');
            const productIdsInput = document.getElementById('product_ids');

            productTiles.forEach(tile => {
                tile.addEventListener('click', function() {
                    const productId = this.getAttribute('data-id');
                    if (selectedProducts.has(productId)) {
                        selectedProducts.delete(productId);
                        this.classList.remove('selected');
                    } else {
                        selectedProducts.add(productId);
                        this.classList.add('selected');
                    }
                    productIdsInput.value = Array.from(selectedProducts).join(',');
                });
            });
        });
    </script>

    <!-- Display and manage offers -->
    <section class="add-offer form-container">
    <h1 class='title' style="margin-top: 20px; color: #fff; font-size: 32px; font-weight: 400;">Current Offers</h1>
    <?php
        if (mysqli_num_rows($select_offers) > 0) {
            while ($offer = mysqli_fetch_assoc($select_offers)) {
                // Get product IDs
                $product_ids = explode(',', $offer['product_ids']);
                
                // Fetch product details
                $images_html = '';
                foreach ($product_ids as $product_id) {
                    $product_id = mysqli_real_escape_string($conn, $product_id);
                    $select_products = mysqli_query($conn, "SELECT * FROM products WHERE id = '$product_id'") or die('query failed');
                    if ($product = mysqli_fetch_assoc($select_products)) {
                        $discounted_price = $product['price'] - ($product['price'] * ($offer['discount'] / 100));
                        $images_html .= '<div>
                                            <img src="img/' . $product['image'] . '" alt="Product Image">
                                            <p>Discounted Price: ' . number_format($discounted_price, 2) . ' Tk</p>
                                         </div>';
                    }
                }
                
                // Display offer
                echo '<div class="offer-card">';
                echo '<h3>' . htmlspecialchars($offer['title']) . '</h3>';
                echo '<p>Description: ' . htmlspecialchars($offer['description']) . '</p>';
                echo '<p>Discount: ' . htmlspecialchars($offer['discount']) . '%</p>';
                echo '<p><strong>Valid From:</strong> ' . htmlspecialchars($offer['valid_from']) . '</p>';
                echo '<p><strong>Valid To:</strong> ' . htmlspecialchars($offer['valid_to']) . '</p>';
                echo '<div class="offer-images">' . $images_html . '</div>';
                echo '<form action="admin_add_offer_products.php" method="get" style="border: none; padding: 0; margin: 0; background: none; display: inline;">
                        <input type="hidden" name="edit" value="' . $offer['id'] . '">
                        <button class="no-border-button" type="submit">Edit</button>
                    </form>';
                
                echo '<form action="admin_add_offer_products.php" method="get" style="border: none; padding: 0; margin: 0; background: none; display: inline;" onsubmit="return confirm(\'Are you sure you want to delete this offer?\')">
                        <input type="hidden" name="delete" value="' . $offer['id'] . '">
                        <button class="no-border-button" type="submit">Delete</button>
                    </form>';
        

                echo '</div>';
            }
        } else {
            echo '<p>No current offers available</p>';
        }
        ?>

        <h1 class='title' style="margin-top: 20px; color: #fff; font-size: 32px; font-weight: 400;">Old Offers</h1>
        <?php
        if (mysqli_num_rows($select_old_offers) > 0) {
            while ($offer = mysqli_fetch_assoc($select_old_offers)) {
                // Get product IDs
                $product_ids = explode(',', $offer['product_ids']);
                
                // Fetch product details
                $images_html = '';
                foreach ($product_ids as $product_id) {
                    $product_id = mysqli_real_escape_string($conn, $product_id);
                    $select_products = mysqli_query($conn, "SELECT * FROM products WHERE id = '$product_id'") or die('query failed');
                    if ($product = mysqli_fetch_assoc($select_products)) {
                        $discounted_price = $product['price'] - ($product['price'] * ($offer['discount'] / 100));
                        $images_html .= '<div>
                                            <img src="img/' . $product['image'] . '" alt="Product Image">
                                            <p>Discounted Price: ' . number_format($discounted_price, 2) . ' Tk</p>
                                         </div>';
                    }
                }
                
                // Display offer
                echo '<div class="offer-card">';
                echo '<h3>' . htmlspecialchars($offer['title']) . '</h3>';
                echo '<p>Description: ' . htmlspecialchars($offer['description']) . '</p>';
                echo '<p>Discount: ' . htmlspecialchars($offer['discount']) . '%</p>';
                echo '<div class="offer-images">' . $images_html . '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No old offers available</p>';
        }
        ?>
    </section>
</body>
</html>