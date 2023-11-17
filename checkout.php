<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['order'])){

  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $number = mysqli_real_escape_string($conn, $_POST['number']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $method = mysqli_real_escape_string($conn, $_POST['method']);
  $address = mysqli_real_escape_string($conn, $_POST['street'].', '. $_POST['city'].', '. $_POST['state'].' - '. $_POST['pin_code']);
  $placed_on = date('d-M-Y');
  $book_date = mysqli_real_escape_string($conn, $_POST['reservation_date']);
  $book_time = mysqli_real_escape_string($conn, $_POST['reservation_time']);

  $cart_total = 0;
  $cart_products = [];

  $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
  if (mysqli_num_rows($cart_query) > 0) {
      while ($cart_item = mysqli_fetch_assoc($cart_query)) {
          if ($cart_item['quantity'] > 0) {
              // Only include items with a quantity greater than 0
              $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ') ';
              $sub_total = ($cart_item['price'] * $cart_item['quantity']);
              $cart_total += $sub_total;
          }
      }
  }

  $total_products = implode(',', $cart_products);
  $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

  if($cart_total == 0){
      $message[] = 'your cart is empty!';
  }elseif(mysqli_num_rows($order_query) > 0){
      $message[] = 'order placed already!';
  }else{
      mysqli_query($conn, "INSERT INTO `booked`(user_id, name,  address,total_products,  placed_on,booking_date,booking_time) VALUES('$user_id', '$name', '$address','$total_products',  '$placed_on','$book_date','$book_time')") or die('query failed');
      mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on,booking_date,booking_time) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on','$book_date','$book_time')") or die('query failed');
      mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      $message[] = 'order placed successfully!';
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>checkout pesanan</h3>
    <p> <a href="home.php">home</a> / checkout </p>
</section>

<section class="display-order">
    <?php
        $grand_total = 0;
        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
    ?>    
    <p> <?php echo $fetch_cart['name'] ?> <span>(<?php echo '$'.$fetch_cart['price'].'/-'.' x '.$fetch_cart['quantity']  ?>)</span> </p>
    <?php
        }
        }else{
            echo '<p class="kosong">keranjang Anda kosong</p>';
        }
    ?>
    <div class="grand-total">Total Keseluruhan : <span>Rp.<?php echo $grand_total; ?>/-</span></div>
</section>

<section class="checkout">

    <form action="" method="POST">

        <h3>Data Reservasi</h3>

        <div class="flex">
            <div class="inputBox">
                <span>Nama :</span>
                <input type="text" name="name" placeholder="masukkan nama">
            </div>
            <div class="inputBox">
                <span>Nomor  :</span>
                <input type="number" name="number" min="0" placeholder="masukkan nomor Anda">
            </div>
            <div class="inputBox">
                <span>Email :</span>
                <input type="email" name="email" placeholder="masukkan email Anda">
            </div>
            <div class="inputBox">
                <span>Metode Pembayaran :</span>
                <select name="method">
                    <option value="cod">COD</option>
                    <option value="bca">Transfer BCA</option>
                </select>
            </div>
            <div class="inputBox">
                <span>Alamat :</span>
                <input type="text" name="street" placeholder="masukkan alamat">
            </div>
            <div class="inputBox">
                <span>Kota :</span>
                <input type="text" name="city" placeholder="masukkan kota">
            </div>
            <div class="inputBox">
                <span>Provinsi :</span>
                <input type="text" name="state" placeholder="masukkan provinsi">
            </div>
            <div class="inputBox">
                <span>Kode Pos :</span>
                <input type="number" min="0" name="pin_code" placeholder="masukkan kode pos">
            </div>
                <div class="inputBox">
                <span>Hari :</span>
                  <input id="datepicker1" type="date" name="reservation_date" class="form-control" placeholder="Date" required="">
            </div>
                <div class="inputBox">
                <span>Waktu :</span>
                  <select type="time" name="reservation_time" class="form-control" placeholder="Heure" id="time" required="">
                    <option value=""> -Select- </option>
                    <option value="10:00">08:00</option>
                    <option value="10:00">08:30</option>
                    <option value="10:00">09:00</option>
                    <option value="10:00">09:30</option>
                    <option value="10:00">10:00</option>
                    <option value="10:30">10:30</option>
                    <option value="11:00">11:00</option>
                    <option value="11:30">11:30</option>
                    <option value="12:00">12:00</option>
                    <option value="12:30">12:30</option>
                    <option value="13:00">13:00</option>
                    <option value="13:30">13:30</option>
                    <option value="14:00">14:00</option>
                    <option value="14:30">14:30</option>
                    <option value="15:00">15:00</option>
                    <option value="15:30">15:30</option>
                    <option value="16:00">16:00</option>
                    <option value="16:30">16:30</option>
                    <option value="17:00">17:00</option>
                    <option value="17:30">17:30</option>
                    <option value="18:00">18:00</option>
                    <option value="18:30">18:30</option>
                    <option value="19:00">19:00</option>
                    <option value="19:30">19:30</option>
                    <option value="20:00">20:00</option>
                    <option value="20:30">20:30</option>
                    <option value="21:00">21:00</option>
                    <option value="21:30">21:30</option>
                    <option value="22:00">22:00</option>
                    <option value="22:30">22:30</option>
                    <option value="23:00">23:00</option>
                    <option value="23:30">23:30</option>
                    <option value="00:00">00:00</option>
                    <option value="00:30">00:30</option>
                    <option value="01:00">01:00</option>
                    <option value="01:30">01:30</option>
                  </select>
                  <div class="validation"></div>
                </div>



        </div>

        <input type="submit" name="order" value="Checkout Sekarang" class="btn">

    </form>

</section>






<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>