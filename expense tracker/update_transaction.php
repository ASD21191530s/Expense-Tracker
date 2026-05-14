<?php
$servername = "sql210.infinityfree.com";
$username   = "if0_41917198";
$password   = "TkPHm5YQI6";
$db         = "if0_41917198_expense_tracker"; // ✅ renamed without space

$conn = mysqli_connect($servername, $username, $password, $db);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

$id              = $_POST['id'];
$transactiontype = $_POST['transactiontype'];
$amount          = $_POST['amount'];
$category        = $_POST['category'];
$date            = $_POST['date'];
$paymentmethod   = $_POST['paymentmethod'];
$notes           = $_POST['notes'];

$sql = "UPDATE transaction SET 
            transactiontype='$transactiontype', 
            amount='$amount', 
            category='$category', 
            date='$date', 
            paymentmethod='$paymentmethod',
            notes='$notes'
        WHERE id='$id'";

if (mysqli_query($conn, $sql)) {
    echo "✅ Record updated successfully.";
} else {
    echo "❌ Failed to update: " . mysqli_error($conn);
}
?>
