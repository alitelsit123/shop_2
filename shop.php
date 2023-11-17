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
        $message[] = 'sudah ditambahkan ke wishlist';
    }elseif(mysqli_num_rows($check_cart_numbers) > 0){
        $message[] = 'sudah ditambahkan ke troli';
    }else{
        mysqli_query($conn, "INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_image')") or die('query failed');
        $message[] = 'produk ditambahkan ke wishlist';
    }

}

if(isset($_POST['add_to_cart'])){

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];
    $type = $_POST['type'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if(mysqli_num_rows($check_cart_numbers) > 0){
        $message[] = 'sudah ditambahkan ke troli';
    }else{

        $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

        if(mysqli_num_rows($check_wishlist_numbers) > 0){
            mysqli_query($conn, "DELETE FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
        }

        mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name, price, quantity, image,type) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image','$type')") or die('query failed');
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
   <title>shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>Toko Kami</h3>
    <p> <a href="home.php">home</a> / shop </p>
</section>

<section class="products">

   <h1 class="title">Produk Terbaru</h1>

   <div class="box-container">

      <?php
       $select_products = mysqli_query($conn, "SELECT * FROM `products`  ORDER BY id  DESC LIMIT 6") or die('query failed');
       if(mysqli_num_rows($select_products) > 0){
           while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
      <form action="" method="POST" class="box">
         <a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>" class="fas fa-eye"></a>
         <div class="price">Rp.<?php echo $fetch_products['price']; ?></div>
         <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" class="image">
         <div class="name" style="display: flex; align-items:center;justify-content:center;">
          <?php echo $fetch_products['name']; ?>
          <span style="<?= $fetch_products['status'] == 'unavailable' ? 'background-color: red; color:white;':'background-color: green; color:white;' ?> padding: .75rem;font-size: 1rem;margin-left: 1rem;border-radius: 7px;"><?php echo $fetch_products['status'] == 'available' ? 'Tersedia':'Tidak Tersedia'; ?></span>
         </div>
         <input type="number" <?= $fetch_products['status'] == 'available' ? '':'disabled' ?> name="product_quantity" value="<?= $fetch_products['status'] == 'available' ? '1':'0' ?>" min="0" class="qty">

         <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
         <input type="hidden" name="type" value="product">
         <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
         <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
         <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
         <?php if($fetch_products['status'] == 'available'): ?>
         <input type="submit" value="Tambah Ke Wishlist" name="add_to_wishlist" class="option-btn">
         <input type="submit" value="Tambah Ke Keranjang" name="add_to_cart" class="btn">
         <?php else: ?>
         <input type="button" value="Tambah Ke Wishlist" name="add_to_wishlist" class="option-btn" style="cursor: not-allowed; opacity: .7;">
         <input type="button" value="Tambah Ke Keranjang" name="add_to_cart" class="btn" style="cursor: not-allowed; opacity: .7;">
         <?php endif; ?>
      </form>
      <?php
         }
      }else{
         echo '<p class="Kosong">belum ada produk yang ditambahkan!</p>';
      }
      ?>

   </div>

   <div class="more-btn">
      <a href="shop.php" class="option-btn">load more</a>
   </div>

</section>


<section class="products" id="makanan">

   <h1 class="title">Makanan</h1>

   <div class="box-container">

      <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
      <form action="" method="POST" class="box">
         <a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>" class="fas fa-eye"></a>
         <div class="price">Rp.<?php echo $fetch_products['price']; ?></div>
         <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" class="image">
         <div class="name" style="display: flex; align-items:center;justify-content:center;">
          <?php echo $fetch_products['name']; ?>
          <span style="<?= $fetch_products['status'] == 'unavailable' ? 'background-color: red; color:white;':'background-color: green; color:white;' ?> padding: .75rem;font-size: 1rem;margin-left: 1rem;border-radius: 7px;"><?php echo $fetch_products['status'] == 'available' ? 'Tersedia':'Tidak Tersedia'; ?></span>
         </div>
         <input type="number" <?= $fetch_products['status'] == 'available' ? '':'disabled' ?> name="product_quantity" value="<?= $fetch_products['status'] == 'available' ? '1':'0' ?>" min="0" class="qty">

         <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
         <input type="hidden" name="type" value="product">
         <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
         <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
         <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
         <?php if($fetch_products['status'] == 'available'): ?>
         <input type="submit" value="Tambah Ke Wishlist" name="add_to_wishlist" class="option-btn">
         <input type="submit" value="Tambah Ke Keranjang" name="add_to_cart" class="btn">
         <?php else: ?>
         <input type="button" value="Tambah Ke Wishlist" name="add_to_wishlist" class="option-btn" style="cursor: not-allowed; opacity: .7;">
         <input type="button" value="Tambah Ke Keranjang" name="add_to_cart" class="btn" style="cursor: not-allowed; opacity: .7;">
         <?php endif; ?>
      </form>
      <?php
         }
      }else{
         echo '<p class="kosong">belum ada produk yang ditambahkan!</p>';
      }
      ?>

   </div>

