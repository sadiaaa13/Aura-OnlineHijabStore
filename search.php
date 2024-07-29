<?php
include 'connection.php';
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

$search_message = '';
$products = [];

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
        margin: 0;
        font-family: Arial, sans-serif;
      }
      header {
          height: 70px;
      }

      .searchbar {
          background: linear-gradient(to bottom, #8d7968, #bab8b1);
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
          color: #8d7968 ;
          text-align: center;
          padding: 10px;
      }

      .box-container {
          background: linear-gradient(to top, #8d7968, #bab8b1);;
          display: flex;
          flex-wrap: wrap;
          justify-content: center;
          padding: 20px;
          margin-top:;
      }

      .box {
        background: #bab8b1;
        box-shadow: var(--box-shadow2);
        width: 300px;
        padding: 2rem;
        margin: 1rem;
        text-align: center;
        border-radius: 10px;
        line-height: 2;
        text-transform: uppercase; 
        position: relative;
        transition: background-color 0.3s, box-shadow 0.3s;
      }

      .box img {
          max-width: 100%;
          border-radius: 8px;
      }

      .box h4 {
          font-size: 16px;
          color: #333;
      }

      .box .icon {
          margin-top: 10px;
      }

      .box .icon a,
      .box .icon button {
          background: none;
          border: none;
          cursor: pointer;
          font-size: 20px;
          margin: 0 5px;
          color: #333;
      }

      .box .icon a:hover,
      .box .icon button:hover {
          color: #8d7968;
      }

      .icon a {
        text-decoration: none; 
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
            <input type="text" name="search_query" placeholder="Search...">
            <button type="submit" name="search">Search</button>
        </form>
    </div>

    <?php if ($search_message): ?>
        <div class="message"><?php echo $search_message; ?></div>
    <?php endif; ?>

    <div class="box-container">
        <?php foreach ($products as $product): ?>
            <div class="box">
                <img src="img/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                <h4><?php echo $product['name']; ?></h4>
                <p>Price: <?php echo $product['price']; ?> Taka</p>
                <p><?php echo $product['product_detail']; ?></p>
                <div class="icon">
                <a href="product.php?id=<?php echo $product['id']; ?>&search_query=<?php echo urlencode($search_query); ?>" class="bi bi-eye-fill"></a>
                    <button type="submit" name="wishlist_submit" onclick="return confirm('Want to wishlist this product?')">
                        <i class="bi bi-heart"></i> 
                    </button>
                    <a href="shop.php?cart=<?php echo $product['id']; ?>" class="bi bi-cart" onclick="return confirm('Want to cart this product?');"></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="update-container">

 
    <?php
        if(isset($_GET['view'])) {
            $edit_id = $_GET['view'];
            
            $edit_query = mysqli_query($conn, "SELECT * FROM `products` WHERE `product_id`='$edit_id'")or die('query failed');
            if(mysqli_num_rows($edit_query)>0){
                while($fetch_products = mysqli_fetch_assoc($edit_query)){

                
            //}
    // }
    ?>


    <form method="POST" enctype="multipart/form-data">



    <img src="img/<?php echo $fetch_products['image']; 
                        ?>
            ">

            
            <h4><br><?php echo $fetch_products['name']; ?></h4>
            <h4>Price: <?php echo $fetch_products['price']; ?> Taka</h4>
            <p>Quantity: <?php echo $fetch_products['product_quantity']; ?></p>
            <details>
                
                Category: <?php echo $fetch_products['category']; ?><br>
                Description: <?php echo $fetch_products['detail']; ?><br>
            </details>

            <input type="hidden" name="product_id" value="<?php echo $fetch_products['product_id'];?>">
            <div class="icon2">
            
            <button type="submit" name="wishlist_submit" onclick="return confirm('Want to wishlist this product?')">
                    <i class="bi bi-heart"></i> 
                </button>
            <a href="shop.php?cart=<?php echo $fetch_products['product_id']; ?>"class="bi bi-cart" onclick="
                return confirm('want to cart this product');"></a>
                <a href="shop.php?"class="bi bi-x-lg"></a>
            </div>
    </form>
    <?php 
                }
            }
            
            echo "<script>document.querySelector('.update-container').style.display='block'</script>";
            
        }
    ?>  
    </section>

    <section class="update-container2">
    <?php

        if(isset($_GET['cart'])) {
            $edit_id = $_GET['cart'];
            $edit_query =mysqli_query($conn,"SELECT * FROM  `products`
            where product_id='$edit_id'
            ")or die('query failed');
            
            if(mysqli_num_rows($edit_query)>0){
                while($fetch_edit = mysqli_fetch_assoc($edit_query)){

                
            //}
    // }
    ?>
    <form method="POST" enctype="multipart/form-data">
    <h1>Delivery Product And Customer Information</h1><br>

        <img src="img/<?php echo $fetch_edit['image'];?>">
        

        <h4><br><?php echo $fetch_edit['name']; ?></h4>
            <h4>Price: <?php echo $fetch_edit['price']; ?> Taka</h4>
            <p>Quantity: <?php echo $fetch_edit['product_quantity']; ?></p>
            <details>
                
                Category: <?php echo $fetch_edit['category']; ?><br>
                Description: <?php echo $fetch_edit['detail']; ?><br>
            </details><br>

            

            
            
        <input type="hidden" name="product_id" value="<?php echo $fetch_edit['product_id'];?>">
        <input type="hidden" name="price" value="<?php echo $fetch_edit['price'];?>">
        <input type="hidden" name="product_quantity" value="<?php echo $fetch_edit['product_quantity'];?>">

        <p style="margin-left: -76%;">Required Quantity: </p>
        <input type="number" name="cart_quantity" min="1" max="<?php echo $fetch_edit['product_quantity']; ?>">
        <p>------------------------Customer Information------------------------</p>
        <p style="margin-left: -86%;">Full Name: </p>
        <input type="text" name="full_name" value="<?php echo $user_id2;?>">
        <p style="margin-left: -82%;">Phone Number: </p>
        <input type="text" name="phone_number"  >
        <p style="margin-left: -80%;">Delivery Address: </p>
        <textarea name="delivery_address" required></textarea>
        
        
        <button type="submit" name="buy_now" class="btn2"  onclick="return confirm('Want to buy this product?')">
        <i class="bi bi-cart-fill"></i>  Buy Now
        </button>
        <input type="reset" value="Cancel" class="btn2"  class="btn2" id="go-back" onclick="window.history.back(); ">
    </form>
    <?php 
                }
            }
            echo "<script>document.querySelector('.update-container2').style.display='block'</script>";
            
        }
    ?> 

    </section>

    <?php include 'footer.php'; ?>
</body>
</html>