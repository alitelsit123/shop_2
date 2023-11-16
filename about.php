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
   <title>Profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
     <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>



<div id="carouselExample" class="carousel slide position-relative">
  <div class="heading position-absolute" style="z-index: 99999;background: transparent;top:50%;left:50%;transform:translate(-50%,-50%)">
      <h3>Profile</h3>
      <p> <a href="home.php">home</a> / Profile </p>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="https://static.wixstatic.com/media/5e0bdd_8402ac74ac6c43309652b181642a64f7~mv2.jpg/v1/fill/w_1024,h_477,al_c,q_85,enc_auto/5e0bdd_8402ac74ac6c43309652b181642a64f7~mv2.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="https://static.wixstatic.com/media/5e0bdd_8402ac74ac6c43309652b181642a64f7~mv2.jpg/v1/fill/w_1024,h_477,al_c,q_85,enc_auto/5e0bdd_8402ac74ac6c43309652b181642a64f7~mv2.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="https://static.wixstatic.com/media/5e0bdd_8402ac74ac6c43309652b181642a64f7~mv2.jpg/v1/fill/w_1024,h_477,al_c,q_85,enc_auto/5e0bdd_8402ac74ac6c43309652b181642a64f7~mv2.jpg" class="d-block w-100" alt="...">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev" style="background: #8080804a;">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next" style="background: #8080804a;">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<section class="about">

    <div class="flex">
      <div class="content">
          <h3>Opening Hours</h3>
      </div>

      <div class="image">
        <table class="table text-center">
          <thead class="table-dark">
            <tr>
              <th>Hari</th>
              <th>Jam</th>
              <!-- <th>Rabu</th>
              <th>Kamis</th>
              <th>Jumat</th>
              <th>Sabtu</th>
              <th>Minggu</th> -->
            </tr>
          </thead>
          <tbody>
            <tr>
              <th>Senin</th>
              <td>09.00–23.00</td>
            </tr>
            <tr>
              <th>Selasa</th>
              <td>09.00–23.00</td>
            </tr>
            <tr>
              <th>Rabu</th>
              <td>09.00–23.00</td>
            </tr>
            <tr>
              <th>Kamis</th>
              <td>09.00–23.00</td>
            </tr>
            <tr>
              <th>Jumat</th>
              <td>09.00–23.00</td>
            </tr>
            <tr>
              <th>Sabtu</th>
              <td>09.00–23.00</td>
            </tr>
            <tr>
              <th>Minggu</th>
              <td>09.00–23.00</td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>

    <div class="flex">
      <div class="content">
          <h3>Lokasi</h3>
      </div>


      <div id="map" class="image" style="height: 480px;"></div>

      <script>
      var map = L.map('map').setView([-7.636481311621536, 111.52383779325251], 13);
      L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 19,
          attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
      }).addTo(map);
      var marker = L.marker([-7.636481311621536, 111.52383779325251]).addTo(map);
      </script>
    </div>

    <div class="flex">

        <div class="image">
            <img src="images/about-img-01.jpg" alt="">
        </div>

        <div class="content">
            <h3>Menu Makanan</h3>
            <p>Toko Tobitabi memiliki banyak sekali koleksi tanaman hias terbaik dengan harga terjangkau pastinya.</p>
            <a href="shop.php#makanan" class="btn">Lihat Selengkapnya</a>
        </div>

    </div>

    <div class="flex">

        <div class="content">
            <h3>Menu Minuman</h3>
            <p>Toko Tobitabi menyediakan berbagai macam tanaman hias yang menarik dengan keadaan yang segar-segar.</p>
            <a href="shop.php#minuman" class="btn">Lihat Selengkapnya</a>
        </div>

        <div class="image">
            <img src="images/about-img-02.jpg" alt="">
        </div>

    </div>

    <div class="flex">

        <div class="image">
            <img src="images/about-img-03.jpg" alt="">
        </div>

        <div class="content">
            <h3>Tempat</h3>
            <p>Toko Tobitabi merupakan toko yang menyediakan berbagai jenis tanaman hias.</p>
            <a href="reservation.php" class="btn">Lihat Selengkapnya</a>
        </div>

    </div>

</section>

<section class="reviews" id="reviews">

    <h1 class="title">client's reviews</h1>

    <div class="box-container">

        <div class="box">
            <img src="images/pic-1.png" alt="">
            <p>Pelayanannya sangatlah ramah dan menyenangkan.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>john deo</h3>
        </div>

        <div class="box">
            <img src="images/pic-2.png" alt="">
            <p>Tempatnya bersih, rapi, dan nyaman.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>john deo</h3>
        </div>

        <div class="box">
            <img src="images/pic-3.png" alt="">
            <p>minumannya segar-segar.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>john deo</h3>
        </div>

        <div class="box">
            <img src="images/pic-4.png" alt="">
            <p>Rekomendasi sekali harganya oke dan kualitas menunya juga oke.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>john deo</h3>
        </div>

        <div class="box">
            <img src="images/pic-5.png" alt="">
            <p>Tempatnya aesthetic banget bisa dibuat spot foto.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>john deo</h3>
        </div>

        <div class="box">
            <img src="images/pic-6.png" alt="">
            <p>Nyesel sih kenapa ngak tau balen coffee dari dulu aja harganya murah, pelayannya ramah.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>john deo</h3>
        </div>

    </div>

</section>











<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>