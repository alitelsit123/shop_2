<?php
@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['update_table'])){

   $update_t_id = $_POST['update_t_id'];
   $table_name = mysqli_real_escape_string($conn, $_POST['table_name']);
   $table_description = mysqli_real_escape_string($conn, $_POST['table_description']);

   mysqli_query($conn, "UPDATE `tables` SET name = '$table_name',  description = '$table_description' WHERE id_table = '$update_t_id'") or die('query failed');

   $table_image = $_FILES['table_image']['name'];
   $table_image_size = $_FILES['table_image']['size'];
   $table_image_tmp_name = $_FILES['table_image']['tmp_name'];
   $table_image_folder = 'uploaded_img/' . $table_image;
   $old_table_image = $_POST['update_t_image'];
   
   if(!empty($table_image)){
      if($table_image_size > 2000000){
         $message[] = 'ukuran file gambar terlalu besar!';
      }else{
         mysqli_query($conn, "UPDATE `tables` SET image = '$table_image' WHERE id_table = '$update_t_id'") or die('query failed');
         move_uploaded_file($table_image_tmp_name, $table_image_folder);
         unlink('uploaded_img/'.$old_table_image);
         $message[] = 'gambar berhasil diperbarui!';
      }
   }

   $message[] = 'tabel berhasil diperbarui!';

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Table</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'admin_header.php'; ?>

<section class="update-product">

<?php

   $update_id = $_GET['update'];
   $select_tables = mysqli_query($conn, "SELECT * FROM `tables` WHERE id_table = '$update_id'") or die('query failed');
   if(mysqli_num_rows($select_tables) > 0){
      while($fetch_tables = mysqli_fetch_assoc($select_tables)){
?>

<form action="" method="post" enctype="multipart/form-data">
   <img src="uploaded_img/<?php echo $fetch_tables['image']; ?>" class="image"  alt="">
   <input type="hidden" value="<?php echo $fetch_tables['id_table']; ?>" name="update_t_id">
   <input type="hidden" value="<?php echo $fetch_tables['image']; ?>" name="update_t_image">
   <input type="text" class="box" value="<?php echo $fetch_tables['name']; ?>" required placeholder="Update table name" name="table_name">
   <textarea name="table_description" class="box" required placeholder="Update table description" cols="30" rows="10"><?php echo $fetch_tables['description']; ?></textarea>
   <input type="file" accept="image/jpg, image/jpeg, image/png" class="box" name="table_image">
   <input type="submit" value="Update Table" name="update_table" class="btn">
   <a href="admin_products.php" class="option-btn">Kembali</a>
</form>

<?php
      }
   }else{
      echo '<p class="kosong">Tidak ada pembaruan tabel pilih</p>';
   }
?>

</section>

<script src="js/admin_script.js"></script>

</body>
</html>
