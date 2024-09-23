<?php
include 'connection.php';
session_start();
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
    <?php include 'header.php' ;?>

    <div style="background: linear-gradient(to top, #8d7968,#bab8b1); padding:20px; height:450px">
        <h1 style="font-size: 32px; color: #3e3f3e;; text-align: center; font-weight:400; margin-top:100px; margin-bottom:30px">About Us</h1>
        <p style="align:center">Welcome to our hijab store! We are dedicated to providing high-quality hijabs for all occasions.Our mission is to offer a wide range of hijabs that cater to different styles, preferences, and needs. Whether you're looking for casual everyday hijabs or elegant hijabs for special occasions, we've got you covered.At our store, we believe that wearing a hijab is a beautiful expression of faith and identity. We strive to empower women by offering stylish and comfortable hijab options that allow them to feel confident and empowered.Customer satisfaction is our top priority. We are committed to providing excellent customer service and ensuring that every shopping experience is enjoyable and hassle-free.Thank you for choosing our hijab store. We look forward to serving you!</p>
</div>

    <?php include 'footer.php' ;?>
</body>
</html>