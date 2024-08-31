<?php 
session_start();
include 'db.php';

    if (isset($_POST['updatecheckout'])) {
        $cartIds = $_POST['cartid']; 
        $cartQuantities = $_POST['cartquantity'];
        $customerid = $_SESSION['user_id'];

        foreach ($cartIds as $index => $cartId) {
            $cartQuantity = $cartQuantities[$cartId];

            $sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $cartQuantity, $customerid, $cartId);

            if (!$stmt->execute()) {
                $_SESSION['alertproduct'] = "Quantity Failed to Update.";
                break;
            }
        }
        $_SESSION['alertproduct'] = "Quantity Updated Successfully.";
    }
    if (isset($_POST['deletebtn'])) {
        $cart_id =$_POST['deletecaritem'];
        $customer_id = $_SESSION['customer_id'];
        
        $query = mysqli_query($conn, "DELETE FROM cart WHERE id = '$cart_id'");

        if ($query) {
        } else {
        }

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Out</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/checkoutform.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap');

        html ::-webkit-scrollbar-track{
            background: transparent;
        }
        html ::-webkit-scrollbar-thumb{
            background: black;
            border-radius: 100px;
        }
        html ::-webkit-scrollbar{
            width: 5px;
        } 
        body{
            overflow-x: hidden;
            
            

        }
        #ultraheader{
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            background-color:#543310;
        }
        .brand-title{
            font-size: 1.9rem;
            color: aliceblue;
            font-family: "Merriweather", serif;
            font-weight: 500;
            font-style: normal;

            

        }
        .logo{
            height: auto;
            width: 16%;
        }

        #nav{
            font-family: "Josefin Sans", sans-serif;
            font-weight: 400;
            background-color: #E9E9E9;
        }
        .nav-link{
            color: #2C1A11;
            font-size: 1rem;
            font-weight: 700;
            border-bottom: #2C1A11 2px solid;
        }
        .navbar {
            margin: 0;
            padding: 0;
        }
        #usod{
            margin-left: 3%;
        }
        .breadcrumbnav{
            border-bottom: #2C1A11 1px solid;
            background-color: #E9E9E9;
        
        }
        .breadcrumb{
            margin: 0;
            padding: 0;
            background-color: #E6CCB3;
        }
        .breadcrumb{

            .link{
                color: #2C1A11;
                text-decoration: none;
            }
            #breadlink{
                color: #2C1A11;
                font-size: 1rem;
                font-weight: 600;
            }
        }


        ol a{
            text-decoration: none;
            font-family: "Poppins", sans-serif;
        }


        /* BODY */

        .titlepay{
            font-size: 2rem;
            font-family: "Josefin Sans", sans-serif;
            font-weight: 600;
        }

        .title{
            margin-top: 1.9rem;
            margin-left: 3.4rem;
        }

        .checkoutbox{
            border: #2C1A11 1px solid;
        }

        /* RIGHT */
        #lefttotal{
            border-bottom: #2C1A11 1px solid;
            box-shadow: 0 15px 100px rgba(0, 0, 0, 0.1); 
        }

        .header{
            font-size: 1.9rem;
            font-family: "Josefin Sans", sans-serif;
            color: #2C1A11;
            margin-top: 1rem;
            font-weight: 600;
        }

        .buttonpay{
            background-color: #2C1A11;
            color: #E9E9E9;
            margin-top: 1rem;
            margin-bottom: 1rem;
            font-family: "Poppins", sans-serif;

        }

        .coupon{
            font-family: "Poppins", sans-serif;
        }

        .img-wrapper{
            max-width: 100%;
            height: 5rem;
            align-items: flex-start;
            display: flex;
            justify-content: center;
        }
        img {
            max-width: 100%;
            height:100%;
            object-fit: contain;
        }

        .removebtn{
            background-color: #2C1A11;
            color: #E9E9E9;
            font-family: "Poppins", sans-serif;

        }

        .product_name{
            font-family: "Poppins", sans-serif;
            color: #2C1A11;
            font-weight: 600;
        }
        .table{
            margin: 0 auto;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px; 
            box-shadow: 15px 15px 15px rgba(0, 0, 0, 0.3); 
            background-color: #ffffff; 
            
        }
        th {
            background-color: #543310; 
            color: #fff;
            padding: 12px; 
        }

        td {
            background-color: #f9f9f9;
            box-shadow: inset 0 9px 9px rgba(0, 0, 0, 0.3); 
        }

        tr {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
        }

        .table th {
            font-family: "Poppins", sans-serif;
            background-color: #2C1A11; 
            color: aliceblue; 
            padding: 10px; 
            text-align: center; 
            font-weight: 400;
            box-shadow: 4px 10px 112px rgba(0, 0, 0, 0.3);
        }
        .table td, .table th {
            
            font-size: 1rem;
        }

        .table tbody tr{
            font-family: "Poppins", sans-serif;
        }
        .table tbody tr td{
            font-size: .9rem;
        }

        .table td, .table th {
            vertical-align: middle;
        }

        tfoot  {
            .subtotal-row td {
                font-family: "Poppins", sans-serif;
                color: #2C1A11;
                font-size: 1rem;
                font-weight: 600;
            }
        }

        #continue{
            margin-top: 3rem;
            margin-bottom: 2rem;

        }

        .continue{
            text-decoration: none;
            font-family: "Poppins", sans-serif;
            color: #2C1A11;
            font-size: 1.3rem;
            font-weight: 700;
            border: none;
        }

        .checkoutbox {
            box-shadow: 0px 50px 50px rgba(0, 0, 0, 0.2); 
            border-radius: 8px; 
            background-color: #F8F9FA;
            background-color: #E6CCB3;
        }

        .body{
            background-color: #E9E9E9;
            height: 100vh;
        }

        .footer{
            display: flex;
            justify-content: center;
            text-align: center;
        }

        .number-input .form-control {
            max-width: 80px; 
            border-radius: 0; 
            border-left: none; 
            border-right: none; 
        }

        .number-input .minus-btn {
            border-radius: 0.25rem 0 0 0.25rem; 
        }

        .number-input .plus-btn {
            border-radius: 0 0.25rem 0.25rem 0; 
        }

        .number-input .btn {
            padding: 0.5rem 1rem;
        }
        .table-hover tbody tr:hover {
        background-color: #74512D; /* Your desired hover color */
    }
    </style>
