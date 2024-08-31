<?php

include 'db.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_user = $conn->prepare("DELETE FROM `users` WHERE id = ?");
   $delete_user->execute([$delete_id]);
   $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
   $delete_orders->execute([$delete_id]);
   $delete_messages = $conn->prepare("DELETE FROM `messages` WHERE user_id = ?");
   $delete_messages->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:users_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>users accounts</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   <link rel="stylesheet" href="admin/e-commerce/css/admin_style.css">

   <style>
            /* styles.css */

      /* Styles for the table header */
      .custom-table-header {
         background-color: #2C1A11; /* Dark gray background */
         color: #fff; /* White font color */
         font-size: 2rem;
      }

      /* Style for the table header fonts */
      .custom-table-header th {
         color: #fff; /* Gold font color */
         background-color: #2C1A11;
      }
      tbody tr td{
         font-size: 1.9rem;
         color: black;
         text-align: center;
      }
      /* Add a colored border to the entire table */
      .table-bordered {
         border: 1px solid #AF8F6F; /* Orange border color */
      }

      .table-bordered td, .table-bordered th {
         border: 1px solid #AF8F6F; /* Orange border color for table cells */
      }
      .table-group-divider{
         background-color: #F8F4E1;

      }
   </style>
  
</head>
<body>
<?php include 'admin/e-commerce/components/admin_header.php'; ?>

   <div class="container-fluid">
      <section class="orders" style="margin-top: 130px;">
         <h1 class="heading">Customer Accounts</h1>
         <div class="container-fluid">
            <div class="row no-gutters">
               <div class="col-lg-12">
                  <div class="container-fluid">
                     <table class="table table-bordered table-hover">
                        <thead class="custom-table-header">
                              <tr class="text-center">
                                 <th>Username</th>
                                 <th>Name</th>
                                 <th>Email</th>
                              </tr>
                        </thead>
                        <tbody class="table-group-divider">
                              <?php
                                 $query = "SELECT * FROM signup";
                                 $run = mysqli_query($conn, $query);
      
                                 if (mysqli_num_rows($run) > 0){
                                    foreach($run as $orders){
                              ?>
                                    <form action="" method="post">
                                          <tr class="text-center">
                                             <td><?= $orders ['username']; ?></td>
                                             <td><?= $orders ['firstname'] . $orders['lastname']; ?></td>
                                             <td><?= $orders ['email']; ?></td>
                                          </tr>
                                    </form>
                              <?php
                                    }
                                 }else{
                                    echo '<h5> No Records of Orders Yet </h5>';
                                 }
                              ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </section>
   </div>
<script src="../js/admin_script.js"></script>
</body>
</html>