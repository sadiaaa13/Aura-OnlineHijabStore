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
    if(isset($_POST['wishlist_submit'])){
        $product_id=$_POST['id'];
        

        $wishlist_number= mysqli_query($conn, "SELECT * FROM `wishlist`  where
        `id`='$id'") or die('query failed');

        if(mysqli_num_rows($wishlist_number)>0){
            $message[]='product already exist in wishlist';}
            
        else{
            mysqli_query($conn, "INSERT INTO `wishlist` (`user_id`,`id`)
                VALUES('$user_id','$product_id')") or die('query failed1');
                $message[]='product succesfully added in your wishlist';
                
                
        }

    }

    //adding products to database
    if(isset($_POST['buy_now']) ){


        //product_id full_name phone_number delivery_address cart_quantity

        $product_price= mysqli_real_escape_string($conn, $_POST['price']);
        $product_cart_quantity= mysqli_real_escape_string($conn, $_POST['cart_quantity']);

        $product_id= mysqli_real_escape_string($conn, $_POST['product_id']);
        $full_name= mysqli_real_escape_string($conn, $_POST['full_name']);
        $product_phone_number= mysqli_real_escape_string($conn, $_POST['phone_number']);
        $product_delivery_address= mysqli_real_escape_string($conn, $_POST['delivery_address']);
        $product_quantity= mysqli_real_escape_string($conn, $_POST['product_quantity']);

        $product_quantity2=$product_quantity-$product_cart_quantity;

        $amount = $product_price*$product_cart_quantity;

        $insert_product = mysqli_query($conn, "INSERT INTO `cart` (`user_id`,`id`,`cart_quantity`,`full_name`,`phone_number`, `delivery_address`,`amount`)
                VALUES('$user_id','$product_id','$product_cart_quantity','$full_name','$product_phone_number','$product_delivery_address','$amount ')") or die('query failed');


        $update_query = mysqli_query($conn, "UPDATE `products` SET `product_quantity`='$product_quantity2' WHERE  'id'='$product_id'")or die('query failed');
     

        header('location:order.php');
        
    }

?>

<!DOCTYPE htmL>
<html lang='en'>

<head>
<meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css'>
    <link rel='stylesheet' type='text/css' href='main.css'>
    <title>User</title>

    <style>
          .scrollable{
               height:500px;
               margin-top: -5px;
               background-color: #bab8b1;
          }
          .line{
               height:100px;
               background-color: #bab8b1;
          }
        .scrollable-images {
          margin-top: 20px;
            margin-left: 100px;
            width: 100%;
            overflow-x: auto;
            white-space: nowrap;
            padding: 20px 0;
        }

        .scrollable-images .product-item {
            display: inline-block;
            margin-right: 10px;
            text-align: center;
            vertical-align: top; 
        }

        .scrollable-images .product-item img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 5px; /* Optional: add border-radius for rounded corners */
        }
    </style>
</head>
 
<body>
<?php include 'header.php';?>
    <!--------------------------------------home slider------------------------------->
    <div class="container-fluid">
          <div class="hero-section">
               <img src="img/slider.png">
               <div class="hero-caption">
                    <h1>Welcome to AURA</h1>
                    <p>Discover amazing products and enjoy exclusive offers!</p>
                    <a href="shop.php" class="btn">Shop Now</a>
               </div>
          </div>
     </div>
     
     <div class="scrollable">
          <div class="line"></div>
          <h1 style="font-size: 40px; color: #8d7968; margin-left:100px;">Explore Products</h1>
          <div class="scrollable-images">
            <?php
            // Database connection
            $servername = "localhost"; // Replace with your database server name
            $username = "root"; // Replace with your database username
            $password = ""; // Replace with your database password
            $dbname = "shop_db"; // Replace with your database name

            $conn = mysqli_connect('localhost', 'root', '', 'shop_db');

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            $select_products = mysqli_query($conn, "SELECT * FROM `products` ORDER BY id DESC") or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                    
                    $imagePath = 'img/' . $fetch_products['image'];
                    $productID = $fetch_products['id'];

                    echo '<div class="product-item">';
                    echo '<a href="product.php?id=' . $productID . '">';
                    echo '<img src="' . $imagePath . '" alt="Product Image">';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo "No products found.";
            }

            $conn->close();
            ?>
        </div>
    </div>

     <div class="services">
               <span style="font-size: 40px; color: #8d7968; margin-left:100px; margin-top:50px">Services</span>
               <div class="row">
                    <div class="box">
                         <img src="img/0.png">
                         <div>
                              <h1>Free Shipping Fast</h1>
                              <p>Aura Aura Aura Aura Aura Aura Aura Aura Aura.</p>
                         </div>
                    </div>
                    <div class="box">
                         <img src="img/1.png">
                         <div>
                              <h1>Good Quality Product</h1>
                              <p>Aura Aura Aura Aura Aura Aura Aura Aura Aura.</p>
                         </div>
                    </div>
                    <div class="box">
                         <img src="img/2.png">
                         <div>
                              <h1>Online Support 24/7</h1>
                              <p>Aura Aura Aura Aura Aura Aura Aura Aura Aura.</p>
                         </div>
                     </div>
               </div>
          </div>
          <div class="story">
               <div class="row align-items-center clearfix" style=" margin-left: -40px; margin-right: -20px;">
                    <div class="box col-md-8" >     
                         <span style="color: #bab8b1">Our Story</span>
                         <h1>Beginning of this Journey</h1>
                         <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                         <a href="shop.php" class="btn">shop now</a>
                    </div>
                    <div class="box col-md-4">
                         <img src="img/download (1).jpg">
                    </div>
               </div>
          </div>
          <div class="discover">
               <div class="detail">
                    <h1 class="title" style="color:#8d7968; font: size 40px; font-weight:400">Light Weight & Comfortable Hijab</h1> <span>Buy 3 And Get 30% Off!</span>
                    <p>lorem Ipsum Is Simply Dummy Text Of The Printing And Typesetting Industry. Lorem Ipsum Has Been The Industry's
                    Standard Dummy Text Ever Since The 1500s, when An Unknown Printer Took A Galley of Type And Scrambled It To Make A Type Specimen Book.</p>
                    <a href="shop.php" class="btn">Discover Offer</a>
               </div>
               <div class="img-box">
                    <img src="img/slider.jpg">
               </div>
          </div>

     <?php include 'footer.php' ;?>
     
     <script type='text/javascript' src='mainscript.js'></script>
</body>
</html>