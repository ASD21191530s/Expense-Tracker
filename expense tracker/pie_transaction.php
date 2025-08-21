<?php
$conn = new mysqli("localhost", "root", "", "expense tracker"); // Removed space from DB name

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$incomeCount = $conn->query("SELECT COUNT(*) AS total FROM transaction WHERE transactiontype='Income'")
                   ->fetch_assoc()['total'];
$expenseCount = $conn->query("SELECT COUNT(*) AS total FROM transaction WHERE transactiontype='Expense'")
                    ->fetch_assoc()['total'];

$totalIncome = $conn->query("SELECT SUM(amount) AS total FROM transaction WHERE transactiontype='Income'")
                    ->fetch_assoc()['total'] ?? 0;
$totalExpense = $conn->query("SELECT SUM(amount) AS total FROM transaction WHERE transactiontype='Expense'")
                     ->fetch_assoc()['total'] ?? 0;
$totalBalance = $totalIncome - $totalExpense;

$catData = $conn->query("SELECT category, SUM(amount) as total 
    FROM transaction WHERE transactiontype='Expense' GROUP BY category");
$catLabels = $catValues = [];
while ($row = $catData->fetch_assoc()) {
    $catLabels[] = $row['category'];
    $catValues[] = $row['total'];
}

$timeData = $conn->query("SELECT date, 
           SUM(CASE WHEN transactiontype='Income' THEN amount ELSE 0 END) as income,
           SUM(CASE WHEN transactiontype='Expense' THEN amount ELSE 0 END) as expense
    FROM transaction GROUP BY date ORDER BY date");
$dates = $incomes = $expenses = [];
while ($row = $timeData->fetch_assoc()) {
    $dates[] = $row['date'];
    $incomes[] = $row['income'];
    $expenses[] = $row['expense'];
}

$payData = $conn->query("SELECT paymentmethod, COUNT(*) as total FROM transaction GROUP BY paymentmethod");
$payLabels = $payValues = [];
while ($row = $payData->fetch_assoc()) {
    $payLabels[] = $row['paymentmethod'];
    $payValues[] = $row['total'];
}

$dailyData = $conn->query("SELECT date, SUM(amount) as total FROM transaction GROUP BY date ORDER BY date");
$dailyDates = $dailyTotals = [];
while ($row = $dailyData->fetch_assoc()) {
    $dailyDates[] = $row['date'];
    $dailyTotals[] = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Expense Tracker Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <style>
 
    .dashboard-card {
      transition: 0.3s ease;
      border-radius: 15px;
      flex: 1;
      min-width: 200px;
      color: white;
    }
    .dashboard-card:hover {
      transform: translateY(-5px) scale(1.03);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }
    .dashboard-row {
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
      margin-bottom: 40px;
    }
    .chart-container {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }
  </style>
</head>
<body class="p-4">


<div class="container">

  <!-- Summary Cards -->
  <div class="dashboard-row">
    <div class="card dashboard-card" style="background: linear-gradient(135deg, #4e54c8, #8f94fb);">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <h5>Total Balance</h5>
          <h2><?= number_format($totalBalance, 2) ?></h2>
        </div>
        <i class="fas fa-wallet fa-3x"></i>
      </div>
    </div>

    <div class="card dashboard-card" style="background: linear-gradient(135deg, #56ab2f, #a8e063);">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <h5>Total Income</h5>
          <h2><?= number_format($totalIncome, 2) ?></h2>
        </div>
        <i class="fas fa-arrow-up fa-3x"></i>
      </div>
    </div>

    <div class="card dashboard-card" style="background: linear-gradient(135deg, #ff512f, #dd2476);">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <h5>Total Expense</h5>
          <h2><?= number_format($totalExpense, 2) ?></h2>
        </div>
        <i class="fas fa-arrow-down fa-3x"></i>
      </div>
    </div>

    <div class="card dashboard-card" style="background: linear-gradient(135deg, #1f4037, #99f2c8);">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <h5>Total Transactions</h5>
          <h2><?= $incomeCount + $expenseCount ?></h2>
        </div>
        <i class="fas fa-exchange-alt fa-3x"></i>
      </div>
    </div>
  </div>

  <!-- Charts Row -->
  <div class="row">
    <div class="col-md-4 chart-container">
      <h5 class="text-center">Expenses by Category</h5>
      <canvas id="categoryChart"></canvas>
    </div>
    &nbsp; &nbsp;
       <div class="col-md-7 chart-container">
      <h5 class="text-center">Income vs Expense Over Time</h5>
      <canvas id="barChart"></canvas>
    </div>
  </div>

  <div class="row">
    <div class="col-md-7 chart-container">
      <h5 class="text-center">Daily Totals</h5>
      <canvas id="lineChart"></canvas>
    </div>
        &nbsp; &nbsp;
    <div class="col-md-4 chart-container">
      <h5 class="text-center">Payment Method Distribution</h5>
      <canvas id="methodChart"></canvas>
    </div>
  </div>

  <div class="row">
    
  </div>
</div>

<!-- Chart JS Scripts -->
<script>
  // Pie Chart - Expense by Category
  new Chart(document.getElementById("categoryChart"), {
    type: "pie",
    data: {
      labels: <?= json_encode($catLabels) ?>,
      datasets: [{
        data: <?= json_encode($catValues) ?>,
        backgroundColor: ["#FF6384","#36A2EB","#FFCE56","#4BC0C0","#9966FF"]
      }]
    }
  });

  // Doughnut Chart - Payment Method
  new Chart(document.getElementById("methodChart"), {
    type: "doughnut",
    data: {
      labels: <?= json_encode($payLabels) ?>,
      datasets: [{
        data: <?= json_encode($payValues) ?>,
        backgroundColor: ["#FF9F40","#4BC0C0","#36A2EB","#9966FF","#FF6384"]
      }]
    }
  });

  // Bar Chart - Income vs Expense
  new Chart(document.getElementById("barChart"), {
    type: "bar",
    data: {
      labels: <?= json_encode($dates) ?>,
      datasets: [
        {
          label: "Income",
          data: <?= json_encode($incomes) ?>,
          backgroundColor: "#36A2EB"
        },
        {
          label: "Expense",
          data: <?= json_encode($expenses) ?>,
          backgroundColor: "#FF6384"
        }
      ]
    }
  });

  // Line Chart - Daily Totals
  new Chart(document.getElementById("lineChart"), {
    type: "line",
    data: {
      labels: <?= json_encode($dailyDates) ?>,
      datasets: [{
        label: "Daily Total",
        data: <?= json_encode($dailyTotals) ?>,
        borderColor: "red",
        fill: false,
        tension: 0.3
      }]
    }
  });
</script>
</body>
</html>
