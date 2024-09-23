<?php
include 'connection.php';
session_start();

if (isset($_POST['submit-btn'])) {

    $email = mysqli_real_escape_string($conn, filter_var($_POST['email'], FILTER_SANITIZE_STRING));
    $password = mysqli_real_escape_string($conn, filter_var($_POST['password'], FILTER_SANITIZE_STRING));

    $select_user = mysqli_query($conn, "SELECT * FROM `users` WHERE email='$email' AND password='$password'") or die('query failed');

    if (mysqli_num_rows($select_user) > 0) {
        $row = mysqli_fetch_assoc($select_user);

        if ($row['user_type'] == 'admin') {
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['admin_id'] = $row['id'];
            header('location:admin_pannel.php');
        } elseif ($row['user_type'] == 'user') {
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_id'] = $row['id'];
            header('location:index.php');
        } else {
            $message[] = 'Incorrect email or password';
        }
    } else {
        $message[] = 'User does not exist';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section class="form-container">
        <div class="image-container">
            <img src="img/login.jpeg" alt="Login Image">
        </div>
        <div class="form-content">
            <?php
            if (isset($message)) {
                foreach ($message as $message) {
                    echo '<div class="message"><span>' . $message . '</span><i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i></div>';
                }
            }
            ?>
            <form method="post">
                <h1>Login</h1>
                <div class="input-field">
                    <label>Email</label><br>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="input-field">
                    <label>Password</label><br>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
                <input type="submit" name="submit-btn" value="Login Now" class="btn">
                <p><a href="forgot_password.php">Forgot Password?</a></p>
               
                <p>Don't have an account? <a href="register.php">Register now</a></p>
            </form>
        </div>
    </section>
</body>
</html>
