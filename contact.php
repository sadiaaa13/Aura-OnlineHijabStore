<?php
include 'connection.php'; // Include your database connection file

$message = ''; // Initialize variable to store success or error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message_text = mysqli_real_escape_string($conn, $_POST['message']);

    // Insert message into database
    $sql = "INSERT INTO `message` (id, name, email, message) VALUES ('$id', '$name', '$email', '$message_text')";
    if (mysqli_query($conn, $sql)) {
        $message = "Message sent successfully!";
    } else {
        $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="main.css">
    <style>
        /* General styles */
        body {
            margin-top: 30px;
            font-family: Arial, sans-serif;
        }

        .section-title {
            text-align: center;
            padding-bottom: 30px;
        }

        .section-title h2 {
            font-size: 32px;
            color: #333;
        }

        .section-title h3 {
            font-size: 28px;
        }

        .section-title h3 span {
            color: #ff98bc;
        }

        /* Contact section styles */
        .contact {
            height: auto;
            width: 100%;
            padding: 60px 0;
            background: linear-gradient(to top, #ff98bc, #fff);
        }

        .contact .info {
            margin-bottom: 20px;
            height: 200px;
            width: 400px;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
        }

        .contact .info h4 {
            font-size: 22px;
            color: #333;
            margin-bottom: 10px;
        }

        .contact .info p {
            font-size: 14px;
            color: #555;
        }

        .contact .info i {
            font-size: 24px;
            color: #007bff;
            margin-bottom: 10px;
        }

        .contact .php-email-form {
            margin-top: 20px;
            padding: 10px;
            align-items: center;
            height: fit-content;
            border-radius: 10px;
            background: #fff;
        }

        .contact .php-email-form .form-group {
            width: 360px;
        }

        .contact .php-email-form label {
            font-weight: bold;
            color: #333;
        }

        .contact .php-email-form input,
        .contact .php-email-form textarea {
            border-radius: 5px;
            margin-bottom: 5px;
            height: 35px;
            font-size: 14px;
        }

        .contact .php-email-form button[type="submit"] {
            background: #fff;
            width: 360px;
            border: 1;
            padding: 10px 24px;
            color: #ff98bc;
            transition: 0.4s;
        }

        .contact .php-email-form button[type="submit"]:hover {
            background: #ff98bc;
            color: white;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .contact .row > .col-lg-6 {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <main id="main">
        <section id="contact" class="contact">
            <div class="container">
                <div class="section-title">
                    <h2>Contact</h2>
                    <h3>Get In Touch With <span>Us</span></h3>
                </div>
                <div>
                    <iframe style="border:0; width: 100%; height: 270px; margin-bottom:20px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.0291459792316!2d90.23014259999999!3d23.824721100000003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c790e6cf50a9%3A0xcae56c17297f85f8!2s141%2C%20%C3%86%20ahsanullah%20university%20of%20science%20and%20technology%20industrial%20area%2C%20Dhaka%201208!5e0!3m2!1sen!2sbd!4v1623674537107!5m2!1sen!2sbd" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="row">
                    <div class="info">
                        <div class="address">
                            <i class="bi bi-geo-alt"></i>
                            <h4>Location:</h4>
                            <p>Ahsanullah University of Science and Technology<br>141 & 142, Love Road, Tejgaon Industrial Area, Dhaka-1208</p>
                        </div>
                    </div>
                    <div class="info">
                        <div class="email">
                            <i class="bi bi-envelope"></i>
                            <h4>Email:</h4>
                            <p>info@aust.edu<br> regr@aust.edu</p>
                        </div>
                    </div>
                    <div class="info">
                        <div class="phone">
                            <i class="bi bi-phone"></i>
                            <h4>Call:</h4>
                            <p>+8801*********<br>+8801*********</p>
                        </div>
                    </div>
                </div>

                <div class="row" style="display: flex; justify-content: center;">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" role="form" class="php-email-form">
                        <div class="form-group mt-3">
                            <input type="number" class="form-control" name="id" id="id" placeholder="User ID" required>
                        </div>
                        <div class="form-group mt-3">
                            <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
                        </div>
                        <div class="form-group mt-3">
                            <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required>
                        </div>
                        <div class="form-group mt-3">
                            <textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
                        </div>
                        <div class="text-center"><button type="submit">Send Message</button></div>
                    </form>
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-success form-group col-lg-9 mt-3"><?php echo $message; ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>