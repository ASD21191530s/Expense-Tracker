<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #c6d7e6ff; /* same blue-gray as in image */
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .login-container {
      display: flex;
      width: 400px;
      height: 250px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.12);
      overflow: hidden;
      width:35vw;
      height:50vh;
    }

    .login-left {
      flex: 2;
      padding: 30px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .login-left h2 {
      margin-bottom: 20px;
      font-size: 22px;
      color: #333;
    }

    .input-group {
      margin-bottom: 15px;
    }

    .input-group input {
      width: 100%;
      padding: 10px;
      border: none;
      border-bottom: 2px solid #ccc;
      outline: none;
      font-size: 14px;
      transition: 0.3s;
    }

    .input-group input:focus {
      border-bottom: 2px solid #6b879f;
    }

    .options {
      font-size: 12px;
      color: #555;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .login-right {
      flex: 1;
      background-color: #2f3b46;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 18px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }

    .login-right:hover {
      background-color: #1e2a33;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <!-- Left side -->
    <div class="login-left">
      <h2>Welcome Back</h2>
      <form action="validate.php" method="post">
        <div class="input-group">
          <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-group">
          <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="options">
          <input type="checkbox" name="remember"> Remember me
        </div>
      </form>
    </div>

    <!-- Right side -->
    <div class="login-right" onclick="document.querySelector('form').submit();">
      LOGIN
    </div>
  </div>

</body>
</html>
