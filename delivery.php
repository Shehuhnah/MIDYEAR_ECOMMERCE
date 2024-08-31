<?php
session_start();
    include 'db.php'; 
    require 'PHPMailer-master/src/Exception.php';
    require 'PHPMailer-master/src/PHPMailer.php';
    require 'PHPMailer-master/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $customerId = $_SESSION['user_id'] ?? null;
    
    if (isset($_POST['submit'])) {
        $query = "
            SELECT name, quantity, price
            FROM cart
            WHERE user_id = ?
        ";

        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $cartItems = $result->fetch_all(MYSQLI_ASSOC);

        if (!$cartItems) {
            die('No items found in the cart.');
        }


        $paymentMethod = $_POST['payment_method'] ?? null;
        $fullname = $_POST['fname'] ?? null;
        $phone = $_POST['number'] ?? null;
        $email = $_POST['email'] ?? null;
        $address = $_POST['address'] ?? null;
        $payment_status = "Pending" ?? null;
            
        $productNames = [];
        $totalAmount = 0;
        $totalitem = 0;

        foreach ($cartItems as $item) {
            $subtotal = $item['quantity'] * $item['price'];
            $totalitem += $item['quantity'];
            $totalAmount += $subtotal;
            $productNames[] = $item['name'];
        }

        $productNamesString = implode(', ', $productNames);

        // Prepare the SQL query for inserting the order
        $insertQuery = "
        INSERT INTO orders (user_id, name, number, email, method, address, total_products, total_price, placed_on, payment_status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)
        ";

        // Prepare the statement
        $stmt = $conn->prepare($insertQuery);

        // Check if the statement was prepared correctly
        if ($stmt === false) {
        die('Error preparing SQL statement: ' . $conn->error);
        }

        // Bind parameters correctly with appropriate types
        $stmt->bind_param(
        'isssssids', 
        $customerId, 
        $fullname, 
        $phone, 
        $email, 
        $paymentMethod, // Corrected parameter order for the 'method' field
        $address, 
        $totalitem, // Corrected to use product names as a string
        $totalAmount, 
        $payment_status
        );

        // Execute the statement
        $stmt->execute();

        // Check for errors after execution
        if ($stmt->error) {
        die('Error executing SQL statement: ' . $stmt->error);
        }

        // Close the statement
        $stmt->close();


        // $deleteQuery = "DELETE FROM cart_tbl WHERE customer_id = ?";
        // $stmt = $conn->prepare($deleteQuery);
        // $stmt->bind_param('i', $customerId);
        // $stmt->execute();

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'shehannamarie@gmail.com';
            $mail->Password = 'xohpiuowxscvyjyf';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('shehannamarie@gmail.com', 'Naurs');
            $mail->addAddress($_SESSION["useremail"]); 

            $mail->isHTML(true);
            $mail->Subject = 'Order Confirmation';
            $mail->Body    = "
                <h1>Thank you for purchasing!</h1>
                <p>We’re excited to be a part of your beauty journey. Your order is confirmed and will be processed shortly <3</p>
                <p>But, Here are the details:</p>
                <p>Products: $productNamesString</p>
                <p>Total Amount: ₱" . number_format($totalAmount, 2) . "</p>
                <p>Mode of Payments:  $paymentMethod </p>
                <p> </p>
                <p> </p>
                <p> </p>
                
                <p>Very Demure, Very Cutesy. </p>
            ";
            $mail->send();
            echo 'Message has been sent';
            header("location:tnx.html");
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="payment.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row" id="ultraheader">
            <div class="col-3 g-0" >
                <nav class="navbar">
                    <a href="../../homepagelst.php"><img src="../../images/logo.png" alt="" class="logo"></a>
                </nav>
                <div >
                    <p class="brand-title">NAURS</p>
                </div>
            </div>
        </div>
        <!-- BREADCRUMB -->
        <div class="row">
            <div class="col-12" style="background-color: #F8F4E1">
                <nav aria-label="breadcrumb" class="breadcrumbnav">
                    <ol class="breadcrumb" style="background-color: #F8F4E1" >
                        <li class="breadcrumb-item" id="usod">
                            <a href="product.php" id="breadlink" class="link">PRODUCT</a>
                        </li>
                        <li class="breadcrumb-item " >
                            <a href="checkout.php" id="breadlink">CHECKOUT FORM</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"id="breadlink">
                            PAYMENT FORM
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- HEADER -->
        <div class="row body">
            <div class="col-lg-9 col-md-6" id="leftside" style="background-color:#F8F4E1; padding-bottom:15%;">
                <div class="row title">
                    <div class="col-lg-12" >
                        <div class="container" >
                            <div class="row" id="headertitle">
                                <div class="col-md-8">
                                    <p class="titlepay"><i class="fa-solid fa-file-invoice-dollar"></i> PAYMENT FORM</p>
                                </div>
                                <div class="col-md-4">
                                <p class="fw-bold"> 
                                    Order Subtotal (<?=$_SESSION['totalitem']?> items): ₱ <?= number_format($_SESSION ['subtotal'],2) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="container" >
                        <div class="col-lg-9 mx-auto mb-4" id="innerleft">
                            <div class="container  innerleft">
                                <h5 class="lake pb-1">Delivery Address</h5>
                                <p class="liit">Please Input Your Valid Credentials</p>
                                <p class="liit pt-1">*Indicates required field</p>
                            </div>
                            <div class="container" >
                                <form action="" method="post">
                                    <div class="container" id="form">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="fname" class="form-label">FULL NAME*</label>
                                                <input type="text" class="form-control" id="fname" placeholder="" name="fname" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="lname" class="form-label">PHONE NUMBER*</label>
                                                <input type="text" class="form-control" id="phone" placeholder="" name="number" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="phone" class="form-label">EMAIL*</label>
                                                <input type="text" class="form-control" id="phone" placeholder="" name="email" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="add1" class="form-label">FULL ADDRESS*</label>
                                                <input type="text" class="form-control" id="add1" placeholder="" name="address" required>
                                            </div>
                                            
                                        </div>
                                        <!-- PAYMENT -->
                                        <div class="row">
                                            <div class="container  innerleft" >
                                                <h5 class="lake pb-1">Payment Details</h5>
                                                <p class="liit pt-1">Please Specify Your Mode of Payment </p>
                                            </div>
                                            <div class="container">
                                                <div class="row payment">
                                                    <div class="col-md-6 mb-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="COD" required>
                                                            <label class="form-check-label" for="cod">
                                                                Cash On Delivery
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="payment_method" id="op" value="OP" required>
                                                            <label class="form-check-label" for="op">
                                                                Online Payment
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class=" col-lg-6 mb-5 mx-auto">
                                                <button class="" type="submit" name="submit" id="button">
                                                    Complete Payment
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <!-- SUMMARY -->
            <div class="col-lg-3 col-md-6 badgeq"  >
                <div class="row" >
                    <div class="col-lg-12" >
                        <div class="container" id="rightside">
                            <h5 class="righttitle"><i class="fa-solid fa-clipboard-list"></i> SUMMARY</h5>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="container d-flex justify-content-between">
                            <p class="summarydetails">Subtotal:</p>
                            <p class="summarydetails"> ₱ <?= number_format($_SESSION['subtotal'],2);?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="container d-flex justify-content-between">
                            <p class="summarydetails">Shipping:</p>
                            <p class="summarydetails">+ 38</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 ">
                        <div class="container total d-flex justify-content-between">
                            <strong>
                                <p class="summarydetails">Total:</p>
                            </strong>
                            <strong>
                                <p class="summarydetails"> ₱ <?= number_format($_SESSION['totalprice'],2);?></p>
                            </strong>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 ">
                        <div class="container righttitle mt-3">
                            <p><i class="fa-solid fa-cart-shopping"></i> Cart <span style="font-size: 1rem;">(<?= $_SESSION['totalitem'];?> items)</span></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12" >
                        <?php 
                            $customer_id = $_SESSION['user_id'];
                            $query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $customer_id");
                            if(mysqli_num_rows($query) > 0){
                                foreach($query as $cart){
                                    $prod_id = $cart['pid']; 
                                    
                        ?>
                        <div class="container mt-2 " id="prod">
                            <div class="row ">
                                <div class="col-lg-4">
                                    <div class="img-wrapper">
                                        <img src="<?= $cart['image']; ?>" alt="product" class="product-image"> 
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="row">
                                        <div class="col-lg-7">
                                            <p class="cartproductheader"><?= $cart['name'];?></p>
                                            
                                        </div>
                                        <div class="col-lg-5">
                                            <p class="cartproductheader">₱ <?= number_format($cart['price'],2);?></p>
                                        </div>
                                    </div>
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <p class="mid">Quantity: <?= $cart['quantity'];?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                                }
                            }
                        ?>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-lg-12">
                        <div class="container">
                            <div class="row">
                                <div class="col-7">
                                    <p class="big">Need help?</p>
                                    <a href="" class="contactlink">Contact Us!</a>
                                    <p class="needhelp">Call Us</p>
                                    <p class="needhelp">+64 912384523</p>
                                    <p class="last">Mon-Fri 8am-6am EST </p>
                                </div>
                                <div class="col-5">
                                    <div class="img-wrapper">
                                        <a href="homepage.php">
                                            <img src="logo.png" alt="logo" class="img" style="size:1%;">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>