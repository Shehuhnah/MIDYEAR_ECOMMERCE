<?php
include 'db.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_admins = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
   $delete_admins->execute([$delete_id]);
   header('location:admin_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin accounts</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="admin/e-commerce/css/admin_style.css">

</head>
<body>

<?php include 'admin/e-commerce/components/admin_header.php'; ?>


<section class="accounts" style="margin-top: 130px;">

   <h1 class="heading">admin accounts</h1>

   <div class="box-container">

   <div class="box">
      <p>add new admin</p>
      <a href="register_admin.php" class="option-btn" style="background-color: #74512D">register admin</a>
   </div>

   <?php
   // Prepare the SQL query using MySQLi
      $select_accounts = $conn->prepare("SELECT * FROM `admins`");
      $select_accounts->execute();
      $result = $select_accounts->get_result(); // Get the result set

      if($result->num_rows > 0){
         while($fetch_accounts = $result->fetch_assoc()){   
   ?>
      <div class="box">
         <p> admin id : <span><?= $fetch_accounts['id']; ?></span> </p>
         <p> admin name : <span><?= $fetch_accounts['name']; ?></span> </p>
         <div class="flex-btn">
            <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('delete this account?')" class="delete-btn" style="background-color:#74512D">delete</a>
            <?php
               if($fetch_accounts['id'] == $admin_id){
                  echo '<a href="update_profile.php" class="option-btn" style="background-color: #F8F4E1; color:black">update</a>';
               }
            ?>
         </div>
      </div>
   <?php
         }
      }else{
         echo '<p class="empty">no accounts available!</p>';
      }
   ?>



   </div>

</section>

<script src="../js/admin_script.js"></script>
   
</body>
</html>