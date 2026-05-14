<?php
$servername = "sql210.infinityfree.com";
$username = "if0_41917198";
$password = "TkPHm5YQI6";
$db = "if0_41917198_expense_tracker"; // avoid spaces in DB name

$conn = mysqli_connect($servername, $username, $password, $db);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
$id = $_POST['id'];

$sql = "DELETE FROM transaction WHERE id=$id";
echo mysqli_query($conn, $sql) ? "Record deleted." : "Failed to delete.";
?>
