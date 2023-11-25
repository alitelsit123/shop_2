<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_product'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = mysqli_real_escape_string($conn, $_POST['price']);
   $details = mysqli_real_escape_string($conn, $_POST['details']);
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folter = 'uploaded_img/'.$image;

   $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');

   if(mysqli_num_rows($select_product_name) > 0){
      $message[] = 'nama produk sudah ada!';
   }else{
      $insert_product = mysqli_query($conn, "INSERT INTO `products`(name, details, price, image) VALUES('$name', '$details', '$price', '$image')") or die('query failed');

      if($insert_product){
         if($image_size > 2000000){
            $message[] = 'ukuran gambar terlalu besar!';
         }else{
            move_uploaded_file($image_tmp_name, $image_folter);
            $message[] = 'produk berhasil ditambahkan!';
         }
      }
   }

}


if (isset($_POST['add_drinks'])) {
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = mysqli_real_escape_string($conn, $_POST['price']);
   $details = mysqli_real_escape_string($conn, $_POST['details']);
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/' . $image;

   $select_drinks_name = mysqli_query($conn, "SELECT name FROM `drinks` WHERE name = '$name'") or die('query failed');

   if (mysqli_num_rows($select_drinks_name) > 0) {
       $message[] = 'nama minuman sudah ada!';
   } else {
       $insert_drink = mysqli_query($conn, "INSERT INTO `drinks`(name, details, price, image) VALUES('$name', '$details', '$price', '$image')") or die('query failed');

       if ($insert_drink) {
           if ($image_size > 2000000) {
               $message[] = 'ukuran gambar terlalu besar!';
           } else {
               move_uploaded_file($image_tmp_name, $image_folder);
               $message[] = 'minuman berhasil ditambahkan!';
           }
       }
   }
}

   if (isset($_POST['add_meja'])) {
      $name = mysqli_real_escape_string($conn, $_POST['name']);
      $description = mysqli_real_escape_string($conn, $_POST['description']);
      $image = $_FILES['image']['name'];
      $image_size = $_FILES['image']['size'];
      $image_tmp_name = $_FILES['image']['tmp_name'];
      $image_folder = 'uploaded_img/' . $image;
  
      $select_table_name = mysqli_query($conn, "SELECT name FROM `tables` WHERE name = '$name'") or die('Query failed');
  
      if (mysqli_num_rows($select_table_name) > 0) {
          $message[] = 'Nama tabel sudah ada!';
      } else {
          $insert_table = mysqli_query($conn, "INSERT INTO `tables` (name, description, image) VALUES ('$name', '$description', '$image')") or die('Query failed');
  
          if ($insert_table) {
              if ($image_size > 2000000) {
                  $message[] = 'Ukuran gambar terlalu besar!';
              } else {
                  move_uploaded_file($image_tmp_name, $image_folder);
                  $message[] = 'Tabel berhasil ditambahkan!';
              }
          }
      }
   }


   

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];


       $select_delete_image = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
       $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
       unlink('uploaded_img/' . $fetch_delete_image['image']);
       mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
       mysqli_query($conn, "DELETE FROM `wishlist` WHERE pid = '$delete_id'") or die('query failed');
       mysqli_query($conn, "DELETE FROM `cart` WHERE pid = '$delete_id'") or die('query failed');
       header('location:admin_products.php');
 
       $select_delete_image = mysqli_query($conn, "SELECT image FROM `drinks` WHERE id_drinks = '$delete_id'") or die('query failed');
       $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
       unlink('uploaded_img/' . $fetch_delete_image['image']);
       mysqli_query($conn, "DELETE FROM `wishlist` WHERE pid = '$delete_id'") or die('query failed');
       mysqli_query($conn, "DELETE FROM `cart` WHERE pid = '$delete_id'") or die('query failed');
       mysqli_query($conn, "DELETE FROM `drinks` WHERE id_drinks = '$delete_id'") or die('query failed');
       header('location:admin_products.php');
   
       $select_delete_image = mysqli_query($conn, "SELECT image FROM `tables` WHERE id_table = '$delete_id'") or die('query failed');
       $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
       unlink('uploaded_img/' . $fetch_delete_image['image']);
       mysqli_query($conn, "DELETE FROM `tables` WHERE id_table = '$delete_id'") or die('query failed');
       // You may need to add additional deletion queries for related entries in other tables
       header('location:admin_products.php');
   }


// ... (Your existing code)


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Produk</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<style>
      .add-products {
        display: flex;
        justify-content: space-between;
    }

    .add-products form {
      padding: -2rem;
        width: 100%; /* Adjust the width as needed */
        margin-bottom: 1rem; /* Added margin-bottom for spacing between forms */
    }
    
    .add-products form .boxdead {
      padding: -2rem;
      width: 100%; /* Adjust the width as needed */
      margin-bottom: 1rem; 
      background-color: var(--pink); 
      color: white; 
      width: 100%;
       margin:1rem 0;
      font-size: 1.8rem;
      color:var(--black);
      border-radius: .5rem;
      padding:1.2rem 1.4rem;
      border:var(--border);
    }
</style>
<body>
   
