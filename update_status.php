<?php
include 'config.php';

// Assuming you receive the data as JSON
$data = json_decode(file_get_contents('php://input'), true);

$tableId = $data['tableId'];
$newStatus = $data['newStatus'];

// Update the status in the database
$updateQuery = "UPDATE tables SET status = '$newStatus' WHERE id_table = '$tableId'";
mysqli_query($conn, $updateQuery);

// Return a response (you may customize this based on your needs)
echo json_encode(['success' => true]);
?>
