<?php
session_start();
include 'db.php';

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($pass, $row['password'])) {
            $_SESSION['username'] = $user;
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "Invalid password.";
        }
    } else {
        $message = "User not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Grocery Store Login</title>
  <style>
  * { box-sizing: border-box; }
  body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #e0f7fa, #80deea);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }
  .card {
    background: #fff;
    padding: 40px;
    max-width: 400px;
    width: 100%;
    border-radius: 16px;
    box-shadow: 0 12px 32px rgba(0,0,0,0.1);
  }
  .brand-title {
    text-align: center;
    font-size: 32px;
    font-weight: 700;
    color: #00796b;
    margin-bottom: 8px;
  }
  .subtitle {
    text-align: center;
    font-size: 20px;
    color: #009688;
    margin-bottom: 24px;
  }
  .input-group {
    position: relative;
    margin-bottom: 20px;
  }
  input[type="text"], input[type="password"] {
    width: 100%;
    padding: 12px 40px 12px 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
  }
  .eye-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 18px;
    color: #666;
    user-select: none;
  }
  button {
    width: 100%;
    padding: 12px;
    background: #009688;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease;
  }
  button:hover {
    background: #00796b;
  }
  .link {
    margin-top: 16px;
    text-align: center;
    font-size: 14px;
  }
  .link a {
    color: #009688;
    text-decoration: none;
    font-weight: 600;
  }
  .message {
    color: red;
    text-align: center;
    margin-bottom: 16px;
    font-weight: 600;
  }
</style>
</head>
<body>
  <div class="card">
    <div class="brand-title">🛒 Grocery Store</div>
    <div class="subtitle">Login</div>
    <?php if ($message): ?>
      <div class="message"><?= $message ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="input-group">
        <input type="text" name="username" placeholder="Username" required>
      </div>
      <div class="input-group">
        <input type="password" name="password" id="password" placeholder="Password" required>
        <span class="eye-icon" onclick="toggle('password', this)">👁️</span>
      </div>
      <button type="submit">Login</button>
    </form>
    <div class="link">
      Don't have an account? <a href="register.php">Register</a><br>
      <a href="forgot.php">Forgot your password?</a>
    </div>
  </div>

  <script>
    function toggle(id, icon) {
      const input = document.getElementById(id);
      if (input.type === "password") {
        input.type = "text";
        icon.textContent = "🙈";
      } else {
        input.type = "password";
        icon.textContent = "👁️";
      }
    }
  </script>
</body>
</html>
