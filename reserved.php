<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['booked'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $address = mysqli_real_escape_string($conn, $_POST['street'].', ');
    $placed_on = date('d-M-Y');

    $cart_total = 0;
    $cart_products[] = '';


    $total_products = implode(', ',$cart_products);

    $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND  address = '$address' AND total_products = '$total_products' ") or die('query failed');
    
        mysqli_query($conn, "INSERT INTO `booked`(user_id, name,  address,total_products,  placed_on) VALUES('$user_id', '$name', '$address','$total_products',  '$placed_on')") or die('query failed');
        mysqli_query($conn, "DELETE FROM `reservasi` WHERE user_id = '$user_id'") or die('query failed');
        $message[] = 'Booked successfully!';
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
    <h3>Reservasi Meja</h3>
    <p> <a href="home.php">home</a> / checkout </p>
</section>

<

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
                <input type="number" name="number"  placeholder="masukkan nomor Anda">
            </div>
           
            <div class="inputBox">
                <span>Alamat :</span>
                <input type="text" name="street" placeholder="masukkan alamat">
            </div>
                <div class="inputBox">
                <span>Hari :</span>
                  <input id="datepicker1" type="date" name="reservation_date" class="form-control" placeholder="Date" required="">
            </div>
                <div class="inputBox">
                <span>Waktu :</span>
                  <select type="time" name="reservation_time" class="form-control" placeholder="Heure" id="time" required="">
                    <option value=""> -Select- </option>
                    <option value="10:00 h">08:00</option>
                    <option value="10:00 h">08:30</option>
                    <option value="10:00 h">09:00</option>
                    <option value="10:00 h">09:30</option>
                    <option value="10:00 h">10:00</option>
                    <option value="10:30 h">10:30</option>
                    <option value="11:00 h">11:00</option>
                    <option value="11:30 h">11:30</option>
                    <option value="12:00 h">12:00</option>
                    <option value="12:30 h">12:30</option>
                    <option value="13:00 h">13:00</option>
                    <option value="13:30 h">13:30</option>
                    <option value="14:00 h">14:00</option>
                    <option value="14:30 h">14:30</option>
                    <option value="15:00 h">15:00</option>
                    <option value="15:30 h">15:30</option>
                    <option value="16:00 h">16:00</option>
                    <option value="16:30 h">16:30</option>
                    <option value="17:00 h">17:00</option>
                    <option value="17:30 h">17:30</option>
                    <option value="18:00 h">18:00</option>
                    <option value="18:30 h">18:30</option>
                    <option value="19:00 h">19:00</option>
                    <option value="19:30 h">19:30</option>
                    <option value="20:00 h">20:00</option>
                    <option value="20:30 h">20:30</option>
                    <option value="21:00 h">21:00</option>
                    <option value="21:30 h">21:30</option>
                    <option value="22:00 h">22:00</option>
                    <option value="22:30 h">22:30</option>
                    <option value="23:00 h">23:00</option>
                    <option value="23:30 h">23:30</option>
                    <option value="00:00 h">00:00</option>
                    <option value="00:30 h">00:30</option>
                    <option value="01:00 h">01:00</option>
                    <option value="01:30 h">01:30</option>
                  </select>
                  <div class="validation"></div>
                </div>



        </div>

        <input type="submit" name="booked" value="Reservasi Sekarang" class="btn">

    </form>

</section>






<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>