<?php @include 'admin_header.php'; ?>

<section class="add-products">

<?php if(in_array($_GET["v"], ['add'])): ?>
<form action="" method="POST" enctype="multipart/form-data">
    <h3>Tambah Menu Makanan</h3>
    <input type="text" class="box" required placeholder="masukkan nama makanan" name="name">
    <input type="number" min="0" class="box" required placeholder="masukkan harga makanan" name="price">
    <textarea name="details" class="box" required placeholder="masukkan detail makanan" cols="30" rows="10"></textarea>
    <input type="file" accept="image/jpg, image/jpeg, image/png" required class="box" name="image">
    <input type="submit" value="Tambah Produk" name="add_product" class="btn">
  </form>

  <form action="" method="POST" enctype="multipart/form-data">
        <h3>Tambah Menu Minuman</h3>
        <input type="text" class="box" required placeholder="masukkan nama minuman" name="name">
        <input type="number" min="0" class="box" required placeholder="masukkan harga minuman" name="price">
        <textarea name="details" class="box" required placeholder="masukkan detail minuman" cols="30" rows="10"></textarea>
        <input type="file" accept="image/jpg, image/jpeg, image/png" required class="box" name="image">
        <input type="submit" value="Tambah Minuman" name="add_drinks" class="btn">
    </form>
    <form action="" method="POST" enctype="multipart/form-data">
       <h3>Tambah Tempat</h3>
       <input type="text" class="box" required placeholder="Masukkan nama tabel" name="name">
       <input type="number" min="0" class="boxdead" required placeholder="" name="price" readonly>
       <textarea name="description" class="box" required placeholder="Masukkan deskripsi tabel" cols="30" rows="5"></textarea>
       <input type="file" accept="image/jpg, image/jpeg, image/png" required class="box" name="image">
       <input type="submit" value="Tambah Tabel" name="add_meja" class="btn">
   </form>

<?php endif; ?>
   


</section>
   
<?php if(isset($_GET["v"]) && $_GET["v"] == 'makan'): ?>

  

<section class="show-products">
<h1 class="title">Makanan </h1>

   <div class="box-container">

      <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
      <div class="box">
         <div class="price">Rp.<?php echo $fetch_products['price']; ?></div>
         <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_products['name']; ?></div>
         <div class="details"><?php echo $fetch_products['details']; ?></div>
         <a href="admin_update_product.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">update</a>
         <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>


</section>
<?php endif; ?>

<?php if(isset($_GET["v"]) && $_GET["v"] == 'minuman'): ?>

<section class="show-products">

   <h1 class="title">Minuman </h1>

   <div class="box-container">

      <?php
         $select_drinks = mysqli_query($conn, "SELECT * FROM `drinks`") or die('query failed');
         if(mysqli_num_rows($select_drinks) > 0){
            while($fetch_drinks = mysqli_fetch_assoc($select_drinks)){
      ?>
      <form action="" method="POST" class="box">
         <div class="price">Rp.<?php echo $fetch_drinks['price']; ?></div>
         <img class="image" src="uploaded_img/<?php echo $fetch_drinks['image']; ?>" alt="" >
         <div class="name"><?php echo $fetch_drinks['name']; ?></div>
         <div class="details"><?php echo $fetch_drinks['details']; ?></div>
         <a href="admin_update_drinks.php?update=<?php echo $fetch_drinks['id_drinks']; ?>" class="option-btn">update</a>
         <a href="admin_products.php?delete=<?php echo $fetch_drinks['id_drinks']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </form>
      <?php
         }
      }else{
         echo '<p class="kosong">belum ada minuman yang ditambahkan!</p>';
      }
      ?>

   </div>

</section>

<?php endif; ?>

<?php if(isset($_GET["v"]) && $_GET["v"] == 'table'): ?>


<section class="show-products">
<h1 class="title">Tempat </h1>

    <div class="box-container">

        <?php
        $select_tables = mysqli_query($conn, "SELECT * FROM `tables`") or die('Query failed');
        if (mysqli_num_rows($select_tables) > 0) {
            while ($fetch_tables = mysqli_fetch_assoc($select_tables)) {
        ?>
                <div class="box">
                    <img class="image" src="uploaded_img/<?php echo $fetch_tables['image']; ?>" alt="">
                    <div class="name"><?php echo $fetch_tables['name']; ?></div>
                    <div class="details"><?php echo $fetch_tables['description']; ?></div>
                    <!-- Update link, replace 'admin_update_drinks' with your actual update page for tables -->
                    <a href="admin_update_table.php?update=<?php echo $fetch_tables['id_table']; ?>" class="option-btn">update</a>
                    <!-- Delete link, replace 'admin_products' with your actual product management page for tables -->
                    <a href="admin_products.php?delete=<?php echo $fetch_tables['id_table']; ?>" class="delete-btn" onclick="return confirm('Delete this table?');">delete</a>
                </div>
        <?php
            }
        } else {
            echo '<p class="empty">No tables added yet!</p>';
        }
        ?>
    </div>
</section>


<?php endif; ?>




<script src="js/admin_script.js"></script>

</body>
</html>