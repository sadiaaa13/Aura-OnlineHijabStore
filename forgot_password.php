<?php
include 'connection.php';

if (isset($_POST['submit-btn'])) {
    $email = mysqli_real_escape_string($conn, filter_var($_POST['email'], FILTER_SANITIZE_STRING));

    $select_user = mysqli_query($conn, "SELECT * FROM `users` WHERE email='$email'") or die('query failed');

    if (mysqli_num_rows($select_user) > 0) {
        $row = mysqli_fetch_assoc($select_user);

        // Here you can implement logic to send an email with the password reset link.
        $message[] = 'An email with password reset instructions has been sent to your email.';
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
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <section class="form-container">
        <?php
        if (isset($message)) {
            foreach ($message as $message) {
                echo '<div class="message"><span>' . $message . '</span><i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i></div>';
            }
        }
        ?>
        <form method="post">
            <h1>Forgot Password</h1>
            <p>Enter your email address below, and we'll send you a link to reset your password.</p>
            <div class="input-field">
                <label>Email</label><br>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <input type="submit" name="submit-btn" value="Send Reset Link" class="btn">
            <p><a href="login.php">Back to Login</a></p>
        </form>
    </section>
</body>
</html>
