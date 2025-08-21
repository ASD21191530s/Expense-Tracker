<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Expense Tracker</title>
  <!-- Font Awesome 5 Free -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
body {
  margin: 0;
  font-family: Arial, sans-serif;
  display: flex;
 background: linear-gradient(135deg, #dceeff, #e9f3fc, #f4f6f8);
  background-size: 400% 400%;
  animation: gradientMove 12s ease infinite;
}

/* Background Animation */
/* Sidebar */
.sidebar {
  width: 220px;
  background: #f8fafb;
  color: black;
  min-height: 100vh;
  padding-top: 20px;
  position: fixed;
  left: 0;
  top: 0;
  box-shadow: 0px 12px 30px rgba(0, 0, 0, 0.12);
}

.sidebar h2 {
  text-align: center;
  margin-bottom: 30px;
  font-size: 20px;
}

.sidebar a {
  display: block;
  padding: 12px 20px;
  color: black;
  text-decoration: none;
  transition: background 0.3s;
}

.sidebar a:hover, 
.sidebar a.active {
  background: black;
  color: white;
}

/* Content */
.content {
  margin-left: 220px; /* sidebar width */
  padding: 20px;
  width: calc(100% - 220px); /* take full remaining width */
  min-height: 100vh;
  box-sizing: border-box;
}

/* Sections */
section {
  display: none;
}

section.active {
  display: block;
}

/* Dashboard Cards */
.cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 20px;
}

.card {
  background: white;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0px 12px 30px rgba(0, 0, 0, 0.12);
  text-align: center;
}

/* Chart Boxes */
.chart-placeholder {
  background: white;
  padding: 40px;
  border-radius: 12px;
  text-align: center;
  box-shadow: 0px 12px 30px rgba(0, 0, 0, 0.12);
  margin-bottom: 20px;
  width: 100%;
  box-sizing: border-box;
}

/* Transaction Form */
form {
  max-width: 900px;  /* wider form */
  width: 100%;
  margin: auto;
  background: white;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0px 12px 30px rgba(0,0,0,0.12);
}

form input, 
form select, 
form textarea {
  width: 100%;
  padding: 12px;
  margin: 10px 0;
  border-radius: 8px;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

form button {
  padding: 12px 20px;
  border: none;
  background: black;
  color: white;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.3s;
}

form button:hover {
  background: #333;
}
</style>  
</head>
<body>
<?php
session_start();
// Prevent caching
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['name'])) {
    header("Location: unauthorized.php");
    exit();
}
?>

  <div class="sidebar">
    <h2>💰 Expense Tracker</h2>

  <a href="#" class="active" onclick="showSection('dashboard')">
    <i class="fas fa-home"></i>
&nbsp; Dashboard
  </a>    
  <a href="#" onclick="showSection('add-transaction')"><i class="fa-solid fa-right-left" ></i> &nbsp; Add Transaction</a>
    <a href="#" onclick="showSection('history')"><i class="fa-solid fa-clock-rotate-left"></i> &nbsp; History</a>
    <a href="#" onclick="showSection('settings')"><i class="fa-solid fa-gear"></i> &nbsp; Settings</a>
  </div>

  <div class="content">
    <!-- Dashboard -->
    <section id="dashboard" class="active">
      <h1>Dashboard</h1>
      <?php include('pie_transaction.php')?>
    
    </section>

    <!-- Add Transaction -->
    <section id="add-transaction">
      <h1>Transaction Form</h1>

   <?php include('form.php'); ?>
    </section>

    <!-- History -->
    <section id="history">
      <h1>History</h1>
      <?php include('data_transaction.php');?>
    </section>

    <!-- Bank / UPI -->
    
    <!-- Settings -->
    <section id="settings">
      <h1>Settings</h1>
      <?php include('setting.php')?>
    </section>
  </div>

  <script>
    function showSection(id) {
      document.querySelectorAll("section").forEach(sec => sec.classList.remove("active"));
      document.getElementById(id).classList.add("active");

      document.querySelectorAll(".sidebar a").forEach(link => link.classList.remove("active"));
      event.target.classList.add("active");
    }
  </script>

</body>
</html>
