<?php
   include 'db.php';
   session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Order History</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="admin/e-commerce/css/admin_style.css">

   <style>
        .custom-table-header {
            background-color: #2C1A11; 
            font-size: 1.8rem;
        }
        
        .custom-table-header th {
            color: #fff; 
        }
        tbody tr td{
            font-size: 1.6rem;
            color: black;
            text-align: center;
        }
        .table-bordered {
            border: 1px solid #AF8F6F; 
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #AF8F6F;
        }
   </style>

</head>
<body>
   <?php include 'admin/e-commerce/components/admin_header.php'; ?>

   <div class="container-fluid">
       <section class="orders" style="margin-top: 130px;">
          <h1 class="heading">Orders History</h1>
          <div class="container-fluid">
             <div class="row no-gutters">
                <div class="col-lg-12">
                   <div class="container-fluid">
                      <table class="table table-bordered table-hover">
                         <thead class="custom-table-header">
                               <tr class="text-center">
                                  <th>Name</th>
                                  <th>Email</th>
                                  <th>Payment Method</th>
                                  <th>Address</th>
                                  <th>Total Quantity</th>
                                  <th>Total Price</th>
                                  <th>Transaction Date</th>
                                  <th>Payment Status</th>
                               </tr>
                         </thead>
                         <tbody class="table-group-divider">
                               <?php
                                  $query = "SELECT * FROM order_history";
                                  $run = mysqli_query($conn, $query);
       
                                  if (mysqli_num_rows($run) > 0){
                                     foreach($run as $orders){
                               ?>
                                     <form action="" method="post">
                                           <tr class="text-center">
                                              <td><?= $orders ['name']; ?></td>
                                              <td><?= $orders ['email']; ?></td>
                                              <td><?= $orders ['method']; ?></td>
                                              <td><?= $orders ['address']; ?></td>
                                              <td><?= $orders ['total_products']; ?></td>
                                              <td><?= $orders ['total_price']; ?></td>
                                              <td><?= $orders ['placed_on']; ?></td>
                                              <td><?= $orders ['payment_status']; ?></td>
                                           </tr>
                                     </form>
                                     <?php
                                    }
                                }else{
                                echo '<h5> No Records of Orders Yet </h5>';
                                }
                            ?>
                         </tbody>
                         <tfoot>
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
                            <tr >
                                <td class="text-center" colspan="5"></td>
                                <td class="text-center" colspan="2" style="font-size:2.5rem; color:#2C1A11;">Total Earnings:</td>
                                <td class="text-center" style="font-size:2.5rem; color:#2C1A11; font-weight: 700"> ₱ <?= number_format($total_pendings,2); ?></td>
                            </tr>
                         </tfoot>
                        
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