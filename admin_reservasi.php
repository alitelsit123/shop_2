<?php
@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
};

// Fetch data from the 'tables' table
$select_tables = mysqli_query($conn, "SELECT * FROM `tables`") or die('Query failed');
$tables = mysqli_fetch_all($select_tables, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Reservasi</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<style>
   .show-tables {
      margin: 20px;
      text-align: center; /* Center the table */
   }

   .table-container {
      width: 80%; /* Adjust the width as needed */
      margin: auto; /* Center the container */
      overflow-x: auto; /* Add horizontal scroll if the table is wider than the container */
   }

   table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
   }

   th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
   }

   th {
      background-color: lightgrey; /* Make the top column light grey */
      font-weight: bold; /* Make the top column bold */
   }

   tbody tr:hover {
      background-color: #f5f5f5;
   }

   .table-image {
      max-width: 100px; /* Adjust the max-width as needed */
      max-height: 100px; /* Adjust the max-height as needed */
   }

   .status-button {
      padding: 8px;
      cursor: pointer;
      border: none;
      outline: none;
      background-color: #4CAF50;
      color: white;
      font-weight: bold;
   }

   .status-disabled {
      background-color: #f44336;
   }

   /* Optional: Add some styling for responsiveness */
   @media (max-width: 600px) {
      table {
         font-size: 14px;
      }

      th, td {
         padding: 8px;
      }
   }
</style>

<body>
   <?php @include 'admin_header.php'; ?>

   <section class="show-tables">
      <h1 class="title">Tabel Reservasi</h1>

      <div class="table-container">
         <table>
            <thead>
               <tr>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Image</th>
                  <th>Status</th>
               </tr>
            </thead>
            <tbody>
            <?php
if (!empty($tables)) {
    foreach ($tables as $table) {
        echo "<tr>";
        echo "<td>{$table['name']}</td>";
        echo "<td>{$table['description']}</td>";
        echo "<td><img src='uploaded_img/{$table['image']}' alt='Table Image' class='table-image'></td>";

        // Add the status button code here
        echo "<td>";
        $statusClass = ($table['status'] == 'Reserved') ? '' : ' status-not-Reserved';
        echo "<button class='status-button{$statusClass}' data-id='{$table['id_table']}' onclick='toggleStatus({$table['id_table']})'>";
        echo ucfirst($table['status']);
        echo "</button>";
        echo "</td>";

        echo "</tr>";
    }
} else {
    echo '<tr><td colspan="6">No tables added yet!</td></tr>';
}
?>


</tbody>

         </table>
      </div>
   </section>

   <script>
   function toggleStatus(tableId) {
      // Simulate toggle locally
      var button = document.querySelector('.status-button[data-id="' + tableId + '"]');
      var currentStatus = button.textContent.toLowerCase();
      var newStatus = (currentStatus === 'enabled') ? 'disabled' : 'enabled';
      
      // Simulate toggle locally
      button.classList.toggle('status-disabled', newStatus === 'disabled');
      button.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);

      // Send AJAX request to update the status in the database
      updateStatus(tableId, newStatus);
   }

   function updateStatus(tableId, newStatus) {
      // Here you would send an AJAX request to update the status in the database
      // Example using fetch API:
      fetch('update_status.php', {
         method: 'POST',
         headers: {
            'Content-Type': 'application/json',
         },
         body: JSON.stringify({
            tableId: tableId,
            newStatus: newStatus,
         }),
      })
      .then(response => response.json())
      .then(data => {
         // Handle the response from the server if needed
      })
      .catch(error => console.error('Error:', error));
   }
</script>


</body>

</html>