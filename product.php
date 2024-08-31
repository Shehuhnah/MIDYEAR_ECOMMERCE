<?php
include 'db.php';
session_start();

if (isset($_POST['addtocart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = 1;
    
    $user_id = $_SESSION['user_id'];    

    // Use a prepared statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM cart WHERE pid = ? AND user_id = ?");
    $stmt->bind_param("ii", $product_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Item already in cart; no update needed
        $_SESSION['alert_addtocart'] = "Product already in the cart.";
    } else {
        // Insert new item
        $stmt = $conn->prepare("INSERT INTO cart (user_id, pid, name, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisdss", $user_id, $product_id, $product_name, $product_price, $product_quantity, $product_image);
        $stmt->execute();
        $_SESSION['alert_addtocart'] = "Product successfully added to cart.";
    }
}
?>

<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="product.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .delete-btn {
            background-color: #2C1A11;
            color: #E9E9E9;
        }
        .table {
            th {
                font-family: "Poppins", sans-serif;
                border: #2C1A11 1px solid;
            }
            td {
                font-family: "Poppins", sans-serif;
            }
            tfoot strong {
                font-weight: 500;
            }
        }
        .table-bordered {
            border: 1px solid #000; /* Black border for the whole table */
        }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000; /* Black border for each cell */
        }
        .gotocart {
            background-color: #2C1A11;
            color: #E9E9E9;
        }
        .btn-primary {
            background-color: #2C1A11;
            border-color: #2C1A11;
        }
        .btn-primary:hover {
            background-color: #3a2d21;
            border-color: #3a2d21;
        }
    </style>
</head>
<body class="container-fluid">
    <div class="container-fluid">
        <div class="row" style="margin-left: 5%; padding-top:2%; position:fixed;">
            <nav class="navbar navbar-expand-md align-items-left justify-content-left">
                <a class="navbar-brand" href="homepage.php">
                    <img src="logo.png" alt="Logo" class="logo">
                </a>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <p class="text-center my-4 productstitle" >PRODUCTS</p>
            </div>
        </div>
        <div class="container">
            <div class="row d-flex justify-content-end">
                <div class="col-auto cart">
                    <button class="btn-cart position-fixed" data-bs-toggle="modal" data-bs-target="#cartModal">
                        <i class="fa-solid fa-cart-shopping"></i>CART<span class="badge top-0 translate-middle start-100 bg-danger position-absolute notif rounded-pill">
                            <?php
                                $user_id = $_SESSION['user_id'];
                                $stmt = $conn->prepare("SELECT COUNT(*) as total FROM cart WHERE user_id = ?");
                                $stmt->bind_param("i", $user_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $row = $result->fetch_assoc();
                                echo $row['total'];
                            ?>
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <div class="container mt-5 p-5">
            <div class="row">
                <?php
                    $products = mysqli_query($conn, "SELECT * FROM products;");
                    if(mysqli_num_rows($products) > 0) {
                        while($fetch_product = mysqli_fetch_assoc($products)) {
                ?>
                    <div class="col-sm-12 col-md-6 col-lg-3 d-flex align-items-center justify-content-center">
                        <div class="card" style="width: 17rem;">
                            <form action="" method="post">
                                <input type="hidden" name="product_name" value="<?= $fetch_product['name']; ?>">
                                <input type="hidden" name="product_price" value="<?= $fetch_product['price']; ?>">
                                <input type="hidden" name="product_image" value="<?= $fetch_product['image_01']; ?>">
                                <input type="hidden" name="product_id" value="<?= $fetch_product['id']; ?>">
                                <div class="product-container">
                                    <img src="<?= $fetch_product['image_01']; ?>" alt="Product 1">
                                    <img class="overlaypic" src="<?= $fetch_product['image_02']; ?>" alt="Hover Picture 1">
                                </div>
                                <div class="card-body">
                                    <h5 class="class-title text-center">
                                        <span><?= htmlspecialchars($fetch_product['name']); ?></span>
                                    </h5>
                                    <span class="text-align-right">
                                        &#8369; <?= htmlspecialchars($fetch_product['price']); ?>
                                    </span>
                                    <p class="card-text text-center">
                                        <span><?= htmlspecialchars($fetch_product['details']); ?></span>
                                    </p>
                                    <button class="btn btn-primary add-to-cart" type="submit" name="addtocart">Add to Cart</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Cart Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title-container">
                        <h5 class="modal-title" id="cartModalLabel" style="color: #2C1A11;">
                            <i class="fa-solid fa-cart-shopping"></i> Cart
                        </h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-light table-hover">
                        <thead class="table">
                            <tr class="text-center">
                                <th>Id</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php
                                $user_id = $_SESSION['user_id'];
                                $query = "SELECT * FROM cart WHERE user_id = $user_id";
                                $run = mysqli_query($conn, $query);

                                $totalPrice = 0;

                                if (mysqli_num_rows($run) > 0) {
                                    foreach ($run as $cart) {
                                        $totalPrice += $cart['price'] * $cart['quantity'];
                            ?>
                                <tr class="text-center">
                                    <td><?= $cart['id']; ?></td>
                                    <td><?= htmlspecialchars($cart['name']); ?></td>
                                    <td><?= htmlspecialchars($cart['price']); ?></td>
                                    <td><?= htmlspecialchars($cart['quantity']); ?></td>
                                </tr>
                            <?php
                                    }
                                }
                            ?>
                        </tbody>
                        <tfoot class="table-light" id="tfoot">
                            <tr class="text-center">
                                <td colspan="3"><strong>Total:</strong></td>
                                <td><strong>₱<?= number_format($totalPrice, 2); ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>  
                </div> 

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="checkout.php" class="btn gotocart">Go to Checkout</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
