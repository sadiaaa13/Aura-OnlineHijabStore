<?php
include 'connection.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}
if (isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
}

// Updating qty
if (isset($_POST['update_qty_btn'])) {
    $update_qty_id = $_POST['update_qty_id'];
    $update_value = $_POST['variable'];
}

// Delete product from wishlist
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `wishlist` WHERE id = '$delete_id'") or die('query failed');
    header('location:wishlist.php');
}

// Delete all products from wishlist
if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `wishlist` WHERE user_id = '$user_id'") or die('query failed');
    header('location:wishlist.php');
}
?>
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.θ'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css'>
    <link rel='stylesheet' href='main.css'>
    <title>My Wishlist</title>
</head>

<body>
    <div class='banner'>
        <div class='detail'>
            <h1>My Wishlist</h1>
            <a href='index.php'>Home</a><span>/ Wishlist</span>
        </div>
    </div>
    <div class='line'></div>
    <section class='shop'>
        <h1 class='title'>Products added in wishlist</h1>
        <?php
        if (isset($message)) {
            foreach ($message as $message) {
                echo '
                <div class="message">
                    <span>' . $message . '</span>
                    <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                </div>';
            }
        }
        ?>

       