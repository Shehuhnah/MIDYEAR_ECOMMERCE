<?php

include 'db.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};


if(isset($_POST['add_product'])) {
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = mysqli_real_escape_string($conn, $_POST['price']);
   $details = mysqli_real_escape_string($conn, $_POST['details']);
   
   // Handling multiple images
   $image_01 = $_FILES['image_01']['name'];
   $image_02 = $_FILES['image_02']['name'];
   $image_03 = $_FILES['image_03']['name'];

   // Adjusted paths based on your folder structure
   $target_01 = '../try/uploaded_img/' . basename($image_01);
   $target_02 = '../try/uploaded_img/' . basename($image_02);
   $target_03 = '../try/uploaded_img/' . basename($image_03);

   if(move_uploaded_file($_FILES['image_01']['tmp_name'], $target_01) &&
      move_uploaded_file($_FILES['image_02']['tmp_name'], $target_02) &&
      move_uploaded_file($_FILES['image_03']['tmp_name'], $target_03)) {

       // Prepare paths for database storage
       $imagePath_01 = 'uploaded_img/' . basename($image_01);
       $imagePath_02 = 'uploaded_img/' . basename($image_02);
       $imagePath_03 = 'uploaded_img/' . basename($image_03);


       $query = "INSERT INTO products (name, details, price, image_01, image_02, image_03) VALUES (?, ?, ?, ?, ?, ?)";
       $stmt = $conn->prepare($query);
       $stmt->bind_param("ssisss", $name, $details, $price, $imagePath_01, $imagePath_02, $imagePath_03);

       if($stmt->execute()) {
           $_SESSION['alertproduct'] = "Product added successfully.";
           header("Location: products.php");
           exit();
       } else {
           $_SESSION['alertproduct'] = "Failed to add product.";
       }

       $stmt->close();
   } else {
       $_SESSION['alertproduct'] = "Failed to upload images.";
   }
}



if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    // Prepare and execute the statement to get product images
    $delete_product_image = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $delete_product_image->bind_param("i", $delete_id); // 'i' denotes an integer
    $delete_product_image->execute();

    // Get the result
    $result = $delete_product_image->get_result();
    $fetch_delete_image = $result->fetch_assoc(); // Use fetch_assoc() to get an associative array

    // Check if fetch_delete_image is not empty
    if ($fetch_delete_image) {
        // Unlink the images from the filesystem
        if (file_exists('../uploaded_img/' . $fetch_delete_image['image_01'])) {
            unlink('../uploaded_img/' . $fetch_delete_image['image_01']);
        }
        if (file_exists('../uploaded_img/' . $fetch_delete_image['image_02'])) {
            unlink('../uploaded_img/' . $fetch_delete_image['image_02']);
        }
        if (file_exists('../uploaded_img/' . $fetch_delete_image['image_03'])) {
            unlink('../uploaded_img/' . $fetch_delete_image['image_03']);
        }
    }

    // Prepare and execute delete statements
    $delete_product = $conn->prepare("DELETE FROM products WHERE id = ?");
    $delete_product->bind_param("i", $delete_id); // 'i' denotes an integer
    $delete_product->execute();

    $delete_cart = $conn->prepare("DELETE FROM cart WHERE pid = ?");
    $delete_cart->bind_param("i", $delete_id); // 'i' denotes an integer
    $delete_cart->execute();

    $delete_wishlist = $conn->prepare("DELETE FROM wishlist WHERE pid = ?");
    $delete_wishlist->bind_param("i", $delete_id); // 'i' denotes an integer
    $delete_wishlist->execute();

    // Redirect after deletion
    header('Location: products.php');
    exit(); // Important to call exit after header redirection
}

?>
<style>
    .delete-btn{
        background-color: #543310;
    }

</style>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="admin/e-commerce/css/admin_style.css">

</head>
<body>

<?php include 'admin/e-commerce/components/admin_header.php'; ?>


<section class="add-products" style="background-color: #F8F4E1; margin-top: 130px;">

    

   <h1 class="heading">add product</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>product name (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="enter product name" name="name">
         </div>
         <div class="inputBox">
            <span>product price (required)</span>
            <input type="number" min="0" class="box" required max="9999999999" placeholder="enter product price" onkeypress="if(this.value.length == 10) return false;" name="price">
         </div>
        <div class="inputBox">
            <span>image 01 (required)</span>
            <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>
        <div class="inputBox">
            <span>image 02 (required)</span>
            <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>
        <div class="inputBox">
            <span>image 03 (required)</span>
            <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
        </div>
         <div class="inputBox">
            <span>product details (required)</span>
            <textarea name="details" placeholder="enter product details" class="box" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
      </div>
      
      <input type="submit" value="add product" class="btn" name="add_product" style="background-color: #543310">
   </form>

</section>

<section class="show-products" style="background-color:#74512D">
    <h1 class="heading" style="color:aliceblue">Products Added</h1>
    <div class="box-container" >
        <?php
        $select_products = $conn->query("SELECT * FROM `products`");
        if ($select_products->num_rows > 0) {
            while ($fetch_products = $select_products->fetch_assoc()) {
        ?>
        <div class="box">
            <img src="<?= $fetch_products['image_01']; ?>" alt="">
            <div class="name"><?= htmlspecialchars($fetch_products['name']); ?></div>
            <div class="price"> &#8369 <span><?= htmlspecialchars($fetch_products['price']); ?></span>/-</div>
            <div class="details"><span><?= htmlspecialchars($fetch_products['details']); ?></span></div>
            <div class="flex-btn">
                <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn" style="background-color: #543310">Update</a>
                <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" style="background-color: #F8F4E1; color:black" onclick="return confirm('Delete this product?');">Delete</a>
            </div>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">No products added yet!</p>';
        }
        ?>
    </div>
</section>






<script src="../js/admin_script.js"></script>
   
</body>
</html>
<style>
