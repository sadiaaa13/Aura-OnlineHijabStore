<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <!--box icon link-->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
     <title>Document</title>
</head>
<body>
    <header class="header">
        <div class="flex">
        <a href="index.php" class="logo">
            <h1 style="font-family: Garamond, serif">Aura</h1>
        </a>
            <nav class="navbar">
                <a href="index.php">home</a>
                <a href="about.php">about us</a>
                <a href="shop.php">shop</a>
                <a href="offers.php">offer</a>
                <a href="order.php">order</a>
                <a href="contact.php">contact</a>
            </nav>
            <div class="icons">
            <u class="bi bi-person" id="user-btn"></u>
            <a href="wishlist.php"><u class="bi bi-heart" id="wishlist-btn"></u></a>
            <a href="cart.php"><u class="bi bi-cart" id="cart-btn"></u></a>
            <u class="bi bi-list" id="menu-btn"></u>
            <a href="search.php"><u class="bi bi-search" id="search-btn"></u></a>
        </div>
        <div class="user-box">
            <p>username: <span> <?php echo $_SESSION['user_name'];?></span></p>
            <p>Email: <span> <?php echo $_SESSION['user_email'];?></span></p>
            <form method="post">
              <button type="submit" name="logout"class="logout-btn">log out</button>
            </form>
        </div>
        </div>
    </header>
    <script type='text/javascript' src='mainscript.js'></script>
</body>
</html>