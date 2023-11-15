<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
    header('location:login.php');
 };
 
 if(isset($_POST['add_to_wishlist'])){
 
     $product_id = $_POST['product_id'];
     $product_name = $_POST['product_name'];
     $product_price = $_POST['product_price'];
     $product_image = $_POST['product_image'];
     
     $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
 
     $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
 
     if(mysqli_num_rows($check_wishlist_numbers) > 0){
         $message[] = 'already added to wishlist';
     }elseif(mysqli_num_rows($check_cart_numbers) > 0){
         $message[] = 'already added to cart';
     }else{
         mysqli_query($conn, "INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_image')") or die('query failed');
         $message[] = 'product added to wishlist';
     }
 
 }
 
 if(isset($_POST['add_to_cart'])){
 
     $product_id = $_POST['product_id'];
     $product_name = $_POST['product_name'];
     $product_price = $_POST['product_price'];
     $product_image = $_POST['product_image'];
     $product_quantity = $_POST['product_quantity'];
 
     $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
 
     if(mysqli_num_rows($check_cart_numbers) > 0){
         $message[] = 'sudah ditambahkan ke troli';
     }else{
 
         $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
 
         if(mysqli_num_rows($check_wishlist_numbers) > 0){
             mysqli_query($conn, "DELETE FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
         }
 
         mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
         $message[] = 'produk ditambahkan ke troli';
     }
 
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Table Details</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="quick-view">

    <h1 class="title">Table Details</h1>

    <?php  
        if(isset($_GET['pid'])){
            $pid = $_GET['pid'];
            $select_table = mysqli_query($conn, "SELECT * FROM `tables` where id_table = '$pid'") or die('query failed');

            if(mysqli_num_rows($select_table) > 0){
               while($fetch_table = mysqli_fetch_assoc($select_table)){
    ?>
   <form action="" method="POST" class="box">
         <img src="uploaded_img/<?php echo $fetch_table['image']; ?>" alt="" class="image">
         <div class="name"><?php echo $fetch_table['name']; ?></div>1`  
         <input type="hidden" name="product_id" value="<?php echo $fetch_table['id_table']; ?>">
         <input type="hidden" name="product_name" value="<?php echo $fetch_table['name']; ?>">
         <input type="hidden" name="product_image" value="<?php echo $fetch_table['image']; ?>">
         <input type="submit" value="Reservasi" name="add_to_cart" class="btn">
      </form>
    <?php
                }
            } else {
                echo '<p class="empty">No table details available!</p>';
            }
        }
    ?>

    <div class="more-btn">
        <a href="home.php" class="option-btn">Go to Home Page</a>
    </div>

</section>

<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