</section>
<section class="products" id="minuman">

   <h1 class="title">Minuman </h1>

   <div class="box-container">

      <?php
         $select_drinks = mysqli_query($conn, "SELECT * FROM `drinks`") or die('query failed');
         if(mysqli_num_rows($select_drinks) > 0){
            while($fetch_drinks = mysqli_fetch_assoc($select_drinks)){
      ?>
      <form action="" method="POST" class="box" style="height:100%;">
         <a href="view_page_drinks.php?pid=<?php echo $fetch_drinks['id_drinks']; ?>" class="fas fa-eye"></a>
         <div class="price">Rp.<?php echo $fetch_drinks['price']; ?></div>
         <img src="uploaded_img/<?php echo $fetch_drinks['image']; ?>" alt="" class="image">
         <div class="name" style="display: flex; align-items:center;justify-content:center;">
          <?php echo $fetch_drinks['name']; ?>
          <span style="<?= $fetch_drinks['status'] == 'unavailable' ? 'background-color: red; color:white;':'background-color: green; color:white;' ?> padding: .75rem;font-size: 1rem;margin-left: 1rem;border-radius: 7px;"><?php echo $fetch_drinks['status'] == 'available' ? 'Tersedia':'Tidak Tersedia'; ?></span>
        </div>
         <input type="hidden" name="product_id" value="<?php echo $fetch_drinks['id_drinks']; ?>">
         <input type="hidden" name="type" value="product">
         <input type="hidden" name="product_name" value="<?php echo $fetch_drinks['name']; ?>">
         <input type="hidden" name="product_price" value="<?php echo $fetch_drinks['price']; ?>">
         <input type="hidden" name="product_image" value="<?php echo $fetch_drinks['image']; ?>">
         <input type="number" <?= $fetch_drinks['status'] == 'available' ? '':'disabled' ?> name="product_quantity" value="<?= $fetch_drinks['status'] == 'available' ? '1':'0' ?>" min="0" class="qty">
         <?php if($fetch_drinks['status'] == 'available'): ?>
         <input type="submit" value="Tambah Ke Wishlist" name="add_to_wishlist" class="option-btn">
         <input type="submit" value="Tambah Ke Keranjang" name="add_to_cart" class="btn">
         <?php else: ?>
         <input type="button" value="Tambah Ke Wishlist" name="add_to_wishlist" class="option-btn" style="cursor: not-allowed; opacity: .7;">
         <input type="button" value="Tambah Ke Keranjang" name="add_to_cart" class="btn" style="cursor: not-allowed; opacity: .7;">
         <?php endif; ?>
         
      </form>
      <?php
         }
      }else{
         echo '<p class="kosong">belum ada minuman yang ditambahkan!</p>';
      }
      ?>

   </div>

</section>


<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>