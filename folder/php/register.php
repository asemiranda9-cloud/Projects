<?php
include 'db.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);
    $confirm = trim($_POST['confirm_password']);

    if ($user === "" || $pass === "" || $confirm === "") {
        $message = "All fields are required.";
    } elseif ($pass !== $confirm) {
        $message = "Passwords do not match.";
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE username=?");
        $check->bind_param("s", $user);
        $check->execute();
        $exists = $check->get_result()->num_rows > 0;

        if ($exists) {
            $message = "Username already taken.";
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $user, $hash);
            if ($stmt->execute()) {
                header("Location: login.php");
                exit;
            } else {
                $message = "Registration failed.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
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
  h2 {
    text-align: center;
    margin-bottom: 24px;
    color: #00796b;
    font-weight: 600;
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
    <h2>Register</h2>
    <?php if ($message): ?>
      <div class="message"><?= $message ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="input-group">
        <input type="text" name="username" placeholder="Username" required>
      </div>
      <div class="input-group">
        <input type="password" name="password" id="password" placeholder="Password" required>
        <span class="eye-icon" onclick="togglePassword('password', this)">👁️</span>
      </div>
      <div class="input-group">
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
        <span class="eye-icon" onclick="togglePassword('confirm_password', this)">👁️</span>
      </div>
      <button type="submit">Register</button>
    </form>
    <div class="link">
      Already have an account? <a href="login.php">Login</a>
    </div>
  </div>

  <script>
    function togglePassword(id, icon) {
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
