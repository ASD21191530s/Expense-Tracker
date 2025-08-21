<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "expense tracker"; // avoid spaces

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Message vars
$success = "";
$error = "";

// =============== HANDLE FORM SUBMISSION ===============
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $transactiontype = $_POST['transactiontype'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $category = $_POST['category'] ?? '';
    $date = $_POST['date'] ?? '';
    $paymentmethod = $_POST['paymentmethod'] ?? '';
    $notes = $_POST['notes'] ?? '';

    if ($transactiontype && $amount && $category && $date && $paymentmethod) {
        $stmt = $conn->prepare("INSERT INTO `transaction`
            (`transactiontype`, `amount`, `category`, `date`, `paymentmethod`, `notes`) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sdssss", $transactiontype, $amount, $category, $date, $paymentmethod, $notes);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();

            // ✅ Prevent duplicate insert on refresh
            header("Location: index.php?success=1");
            exit(); // 🚨 MUST HAVE or redirect won’t work
        } else {
            $error = "Error: " . $stmt->error;
        }
    } else {
        $error = "Please fill all required fields.";
    }
}

$conn->close();

// Show messages only after redirect
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = "Transaction Added Successfully!";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .transaction-form {
      background: #fff;
      padding: 25px 30px;
      border-radius: 12px;
     box-shadow: 0px 12px 30px rgba(0, 0, 0, 0.12);
      width: 90%;           /* take most of the screen on mobile */
  max-width: 90%;     /* but not wider than 420px */
  margin: 40px auto;    /* auto centers horizontally */
  
  }

     .transaction-form h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    .form-group {
      margin-bottom: 15px;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
      color: #444;
    }

    input, select, textarea {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
      outline: none;
      transition: border 0.3s;
    }

    input:focus, select:focus, textarea:focus {
      border-color: #007bff;
    }

    textarea {
      resize: none;
      height: 80px;
    }

    .btn-submit {
      width: 100%;
      background: #007bff;
      color: #fff;
      border: none;
      padding: 12px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }

    .btn-submit:hover {
      background: #0056b3;
    }
    .radio-group {
  display: flex;
  gap: 20px;
  align-items: center;
 }
 .radio-group .col {
  display: flex;
  align-items: center;
  gap: 5px;
 }


</style>
</head>
<body>



  <form class="transaction-form" method="post" action="">
      <?php if($error): ?><div class="alert alert-danger"><?=$error?></div><?php elseif($success): ?><div class="alert alert-success"><?=$success?></div><?php endif; ?>
  
  <h2>Add Transaction</h2>


<div class="form-group">
    
  <label for="transactiontype">Transaction Type</label>
  <div class="radio-group">
    <div class="col">
      <input type="radio" id="expense" name="transactiontype" value="Expense" required>
      <label for="expense"style="  font-weight: normal;
">Expense</label>
    </div>
    <div class="col">
      <input type="radio" id="income" name="transactiontype" value="Income">
      <label for="income" style="  font-weight: normal;
">Income</label>
    </div>
  </div>
</div>



    
    <div class="form-group">
      <label for="amount">Amount</label>
      <input type="number" id="amount" name="amount" placeholder="Enter amount" required>
    </div>

    <div class="form-group">
      <label for="category">Category</label>
      <select id="category" name="category" required>
        <option value="">-- Select Category --</option>
        <option value="food">Food</option>
        <option value="transport">Transport</option>
        <option value="shopping">Shopping</option>
        <option value="bills">Bills</option>
        <option value="entertainment">Entertainment</option>
        <option value="other">Other</option>
      </select>
    </div>

    <div class="form-group">
      <label for="date">Date</label>
      <input type="date" id="date" name="date" required>
    </div>

    <div class="form-group">
      <label for="category">Payment Method</label>
      <select id="category" name="paymentmethod" >
        <option value="">-- Select Payment Method --</option>
        <option value="UPI">UPI</option>
        <option value="Net Banking">Net Banking</option>
        <option value="Cash">Cash</option>
        <option value="Other">Other</option>
      </select>
    </div>

    <div class="form-group">
      <label for="notes">Notes</label>
      <textarea id="notes" name="notes" placeholder="Add notes..."></textarea>
    </div>

    <button type="submit" class="btn-submit">Save Transaction</button>
  </form>
</body>
</html>