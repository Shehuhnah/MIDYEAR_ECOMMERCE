<?php
   include 'db.php';
   session_start();
   require 'PHPMailer-master/src/Exception.php';
    require 'PHPMailer-master/src/PHPMailer.php';
    require 'PHPMailer-master/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

   $admin_id = $_SESSION['admin_id'];

   if(!isset($admin_id)){
      header('location:admin_login.php');
   }

   if (isset($_POST['acceptbtn'])) {
      $order_id = $_POST['order_id'];
      $customer_id = $_POST['customer_id'];
      $name = $_POST['name'];
      $email = $_POST['email'];
      $method = $_POST['method'];
      $address = $_POST['address'];
      $total_products = $_POST['total_products'];
      $total_price = $_POST['total_price'];
  
      $insertQuery = "
      INSERT INTO order_history ( user_id, name, email, method, address, total_products, total_price, placed_on, payment_status)
      VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 'complete')
      ";
  
      $stmt = $conn->prepare($insertQuery);
      $stmt->bind_param('issssis', 
          $customer_id, 
          $name, 
          $email, 
          $method, 
          $address, 
          $total_products, 
          $total_price
      );
  
      $stmt->execute();
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
         $mail->Subject = 'Order Approved';
         $mail->Body    = "
            <h1>Your Order Has Been Approved!</h1>
            <p>Your order has been successfully approved and is now being prepared for shipment. 
            We will notify you once it's on its way. 
            Thank you for choosing us! ❤️</p>
            <p>Happy Palpitation!</p>
            <p>Here are the details:</p>
            <p>Total Amount: ₱" . number_format($total_price, 2) . "</p>
            <p>Mode of Payments:  $method </p>
            <p>Very Demure, Very Cutesy. </p>
         ";
         $mail->send();

         $deletequery = "DELETE FROM orders WHERE id = $order_id";
         $run = mysqli_query($conn, $deletequery);
         
         $deleteCartQuery = "DELETE FROM cart WHERE user_id = ?";
         $cartStmt = $conn->prepare($deleteCartQuery);
         $cartStmt->bind_param('i', $customer_id);
         $cartStmt->execute();
         $cartStmt->close();
         header("location: placed_orders.php");

         exit;
      } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
      }
   }

   if(isset($_POST['declinebtn'])){
         $order_id = $_POST['order_id'];
         $deleteQuery = "DELETE FROM orders WHERE id = ?";
         $stmt = $conn->prepare($deleteQuery);
         $stmt->bind_param('i', $order_id);
         $stmt->execute();
         $stmt->close();

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
        $mailSubject = 'Order Declined';
        $mailBody = "
            <h1>Your Order Has Been Declined</h1>
            <p>We regret to inform you that your order has been declined. 
            If you have any questions or need further assistance, 
            please contact us at your earliest convenience.</p>
            <p>Thank you for your understanding.</p>
        ";

        $mail->send();
        header("location: placed_orders.php");
        exit;
      } catch (Exception $e) {
         echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
      }
   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>placed orders</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
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
      }
      tbody tr td{
         font-size: 1.5rem;
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
   </style>

</head>
<body>
   <?php include 'admin/e-commerce/components/admin_header.php'; ?>

   <section class="orders" style="margin-top: 130px;">
      <h1 class="heading">placed orders</h1>
      <div class="container-fluid" id="bodymanageproduct">
         <div class="row no-gutters">
            <div class="col-12">
               <!-- start dito -->
               <div class="container">
                  <table class="table table-bordered table-dark table-hover">
                     <thead class="custom-table-header">
                           <tr class="text-center">
                              <!-- palitan ng field names sa signup, tanggalin ang excess -->
                              <th>Name</th>
                              <th>Email</th>
                              <th>Payment Method</th>
                              <th>Address</th>
                              <th>Total Quantity</th>
                              <th>Total Price</th>
                              <th>Transaction Date</th>
                              <th>Payment Status</th>
                              <th> Decline or Accept</th>

                           </tr>
                     </thead>
                     <tbody class="table-group-divider">
                           <?php
                           // palitan yung orders ng signup
                              $query = "SELECT * FROM orders";
                              $run = mysqli_query($conn, $query);
                              
                              if (mysqli_num_rows($run) > 0){
                                 foreach($run as $orders){
                           ?>
                                 <form action="" method="post">
                                       <!-- wag iinclude starting from these -->
                                       <input type="hidden" name="order_id" value="<?= $orders['id'];?>">
                                       <input type="hidden" name="customer_id" value="<?=$orders['user_id'];?>">
                                       <input type="hidden" name="name" value="<?= $orders['name'];?>">
                                       <input type="hidden" name="email" value="<?= $orders['email'];?>">
                                       <input type="hidden" name="method" value="<?= $orders['method'];?>">
                                       <input type="hidden" name="address" value="<?= $orders['address'];?>">
                                       <input type="hidden" name="total_products" value="<?= $orders['total_products'];?>">
                                       <input type="hidden" name="total_price" value="<?= $orders['total_price'];?>">
                                       <input type="hidden" name="placed_on" value="<?= $orders['placed_on'];?>">
                                       <input type="hidden" name="payment_status" value="<?= $orders['payment_status'];?>">
                                       <!-- to this -->

                                       <tr class="text-center">
                                          <!-- palita ng field name based sa signup -->
                                          <td><?= $orders ['name']; ?></td>
                                          <td><?= $orders ['email']; ?></td>
                                          <td><?= $orders ['method']; ?></td>
                                          <td><?= $orders ['address']; ?></td>
                                          <td><?= $orders ['total_products']; ?></td>
                                          <td><?= $orders ['total_price']; ?></td>
                                          <td><?= $orders ['placed_on']; ?></td>
                                          <td><?= $orders ['payment_status']; ?></td>

                                          <!-- wag na isama to sa signup -->
                                          <td>
                                             <button type="submit" name="acceptbtn" style="background-color: #543310;" class="btn btn-success m-1">Accept</button>
                                             <button type="submit" name="declinebtn" style="background-color: #AF8F6F" class="btn btn-danger m-1" >Decline</button>
                                          </td>
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
               <!-- ending dito -->
            </div>
         </div>
      </div>
   </section>
<script src="../js/admin_script.js"></script>
</body>
</html>