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
      mysqli_query($conn, "INSERT INTO `booked`(user_id, name,  address,total_products,  placed_on,booking_date,booking_time,email) VALUES('$user_id', '$name', '$address','$total_products',  '$placed_on','$book_date','$book_time','$email')") or die(mysqli_error($conn));
      mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on,booking_date,booking_time) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on','$book_date','$book_time')") or die(mysqli_error($conn));
      mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      $message[] = 'order placed successfully!';
      $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id' order by id desc") or die('query failed');
      if(mysqli_num_rows($select_orders) > 0){
        while($fetch_orders = mysqli_fetch_assoc($select_orders)){
          header("Location: checkout.php?id=".$fetch_orders["id"]);
          break;
        }
      }
  }
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
        $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id' order by id desc") or die('query failed');
        if(mysqli_num_rows($select_orders) > 0){
          while($fetch_orders = mysqli_fetch_assoc($select_orders)){
            header("Location: orders.php");
            break;
          }
        }
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
   <title>checkout</title>

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<?php
if(!isset($_GET["id"])):
?>
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
            <div class="inputBox" style="display:none;">
                <span>Email :</span>
                <input type="text" name="email" placeholder="masukkan email Anda" value="-">
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
            <div class="inputBox" style="display:none;">
                <span>Provinsi :</span>
                <input type="text" name="state" placeholder="masukkan provinsi" value="-">
            </div>
            <div class="inputBox" style="display:none;">
                <span>Kode Pos :</span>
                <input type="number" name="pin_code" placeholder="masukkan kode pos" value="0">
            </div>
            

            <div class="inputBox" style="display:flex;align-items:center;">
            <div class="inputBox" style="margin-right: 1rem;">
                <span>Hari :</span>
                  <input id="datepicker1" type="date" name="reservation_date" class="form-control" placeholder="Date" required="">
            </div>
                <div class="inputBox">
                <span>Waktu :</span>
                  <select type="time" name="reservation_time" class="form-control" placeholder="Heure" id="time" required="">
                    <option value=""> -Select- </option>
                    <option value="09:00">09:00</option>
                    <option value="09:30">09:30</option>
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
                    <option value="16:00">15:00</option>
                    <option value="16:30">16:30</option>
                    <option value="17:00">17:00</option>
                    <option value="17:30">17:30</option>
                    <option value="18:00">18:00</option>
                    <option value="18:30">18:30</option>
                    <option value="19:00">19:00</option>
                    <option value="19:30">19:30</option>
                    <option value="20:00">20:00</option>
                  </select>
                  <div class="validation"></div>
                </div>
            </div>



        </div>

        <input type="submit" name="order" value="Checkout Sekarang" class="btn">

    </form>

</section>

<?php
else:
?>
<section class="heading">
    <h3>checkout pesanan</h3>
    <p> <a href="home.php">home</a> / checkout / order #<?php echo $_GET["id"] ?> </p>
</section>

<section class="placed-orders">
    <div class="box-container" style="width: 400px;">
    <h3>Checkout Details</h3>
    <?php
        $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id' AND id='".$_GET['id']."'") or die('query failed');
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
                <h5 class="modal-title" id="exampleModalLabel">Upload Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
              <div class="mb-3">
              <div class="">
                <div class="alert" role="alert">
                  <h5 class="alert-heading">Cara Bayar</h5>
                  <p style="font-size:1.2rem;">Kirim bukti transfer ke Whatsapp (081336644889) jika menggunakan transfer</p>
                  <p style="font-size:1.2rem;">No.Rek BCA An. Iqbal Muhammad Firdaus</p>
                  <p>
                <br /><img src="./images/qris.jpeg" alt="" srcset="" width="200px" height="200px" class="mx-auto">
                  </p>
                </div>
              </div>
            </div>
            <hr />
            <h5 class="alert-heading mb-3">Summary</h5>

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
                      if (preg_match('/[a-zA-Z0-9]+\s?(\(\d+\))/', $item, $matches)) {
                        $itemString = str_replace($matches[1], '', $item);
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
                      $summed += (int)($itemRow && $itemRow["price"] ? (int)$itemRow["price"]: '0');
                      ?>
                      <div>Rp. <?= $itemRow && $itemRow["price"] ? (int)$itemRow["price"]: '0' ?></div>
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

<?php
endif;
?>




<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>