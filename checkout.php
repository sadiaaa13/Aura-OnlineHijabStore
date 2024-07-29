<?php
include 'connection.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("location:login.php");
    exit();
}

if (isset($_POST['order_btn'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn, 'flat no. '.$_POST['flate'].','.$_POST['street'].','.$_POST['city'].','.$_POST['state'].','.$_POST['country'].','.$_POST['pin']);
    $placed_on = date('d-M-Y');
    $cart_total = 0;
    $cart_product = [];
    
    $cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id'") or die('query failed');
    
    if (mysqli_num_rows($cart_query) > 0) {
        while ($cart_item = mysqli_fetch_assoc($cart_query)) {
            $cart_product[] = $cart_item['name'].' ('.$cart_item['quantity'].')';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }
    $total_products = implode(', ', $cart_product);

    // Insert order into the database
    $insert_order_query = "INSERT INTO orders (user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES ('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')";

    if (mysqli_query($conn, $insert_order_query)) {
        // Clear cart after successful order insertion
        mysqli_query($conn, "DELETE FROM cart WHERE user_id='$user_id'");

        $message[] = 'Order placed successfully';
        header('Location: checkout.php');
        exit();
    } else {
        $message[] = 'Failed to place order';
    }
}
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css'>
    <link rel='stylesheet' type='text/css' href='main.css'>
    <title>Checkout page</title>
    <style>
        @media (max-width: 768px) {
            .payment-form {
                padding: 15px;
                margin-top: 20px;
            }
            .input-field {
                margin-bottom: 15px;
            }
            .grand-total {
                font-size: 16px;
            }
        }
        @media (max-width: 480px) {
            .payment-form {
                padding: 10px;
            }
            .input-field input,
            .input-field select {
                width: 100%;
            }
            .grand-total {
                font-size: 14px;
                margin-bottom: 30px;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <section class="form-container1" style="align-items: center;">

        <form method="post" class="payment-form">
            <h1 class="title">Payment Process</h1>
            <?php
            if (isset($message)) {
                foreach ($message as $msg) {
                    echo '
                    <div class="message">
                        <span>' . $msg . '</span>
                        <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                    </div>
                    ';
                }
            }
            ?>
            <?php
            $select_cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id'") or die('query failed');
            $total = 0;
            $grand_total = 0;
            if (mysqli_num_rows($select_cart) > 0) {
                while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                    $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
                    $grand_total = $total += $total_price;
                    ?>
                    <?php
                }
            }
            ?>
            <span class="grand-total">Total Amount Payable : <?= $grand_total; ?>/-</span>

            <div class="input-field">
                <label>your name</label>
                <input type="text" name="name" placeholder="enter your name" required>
            </div>
            <div class="input-field">
                <label>your number</label>
                <input type="text" name="number" placeholder="enter your number" required 
                    pattern="\d{11}" maxlength="11" title="Please enter 11 digits">
            </div>
            <div class="input-field">
                <label>your email</label>
                <input type="email" name="email" placeholder="enter your email" required>
            </div>
            <div class="input-field">
                <label>select payment method</label>
                <select name="method" required>
                    <option selected disabled>select payment method</option>
                    <option value="Cash on Delivery">Cash on Delivery</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Nagad">Nagad</option>
                    <option value="Bkash">Bkash</option>
                </select>
            </div>

            <div class="input-field">
                <label>address line 1</label>
                <input type="text" name="flate" placeholder="e.g flat no.">
            </div>
            <div class="input-field">
                <label>address line 2</label>
                <input type="text" name="street" placeholder="e.g street name">
            </div>
            <div class="input-field">
                <label>city</label>
                <input type="text" name="city" placeholder="e.g Dhaka">
            </div>
            <div class="input-field">
                <label>state</label>
                <input type="text" name="state" placeholder="e.g Dhaka">
            </div>
            <div class="input-field">
                <label>country</label>
                <input type="text" name="country" placeholder="e.g Bangladesh">
            </div>
            <div class="input-field">
                <label>pin code</label>
                <input type="text" name="pin" placeholder="e.g 110012">
            </div>
            <input type="submit" name="order_btn" class="btn" value="Order Now">
        </form>
    </section>

    <script type="text/javascript" src="script.js"></script>
    <?php include 'footer.php'; ?>
</body>
</html>
