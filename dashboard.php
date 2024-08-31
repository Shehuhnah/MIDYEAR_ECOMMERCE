<?php

include 'db.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header("location: admin_login.php");
   exit(); // It's a good practice to exit after a redirect
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
   <link rel="stylesheet" href="admin/e-commerce/css/admin_style.css">

</head>
<body>

<style>
   @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
   /* Center items in the navbar */
   #uniqueNavbar .navbar-nav {
      margin-left: auto;
      margin-right: auto;


   }
   #uniqueNavbar .navbar-brand{
      font-family: "Poppins", sans-serif;
      font-weight: 500;
      font-size: 1.7rem;
      color: aliceblue;
   }

   #uniqueNavbar .navbar-nav .nav-item {
      margin-left: 15px; /* Adjust spacing between items */
   }

   #uniqueNavbar .navbar-nav .nav-link {
      font-family: "Poppins", sans-serif;
      font-weight: 500;
      font-size: 2.3rem;
      color: aliceblue;
   }

   /* Center the form within the navbar */
   #uniqueNavbar .d-flex {
         margin-left: auto;
         margin-right: auto;
   }



   .dropdown-item{
      font-family: "Poppins", sans-serif;
      font-weight: 400;
      font-size: 1.4rem;
   }
   .btn{
      background-color: #2C1A11;
   }
   .box:hover .btn:hover {
      background-color: #543310; 
      color: aliceblue;
   }
   .navbar-toggler {
      width: 50px; 
      height: 40px; 
      padding: 0;
      font-size: 20px; 
   }
      

</style>
<body>
   <div class="container-fluid" style="height: 100vh; background-color: #F8F4E1">
      <div class="row" style="background-color: #74512D;">
         <div class="col-12" style="background-color: #2C1A11;">
            <nav id="uniqueNavbar" class="navbar navbar-expand-lg">
               <div class="container-fluid">
               <a class="navbar-brand" style="background-color: #2C1A11;" href="dashboard.php">Admin Side</a>
               <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <i class="fa fa-bars"></i> <!-- Font Awesome icon -->
               </button>
               <div class="collapse navbar-collapse" style="background-color: #2C1A11;" id="navbarSupportedContent">
                  <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                     <li class="nav-item">
                     <a class="nav-link" aria-current="page" href="#">Home</a>
                     </li>
                   
                     <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Others
                     </a>
                     <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="dashboard.php">Home</a></li>
                        <li><a class="dropdown-item" href="products.php">Product</a></li>
                        <li><a class="dropdown-item" href="placed_orders.php">Orders</a></li>
                        <li><a class="dropdown-item" href="admin_accounts.php">Admin</a></li>
                        <li><a class="dropdown-item" href="users_accounts.php">Users</a></li>
                        <li><a class="dropdown-item" href="messages.php">Messages</a></li>
                     </ul>
                     </li>
                  </ul>
               </div>
            </div>
         </nav>
      </div>
   </div>


   <section class="dashboard mt-5">
      <h1 class="heading">dashboard</h1>
      <div class="box-container">
         <div class="box">
               <h3>welcome!</h3>
               <p><?= $_SESSION['adminname']?></p>
               <a href="update_profile.php" class="btn">update profile</a>
         </div>

         <div class="box">
            <?php
               $total_pendings = 0;
               $select_pendings = $conn->prepare("SELECT * FROM `order_history` WHERE payment_status = ?");
               $select_pendings->bind_param("s", $status);
               $status = 'complete';
               $select_pendings->execute();
               $result = $select_pendings->get_result();
               if($result->num_rows > 0){
                  while($fetch_pendings = $result->fetch_assoc()){
                     $total_pendings += $fetch_pendings['total_price'];
                  }
               }
            ?>
            <h3><span>&#8369</span><?= number_format($total_pendings , 2); ?><span>/-</span></h3>
            <p>Total Earnings</p>
            <a href="orderhistory.php" class="btn" >see orders</a>
            
         </div>

         <div class="box">
         <?php
            $total_completes = 0;
            $status = 'complete';

            // Prepare the SQL statement
            $select_completes = $conn->prepare("SELECT COUNT(*) AS completed_orders FROM `order_history` WHERE payment_status = ?");
            $select_completes->bind_param("s", $status);
            $select_completes->execute();
            $result = $select_completes->get_result();

            if ($result->num_rows > 0) {
               $fetch_completes = $result->fetch_assoc();
               $total_completes = $fetch_completes['completed_orders'];
            }

            $select_completes->close();
            ?>

            <h3><?= $total_completes; ?></h3>
            <p>Completed Orders</p>
            <a href="completeorders.php" class="btn">see orders</a>
         </div>

         <div class="box">
            <?php
               $select_orders = $conn->prepare("SELECT * FROM `orders`");
               $select_orders->execute();
               $result = $select_orders->get_result();
               $number_of_orders = $result->num_rows; // Use num_rows instead of rowCount
            ?>
            <h3><?= $number_of_orders; ?></h3>
            <p>Orders Placed</p>
            <a href="placed_orders.php" class="btn">See Orders</a>
         </div>

         <div class="box">
            <?php
               $select_products = $conn->prepare("SELECT * FROM `products`");
               $select_products->execute();
               $result = $select_products->get_result();
               $number_of_products = $result->num_rows; // Use num_rows instead of rowCount
            ?>
            <h3><?= $number_of_products; ?></h3>
            <p>products added</p>
            <a href="products.php" class="btn">See Products</a>
         </div>

         <div class="box">
            <?php
               $select_users = $conn->prepare("SELECT * FROM `signup`");
               $select_users->execute();
               $result = $select_users->get_result();
               $number_of_users = $result->num_rows; // Use num_rows instead of rowCount
            ?>
            <h3><?= $number_of_users; ?></h3>
            <p>Normal Users</p>
            <a href="users_accounts.php" class="btn">See Users</a>
         </div>

         <div class="box">
            <?php
               $select_admins = $conn->prepare("SELECT * FROM `admins`");
               $select_admins->execute();
               $result = $select_admins->get_result();
               $number_of_admins = $result->num_rows; // Use num_rows instead of rowCount
            ?>
            <h3><?= $number_of_admins; ?></h3>
            <p>Admin Users</p>
            <a href="admin_accounts.php" class="btn">See Admins</a>
         </div>

         <div class="box">
            <?php
               $select_messages = $conn->prepare("SELECT * FROM `messages`");
               $select_messages->execute();
               $result = $select_messages->get_result();
               $number_of_messages = $result->num_rows; // Use num_rows instead of rowCount
            ?>
            <h3><?= $number_of_messages; ?></h3>
            <p>new messages</p>
            <a href="messages.php" class="btn">see messages</a>
         </div>
      </div>
   </section>
</div>



<script src="../js/admin_script.js"></script>
   
</body>

</html>