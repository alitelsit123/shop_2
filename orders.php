<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Order</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>Pesanan Anda</h3>
    <p> <a href="home.php">home</a> / order </p>
</section>

<section class="placed-orders">

    <h1 class="title">Memesan</h1>

    <div class="box-container">

    <?php
        $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('query failed');
        if(mysqli_num_rows($select_orders) > 0){
            while($fetch_orders = mysqli_fetch_assoc($select_orders)){
    ?>
    <div class="box">
        <p> tanggal order : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
        <p> tanggal reservasi : <span><?php echo $fetch_orders['booking_date'] ?? '-'; ?></span> </p>
        <p> waktu reservasi : <span><?php echo $fetch_orders['booking_time'] ?? '-'; ?></span> </p>
        <p> nama : <span><?php echo $fetch_orders['name']; ?></span> </p>
        <p> nomor : <span><?php echo $fetch_orders['number']; ?></span> </p>
        <p> email : <span><?php echo $fetch_orders['email']; ?></span> </p>
        <p> alamat : <span><?php echo $fetch_orders['address']; ?></span> </p>
        <p> metode pembayaran : <span><?php echo $fetch_orders['method']; ?></span> </p>
        <?php
        $listString = $fetch_orders['total_products'];
        // Remove spaces and split the string into an array using ","
        $itemArray = explode(",", str_replace(" ", "", $listString));

        $resultMeja = [];
        $resultProduct = [];

        // Loop through each item and check if it contains "Meja"
        foreach ($itemArray as $item) {
            if (strpos($item, "Meja") !== false) {
                $resultMeja[] = $item;
            } else {
              $resultProduct[] = $item;
            }
        }
        ?>
        <p> orderan anda : <span><?php echo implode(', ', $resultProduct); ?></span> </p>
        <p> nomor meja : <span>
          <?php 
          $resultMejaNumber = [];
          foreach ($resultMeja as $m) {
            if (preg_match('/Meja(\d+)\(\d+\)/', $item, $matches)) {
              $resultMejaNumber[] = $matches[1];
            }
          }
          if (sizeof($resultMejaNumber) > 0) {
            echo implode(', ', $resultMejaNumber);
          } else {
            echo '-';
          }
          ?>
          </span> </p>
        <p> total harga : <span>Rp.<?php echo $fetch_orders['total_price']; ?>/-</span> </p>
        <p> status pembayaran : <span style="color:<?php if($fetch_orders['payment_status'] == 'pending'){echo 'tomato'; }else{echo 'green';} ?>"><?php echo $fetch_orders['payment_status']; ?></span> </p>
    </div>
    <?php
        }
    }else{
        echo '<p class="Kosong">belum ada pesanan!</p>';
    }
    ?>
    </div>

</section>







<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>