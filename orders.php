<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['submitupload'])){

  $postId = mysqli_real_escape_string($conn, $_POST['id']);
  

  $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE id='$post_id'") or die('query failed');

  $root = "";

  if($order_query == 0){
      $message[] = 'cant upload!';
  }else{
    if (isset($_FILES["eot"]) && $_FILES["eot"]["error"] == 0) {
      // Extract relevant information from the uploaded file
      $fileName = basename($_FILES["eot"]["name"]);
      $eotData = $root . "images/upload/" . $fileName; // Full path to the stored image

      // Move the uploaded file to the desired directory
      move_uploaded_file($_FILES["eot"]["tmp_name"], $eotData);

      // Update the database with the extracted information
      $orderId = 1; // Set the order_id to the specific order you want to update
      $eotData = $conn->real_escape_string($fileName); // Store the filename in the eot column

      $sql = "UPDATE orders SET eot = '$eotData' WHERE id = $postId"; // Assuming you have an order_id to identify the specific order

      if ($conn->query($sql) === TRUE) {
        $message[] = 'Bukti bayar dikirim!';
        // $_SESSION['uploaded_filename'] = $fileName;
      } else {
        $message[] = "Error updating record: " . $conn->error;
      }
    } else {
      $message[] = "Error uploading file.";
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Reservation</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

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

<div class="box-container mb-5">
  <div class="box">
    <div class="alert" role="alert">
      <h4 class="alert-heading">Cara Bayar</h4>
      <p>Kirim bukti transfer ke Whatsapp (081336644889) jika menggunakan transfer</p>
      <p>No.Rek BCA An. Iqbal Muhammad Firdaus</p>
      <p>
     <br /><img src="./images/qris.jpeg" alt="" srcset="" width="300px" height="300px">
      </p>
    </div>
  </div>
</div>

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
        <p> metode pembayaran : <span>
          <?php 
          if ($fetch_orders['method'] == 'cod') {
            echo 'Cash On Delivery';
          } else {
            echo 'Transfer '.$fetch_orders['method'];
          }
          ?>
        </span> </p>
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
            if (preg_match('/Meja\s?(\d+)\s?\(\d+\)/', $item, $matches)) {
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
        <?php
        if (!$fetch_orders["eot"]) {
        ?>
        <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $fetch_orders["id"] ?>" class="btn btn-default btn-block">Upload Pembayaran</button>
        <?php
        } else {
          echo '<img src="images/upload/'.$fetch_orders["eot"].'" width="400px" height="auto" />';
        }
        ?>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal<?php echo $fetch_orders["id"] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="" method="post" style="display: flex; flex-direction: column;" enctype="multipart/form-data">
                  <div>Produk</div>
                  <ul class="mb-4 pb-2" style="border-bottom: 1px solid gray;border-bottom-style: dotted;">
                    <?php
                    $listArrayWS = explode(',', $listString); 
                    $summed = 0;
                    foreach($listArrayWS as $item): ?>
                    <li class="d-flex align-items-center justify-content-between">
                      <div><?= $item ?></div>
                      <?php
                      $itemRow = null;
                      $itemString = '';
                      $qty = 1;
                      if (preg_match('/[a-zA-Z0-9]+\s?(\(\d+\))/', $item, $matches)) {
                        $itemString = str_replace($matches[1], '', $item);
                        $qty = str_replace('(', '', str_replace(')', '', $matches[1]));
                      }
                      $select_orders_item_makanan = mysqli_query($conn, "SELECT * FROM `products` WHERE name = '$itemString'") or die('query failed');
                      $select_orders_item_minuman = mysqli_query($conn, "SELECT * FROM `drinks` WHERE name = '$itemString'") or die('query failed');
                      $select_orders_item_table = mysqli_query($conn, "SELECT * FROM `tables` WHERE name = '$itemString'") or die('query failed');
                      if(mysqli_num_rows($select_orders_item_makanan) > 0){
                        while($fetch_ordersItem = mysqli_fetch_assoc($select_orders_item_makanan)){
                          $itemRow = $fetch_ordersItem;
                        }
                      } else if(mysqli_num_rows($select_orders_item_minuman) > 0) {
                        while($fetch_ordersItem = mysqli_fetch_assoc($select_orders_item_minuman)){
                          $itemRow = $fetch_ordersItem;
                        }
                      } else if(mysqli_num_rows($select_orders_item_table) > 0) {
                        while($fetch_ordersItem = mysqli_fetch_assoc($select_orders_item_table)){
                          $itemRow = $fetch_ordersItem;
                        }
                      }
                      $summed += (int)($itemRow && $itemRow["price"] ? (int)$itemRow["price"] * (int)$qty: '0');
                      ?>
                      <div>Rp. <?= $itemRow && $itemRow["price"] ? (int)$itemRow["price"] * (int)$qty: '0' ?></div>
                    </li>
                    <?php endforeach; ?>
                  </ul>
                  <ul style="list-style: none;">
                    <li class="d-flex align-items-center justify-content-between">
                      <div>Total</div>
                      <div>Rp. <?= $summed ?></div>
                    </li>
                  </ul>
                  <?php
                  if(!$fetch_orders["eot"]){
                  ?>
                  <h6>Upload Bukti Pembayaran</h6>
                  <div class="d-flex align-items-center mb-4" style="border: 1px solid gray; border-radius: 8px;">
                    <input type="file" name="eot" id="" accept="image/*" required />
                    <input type="hidden" name="id" id="" value="<?= $fetch_orders["id"] ?>" />
                  </div>
                  <button type="submit" name="submitupload" class="btn btn-primary">Upload</button>
                </form>
                <?php
                 } else {
                  echo "<div class='alert alert-success'>Pembayaran sudah diterima.</div>";
                }
                ?>
              </div>
            </div>
          </div>
        </div>
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