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
      body {
        margin: 0;
        font-family: Arial, sans-serif;
      }
      header {
          height: 70px;
      }

      .searchbar {
          background: linear-gradient(to bottom, #ff98bc, #fff);
          padding: 10px 20px;
          color: white;
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
          border: 1px solid #fff;
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
          background-color: #ff98bc;
      }


      .message {
          color: #ff98bc ;
          text-align: center;
          padding: 10px;
      }

      .box-container {
          background: linear-gradient(to top, #ff98bc, #fff);;
          display: flex;
          flex-wrap: wrap;
          justify-content: center;
          padding: 20px;
          margin-top: 20px;
      }

      .box {
        background: #fff;
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

    </style>
</head>
<body>
  <?php include 'header.php'; ?>
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
            </div>
        <?php endforeach; ?>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>