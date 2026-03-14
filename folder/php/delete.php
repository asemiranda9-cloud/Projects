<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
$message = "";
$item = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm'])) {
        $id = intval($_POST['id']);
        $sql = "DELETE FROM items WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: dashboard.php?deleted=1");
            exit;
        } else {
            $message = "Error deleting item: " . $conn->error;
        }
    } else {
        header("Location: dashboard.php");
        exit;
    }
}

// Fetch item details for confirmation
if ($id) {
    $sql = "SELECT * FROM items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Delete Item</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #e0f7fa, #80deea);
      margin: 0;
      padding: 0;
    }
    .top-header {
      background: linear-gradient(to right, #009688, #00796b);
      padding: 16px 32px;
      color: #fff;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .top-header h1 {
      font-size: 22px;
      font-weight: 600;
      margin: 0;
    }
    .header-link {
      color: #fff;
      text-decoration: none;
      font-weight: 500;
      background: rgba(255,255,255,0.2);
      padding: 8px 12px;
      border-radius: 6px;
      transition: background 0.3s;
    }
    .header-link:hover { background: rgba(255,255,255,0.35); }

    .container {
      max-width: 500px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 12px 32px rgba(0,0,0,0.1);
      text-align: center;
    }
    h2 {
      color: #c0392b;
      margin-bottom: 20px;
      font-weight: 600;
    }
    p { margin-bottom: 20px; }
    .btn {
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 15px;
      font-weight: 600;
      margin: 5px;
      transition: background 0.3s;
    }
    .confirm {
      background: #c0392b;
      color: #fff;
    }
    .confirm:hover { background: #962d22; }
    .cancel {
      background: #009688;
      color: #fff;
    }
    .cancel:hover { background: #00796b; }
    img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 15px;
    }
    .message {
      color: red;
      font-weight: 600;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <header class="top-header">
    <h1>🛒 Grocery Store</h1>
    <a href="dashboard.php" class="header-link">← Back to Dashboard</a>
  </header>

  <div class="container">
    <h2>Confirm Delete</h2>
    <?php if ($message) echo "<p class='message'>$message</p>"; ?>

    <?php if ($item) { ?>
      <p>Are you sure you want to delete this item?</p>
      <img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt="Item Image">
      <p><strong><?= htmlspecialchars($item['name']) ?></strong><br>
         ₱<?= number_format($item['price'], 2) ?><br>
         <?= htmlspecialchars($item['description']) ?></p>

      <form method="POST">
        <input type="hidden" name="id" value="<?= $item['id'] ?>">
        <button type="submit" name="confirm" class="btn confirm">Yes, Delete</button>
        <button type="submit" name="cancel" class="btn cancel">Cancel</button>
      </form>
    <?php } else { ?>
      <p>Item not found.</p>
      <a href="dashboard.php" class="btn cancel">Back to Dashboard</a>
    <?php } ?>
  </div>
</body>
</html>
