<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['update_order'])){
   $order_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   mysqli_query($conn, "UPDATE `booked` SET payment_status = '$update_payment' WHERE id = '$order_id'") or die('query failed');
   $message[] = 'Reservation status has been updated!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `booked` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_booked.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'admin_header.php'; ?>

<section class="placed-orders">

   <h1 class="title">Tempat Order</h1>

   <div class="box-container">

      <?php
      
      $select_orders = mysqli_query($conn, "SELECT * FROM `booked`") or die('query failed');
      if(mysqli_num_rows($select_orders) > 0){
         while($fetch_orders = mysqli_fetch_assoc($select_orders)){
      ?>
      <div class="box">
         <p> user id : <span><?php echo $fetch_orders['user_id']; ?></span> </p>
         <p> Waktu : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
         <p> nama : <span><?php echo $fetch_orders['name']; ?></span> </p>
         <p> alamat : <span><?php echo $fetch_orders['address']; ?></span> </p>
         <p> total produk : <span><?php echo $fetch_orders['total_products']; ?></span> </p>
         <form action="" method="post">
            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
            <select name="update_payment">
               <option disabled selected><?php echo $fetch_orders['payment_status']; ?></option>
               <option value="pending">Pending</option>
               <option value="completed">Approved</option>
            </select>
            <input type="submit" name="update_order" value="update" class="option-btn">
            <a href="admin_booked.php?delete=<?php echo $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
         </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="kosong">belum ada pesanan!</p>';
      }
      ?>
   </div>

</section>













<script src="js/admin_script.js"></script>

</body>
</html>