</head>
<body>
    <div class="container-fluid" style="background-image: url(cover.jpg);">
        <div class="row g-0" id="ultraheader">
            <div class="col-3 g-0">
                <nav class="navbar">
                    <a href="homepage.php"><img src="lsddo.png" alt="" class="logo"></a>
                </nav>
                <div>
                    <p class="brand-title">NAURS</p>
                </div>
            </div>
        </div>
        <div class="container-fluid body" style="background-image: url(cover.jpg);">
            <div class="row g-0">
                <div class="col-12">
                    <nav aria-label="breadcrumb" class="breadcrumbnav">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item" id="usod">
                                <a href="product.php" id="breadlink" class="link">PRODUCT</a>
                            </li>
                            <li class="breadcrumb-item active">
                                <a href="" id="breadlink">CHECKOUT FORM</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-12 title">
                    <p class="titlepay"><i class="fa-solid fa-file-invoice"></i> CHECKOUT FORM</p>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row" >
                    <div class="col-lg-9 col-md-6">
                        <div class="container" >
                            <table class="table table-bordered table-hover" style=" background-color:#E6CCB3">
                                <thead class="table" >
                                    <tr class="text-center">
                                        <th colspan="1" >Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                
                                <tbody class="table-group-divider">
                                
                                <?php
                                    $totalPricee = 0;
                                    $customer_id = $_SESSION['user_id'];
                                    $query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $customer_id");
                                    if(mysqli_num_rows($query) > 0){
                                        foreach($query as $cart){
                                            $prod_id = $cart['id']; 
                                            $prod_query = mysqli_query($conn, "SELECT quantity FROM cart WHERE id = $prod_id");
                                            $prod_data = mysqli_fetch_assoc($prod_query);
                                            $prodstock = $prod_data['quantity'];

                                            $subtotal = $cart['price'] * $cart['quantity'];
                                            $totalPricee += $subtotal;
                                ?>
                                        <tr class="text-center align-middle" >
                                            <td colspan="1" style="padding: 10px; background-color:#E6CCB3;">
                                                <div class="d-flex align-items-center justify-content-left" style="margin-left:15%" >
                                                    <div class="img-wrapper">
                                                        <img src="<?=$cart['image']; ?>" class="img" alt="test">
                                                    </div>
                                                    <p class= "text-center  product_name"><?= $cart['name'];?></p>
                                                </div>
                                            </td>
                                            <td style="padding: 5px; background-color:#E6CCB3"><?= $cart['price'];?></td>
                                            <td style="padding: 5px; background-color:#E6CCB3">
                                                <div class="input-group number-input justify-content-center">
                                                    <button class="btn btn-outline-secondary minus-btn" type="button">-</button>
                                                    <input type="number" id="quantity-<?= $cart['id']; ?>" class="form-control text-center quantity-input" value="<?= $cart['quantity']; ?>" min="1" placeholder="<?= $cart['quantity']; ?>">
                                                    <button class="btn btn-outline-secondary plus-btn" type="button">+</button>
                                                </div>
                                            </td>
                                            <td style="padding: 5px; background-color:#E6CCB3"><?= number_format($subtotal,2); ?></td>
                                            <td style="background-color:#E6CCB3">
                                                <form action="" method="post" >
                                                    <input type="hidden" name="deletecaritem" value="<?= $cart['id']; ?>">
                                                    <button type="submit" class="btn delete-btn" name="deletebtn">x</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php
                                        }
                                    }
                                   
                                   
                                     ?>
                                </tbody>
                                <tfoot >
                                    <tr class="text-center subtotal-row" style="background-color:#C9B29C">
                                        <td colspan="2" style="background-color:#E6CCB3"></td>
                                        <td colspan="1" style="background-color:#E6CCB3">Subtotal:</td>
                                        <td colspan="" style="background-color:#E6CCB3">₱ <?= number_format($totalPricee,2);?></td>
                                        <?php $_SESSION ['subtotal'] = $totalPricee; ?>
                                    </tr>
                                </tfoot>E6CCB3
                            </table>
                        </div>
                        <div class="container" id="continue">
                            <div class="row footer">
                                <div class="col-lg-6">
                                    <a href="../../product.php" class="continue text-center">< Continue Shopping</a>
                                </div>
                                <div class="col-lg-6">
                                    <form action="" method="post" id="update-cart-form">
                                        <?php 
                                            // Reset pointer to get correct cart ID and quantity for each item
                                            $query->data_seek(0); 
                                            while ($cart = $query->fetch_assoc()) { 
                                        ?>
                                        <input type="hidden" id="cartquantity-<?= $cart['id']; ?>" name="cartquantity[<?= $cart['id']; ?>]" value="<?= $cart['quantity']; ?>">
                                        <input type="hidden" name="cartid[]" value="<?= $cart['id']; ?>">
                                        <?php } ?>
                                        <button type="submit" style="background-color: #E6CCB3;" name="updatecheckout" class="continue">
                                            <i class="fa-solid fa-rotate-right"></i> Update Checkout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 ">
                        <div class="container checkoutbox">
                            <p class="header text-center">
                                <i class="fa-solid fa-bag-shopping"></i> Cart Totals
                            </p>    
                            <div class="d-flex justify-content-between" id="lefttotal">
                                <?php 
                                    $totalPrice = 0;
                                    $totalitem = 0;
                                    $shipping = 38;

                                    if(mysqli_num_rows($query) > 0){
                                        foreach($query as $cart){
                                            $totalPrice += $cart['price'] * $cart['quantity']; 
                                            $totalitem +=  $cart['quantity'];
                                        }
                                    }

                                    $totalPrice += $shipping;
                                
                                ?>
                                <p class="total fs-5 fw-bold">
                                    Total:
                                </p>
                                <p class="total fs-5 fw-bold">
                                    ₱ <?= number_format($totalPrice, 2); ?>
                                    <?php $_SESSION ['totalprice'] = $totalPrice; ?>
                                </p>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <p class="coupon fw-bold">
                                    Items:
                                </p>
                                <p class="coupon">
                                    <?= $totalitem;?>
                                    <?php $_SESSION['totalitem'] = $totalitem; ?>
                                </p>
                            </div>
                            <div class="d-flex justify-content-between" id="lefttotal">
                                <p class="coupon fw-bold">
                                    Shipping:
                                </p>
                                <p class="coupon">
                                    + <?= number_format($shipping, 2);?>
                                </p>
                            </div>
                            <div class="d-flex justify-content-center">
                                <a href="delivery.php" style="color: #2C1A11;"><button class="btn buttonpay">Pay Now</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
// For minus button
document.querySelectorAll('.minus-btn').forEach(button => {
    button.addEventListener('click', function() {
        const input = this.nextElementSibling;
        let value = parseInt(input.value) || parseInt(input.min);
        if (value > parseInt(input.min)) {
            value--;
            input.value = value;
            updateCartQuantity(input.id.split('-')[1]);
        }
    });
});

// For plus button - removed the max check
document.querySelectorAll('.plus-btn').forEach(button => {
    button.addEventListener('click', function() {
        const input = this.previousElementSibling;
        let value = parseInt(input.value) || parseInt(input.min);
        value++; // Simply increment the value without checking for max
        input.value = value;
        updateCartQuantity(input.id.split('-')[1]);
    });
});

function updateCartQuantity(cartId) {
    var quantity = document.getElementById("quantity-" + cartId).value;
    document.getElementById("cartquantity-" + cartId).value = quantity;
}


</script>
</body>
</html>
