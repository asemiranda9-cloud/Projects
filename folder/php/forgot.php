<?php
include 'db.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $message = "Username not found.";
    } else {
        // In real apps, you'd send email or security questions
        header("Location: reset.php?user=" . urlencode($user));
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
  <style>
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
    border-radius: 16px;
    box-shadow: 0 12px 32px rgba(0,0,0,0.1);
    max-width: 400px;
    width: 100%;
  }
  h2 {
    text-align: center;
    margin-bottom: 24px;
    color: #00796b;
    font-weight: 600;
  }
  input[type="text"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 16px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
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
    <h2>Forgot Password</h2>
    <?php if ($message): ?>
      <div class="message"><?= $message ?></div>
    <?php endif; ?>
    <form method="POST">
      <input type="text" name="username" placeholder="Enter your username" required>
      <button type="submit">Next</button>
    </form>
  </div>
</body>
</html>
