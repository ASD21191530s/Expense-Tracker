<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>



<div class="settings-container d-flex align-items-center p-4 bg-white shadow rounded">
  <!-- Profile Image -->
  <img src="asd1.jpeg" 
       alt="Profile Image" 
       class="rounded-circle me-3" 
       width="200" height="200">

  <!-- User Info + Actions -->
  <div class="flex-grow-1">
    <h4 class="mb-2">                  
    </h4><?php 

                    echo $_SESSION['name'];
                  ?>
      <p>Web Developer </p>
    <div class="d-flex gap-2">
      <form action="generate_report.php" method="post">
        <button type="submit" onclick="generate_report.php"class="btn btn-primary">
          <i class="fas fa-file-alt"></i> Generate Report
        </button>
      </form>
      <form action="login.php" method="post">
        <button type="submit" class="btn btn-danger">
          <i class="fas fa-sign-out-alt"></i> Logout
        </button>
      </form>
    </div>
  </div>
</div>
</body>
</html>