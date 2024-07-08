<?php
include 'connection.php';
session_start();

$admin_id = $_SESSION['user_name'];

if (!isset($admin_id)) {
     header('location:login.php');
}

if (isset($_POST['logout'])) {
     session_destroy();
     header('location:login.php');
}

if (isset($_POST['add_to_wishlist'])) {
     $product_id = $_POST['product_id'];
     $product_name = $_POST['product_name']; 
     $product_price = $_POST['product_price']; 
     $product_image = $_POST['product_image'];

     $wishlist_number = mysqli_query($conn, "SELECT * FROM wishlist WHERE name = '$product_name' AND user_id ='$id'") or die('query failed');
     $cart_num = mysqli_query($conn, "SELECT * FROM cart WHERE name = '$product_name' AND user_id ='$id'")
          or die('query failed');
     if (mysqli_num_rows($wishlist_number)>0) {
          $message[]='product already exist in wishlist';
     }else if (mysqli_num_rows($cart_num)>0) {
          $message[] = 'product already exist in cart';
     }else{
          mysqli_query($conn, "INSERT INTO wishlist (user_id,pid,name,price,image) VALUES ('$user_id', '$ product_id', '$product_name', '$product_price', '$product_image')");
          $message[] = 'product successfuly added in your wishlist';
     }
}



//adding product in cart
if (isset($_POST['add_to_cart'])) {
$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$product_price = $_POST['product_price'];
$product_image = $_POST['product_image' ];
$product_quantity = $_POST['product_quantity' ];
$cart_num = mysqli_query($conn, "SELECT * FROM 'cart' WHERE name = '$product_name' AND user_id ='$user_id'")
or die('query failed');
if (mysqli_num_rows($cart_num)>0) {
$message[]='product already exist in cart';
}else{
mysqli_query($conn, "INSERT INTO 'cart' (`user_id`, `pid` , `name`, `price`, `quantity` , `image`) VALUES('$
user_id', '$product_id', '$product_name', '$product_price' ,'$product_quantity' , '$product_image')");
$message[]='product successfuly added in your cart';
}

}


?>

<!DOCTYPE htmL>
<html lang='en'>

<head>
     <meta charset='UTF-8'>
     <meta name='viewport' content='width=device-width, initial-scale=1.θ'>
     <link  rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
     <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css'>

     <link rel='stylesheet' type='text/css' href='main.css'>
     <title>User</title>
</head>
 
<body>
<?php include 'header.php';
     ?>
    <!--------------------------------------home slider------------------------------->
    <div class="container-fluid">
          <div class="hero-section">
               <img src="img/slider.png" alt="Hero Image">
               <div class="hero-caption">
                    <h1>Welcome to AURA</h1>
                    <p>Discover amazing products and enjoy exclusive offers!</p>
                    <a href="shop.php" class="btn">Shop Now</a>
               </div>
          </div>
     </div>
          <div class="services">
               <span style="font-size: 40px; color: #ff98bc; margin-left:100px; margin-top:50px">Services</span>
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
               <div class="row align-items-center clearfix">
                    <div class="box col-md-8" >     
                         <span style="color: #fff">Our Story</span>
                         <h1>Beginning of this Journey</h1>
                         <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                         <a href="shop.php" class="btn">shop now</a>
                    </div>
                    <div class="box col-md-4">
                         <img src="img/images (1).jpg">
                    </div>
               </div>
          </div>
          <div class="discover">
               <div class="detail">
                    <h1 class="title" style="color:#ff98bc; font: size 40px; font-weight:400">Light Weight & Comfortable Hijab</h1> <span>Buy 3 And Save 30% Off!</span>
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