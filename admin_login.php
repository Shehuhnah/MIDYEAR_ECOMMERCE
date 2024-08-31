<?php
include 'db.php';
session_start();

if (isset($_POST['submit'])) {
    // Sanitize inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $pass = htmlspecialchars(trim($_POST['pass']));

    // Prepare and execute the SQL statement
    $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ? AND password = ?");
    $select_admin->bind_param("ss", $name, $pass);
    $select_admin->execute();
    $result = $select_admin->get_result();
    $row = $result->fetch_assoc();

    if ($result->num_rows > 0) {
        // Login successful
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['adminname'] = $row['name'];
        header('Location: dashboard.php');
    } else {
        // Login failed
        echo 'Incorrect username or password!';
    }
} else {
    echo 'Please fill in the login form.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Login</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="admin-login.css">
</head>
<body>
   <div class="form-container sign-in-container">
      <form action="" method="post">
         <h1>LOG IN</h1>
         <span>ADMIN</span>
         <input type="text" name="name" placeholder="Name" required />
         <input type="password" name="pass" placeholder="Password" minlength="8" maxlength="16" required />
         <button type="submit" name="submit">Log in</button>
      </form>
   </div>
   <div class="overlay-container">
      <div class="overlay">
         <div class="overlay-panel overlay-left">
            <h1>Welcome Back!</h1>
            <p>To keep connected with us, please log in with your personal info.</p>
            <button class="ghost" id="signIn">Sign In</button>
         </div>
         <div class="overlay-panel overlay-right">
            <div class="row p-1 box-area" style="width: 55%; height:600px; border-radius: 30px">
               <div class="col-md-6 rounded-5 d-flex justify-content-center align-items-center flex-column left-box ">
                  <div class="featured-image">
                     <img src="loginpic.png" class="img-fluid" alt="lbox" id="lboximg">
                     <h1><b>Hello, Naurs!</b></h1>
                     <p><i>Find your own way, have an open spirit, and believe in your own beauty.</i></p>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</body>
</html>
