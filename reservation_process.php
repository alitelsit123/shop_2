<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `reservasi` WHERE id = '$delete_id'") or die('query failed');
    header('location:reservation_process.php');
}

if(isset($_GET['delete_all'])){
    mysqli_query($conn, "DELETE FROM `reservasi` WHERE user_id = '$user_id'") or die('query failed');
    header('location:reservation_process.php');
};


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Keranjang Belanja</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>Reservasi</h3>
    <p> <a href="home.php">home</a> / Reservasi </p>
</section>

<section class="shopping-cart">

    <h1 class="title">Tambah Reservasi</h1>

    <div class="box-container">

    <?php
        $grand_total = 0;
        $select_cart = mysqli_query($conn, "SELECT * FROM `reservasi` WHERE user_id = '$user_id'") or die('query failed');
        if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
    ?>
    <div  class="box">
        <a href="reservation_process.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>
        <a href="view_page.php?pid=<?php echo $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
        <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="" class="image">
        <div class="name"><?php echo $fetch_cart['name']; ?></div>
        <form action="" method="post">
            <input type="hidden" value="<?php echo $fetch_cart['id']; ?>" name="cart_id">
           
        </form>
    </div>
    <?php
  
        }}

    ?>
    </div>

    <div class="cart-total">
        <a href="reservation.php" class="option-btn">Tambah Reservasi</a>
        <a href="reserved.php" class="btn">Lanjut</a>
    </div>

</section>






<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>