<?php
include 'db.php';
$user = $_GET['user'] ?? '';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newpass = trim($_POST['new_password']);
    $confirm = trim($_POST['confirm_password']);

    if ($newpass === "" || $confirm === "") {
        $message = "Please fill in both fields.";
    } elseif ($newpass !== $confirm) {
        $message = "Passwords do not match.";
    } else {
        $hash = password_hash($newpass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password=? WHERE username=?");
        $stmt->bind_param("ss", $hash, $user);
        if ($stmt->execute()) {
            header("Location: login.php?msg=Password+reset+successfully");
            exit;
        } else {
            $message = "Failed to reset password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <style>
  * {
    box-sizing: border-box;
  }
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
    width: 100%;
    max-width: 400px;
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
  input[type="password"] {
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
    <h2>Reset Password</h2>
    <?php if ($message): ?>
      <div class="message"><?= $message ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="input-group">
        <input type="password" name="new_password" id="new_password" placeholder="New Password" required>
        <span class="eye-icon" onclick="toggle('new_password', this)">👁️</span>
      </div>
      <div class="input-group">
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
        <span class="eye-icon" onclick="toggle('confirm_password', this)">👁️</span>
      </div>
      <button type="submit">Reset Password</button>
    </form>
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
