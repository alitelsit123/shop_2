<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}

if (isset($_POST['update_drink'])) {

    $update_d_id = $_POST['update_d_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $details = mysqli_real_escape_string($conn, $_POST['details']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    mysqli_query($conn, "UPDATE `drinks` SET name = '$name', details = '$details', price = '$price', status = '$status' WHERE id_drinks = '$update_d_id'") or die('query failed');

    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;
    $old_image = $_POST['update_d_image'];

    if (!empty($image)) {
        if ($image_size > 2000000) {
            $message[] = 'ukuran file gambar terlalu besar!';
        } else {
            mysqli_query($conn, "UPDATE `drinks` SET image = '$image' WHERE id_drinks = '$update_d_id'") or die('query failed');
            move_uploaded_file($image_tmp_name, $image_folder);
            unlink('uploaded_img/' . $old_image);
            $message[] = 'gambar berhasil diperbarui!';
        }
    }
    $message[] = 'Munuman  berhasil diperbarui!';

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Drink</title>

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
        $select_drinks = mysqli_query($conn, "SELECT * FROM `drinks` WHERE id_drinks = '$update_id'") or die('query failed');
        if (mysqli_num_rows($select_drinks) > 0) {
            while ($fetch_drinks = mysqli_fetch_assoc($select_drinks)) {
        ?>

                <form action="" method="post" enctype="multipart/form-data">
                    <img src="uploaded_img/<?php echo $fetch_drinks['image']; ?>" class="image" alt="">
                    <input type="hidden" value="<?php echo $fetch_drinks['id_drinks']; ?>" name="update_d_id">
                    <input type="hidden" value="<?php echo $fetch_drinks['image']; ?>" name="update_d_image">
                    <input type="text" class="box" value="<?php echo $fetch_drinks['name']; ?>" required placeholder="update drink name" name="name">
                    <input type="number" min="0" class="box" value="<?php echo $fetch_drinks['price']; ?>" required placeholder="update drink price" name="price">
                    <select name="status" class="box" id="">
                      <option value="available" <?= $fetch_drinks['status'] == 'available' ? 'selected':'' ?>>Tersedia</option>
                      <option value="unavailable" <?= $fetch_drinks['status'] == 'unavailable' ? 'selected':'' ?>>Tidak Tersedia</option>
                    </select>
                    <textarea name="details" class="box" required placeholder="update drink details" cols="30" rows="10"><?php echo $fetch_drinks['details']; ?></textarea>
                    <input type="file" accept="image/jpg, image/jpeg, image/png" class="box" name="image">
                    <input type="submit" value="update drink" name="update_drink" class="btn">
                    <a href="admin_products.php" class="option-btn">kembali</a>
                </form>

        <?php
            }
        } else {
            echo '<p class="kosong">tidak ada pembaruan minuman pilih</p>';
        }
        ?>

    </section>

    <script src="js/admin_script.js"></script>

</body>

</html>
