<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>
<link rel="stylesheet" href="css/style.css">

<header class="header">

   <div class="flex">

      <a href="admin_page.php" class="logo">Admin_<span>Balen Coffee </span></a>

      <nav class="navbar">
         
         <ul>
        <li><a href="admin_page.php">Home</a></li>
        <li><a href="#">Products +</a>
            <ul>
                <li><a href="admin_products.php">Makanan</a></li>
                <li><a href="admin_products.php?v=minuman">Minuman</a></li>
                <li><a href="admin_products.php?v=table">Table</a></li>
            </ul>
        </li>
        <li><a href="admin_orders.php">Orders</a></li>
        <li><a href="admin_users.php">User</a></li>
        <li><a href="admin_contacts.php">Kritik Saran</a></li>

    </ul>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="account-box">
         <p>username : <span><?php echo $_SESSION['admin_name']; ?></span></p>
         <p>email : <span><?php echo $_SESSION['admin_email']; ?></span></p>
         <a href="logout.php" class="delete-btn">logout</a>
         <div>baru <a href="login.php">login</a> | <a href="register.php">register</a> </div>
      </div>

   </div>

</header>