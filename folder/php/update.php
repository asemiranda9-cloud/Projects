<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);
$message = "";
$name = $description = $image = "";
$price = 0.0;

$stmt = $conn->prepare("SELECT * FROM items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();

if ($item) {
    $name = $item['name'];
    $price = floatval($item['price']);
    $description = $item['description'];
    $image = $item['image'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $newImage = $image;

        if (!empty($_FILES['image']['name'])) {
            $newImage = $_FILES['image']['name'];
            $target = "uploads/" . basename($newImage);
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $message = "Failed to upload new image.";
            }
        }

        if ($name && $price > 0 && $description) {
            $sql = "UPDATE items SET name=?, price=?, description=?, image=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdssi", $name, $price, $description, $newImage, $id);
            $message = $stmt->execute() ? "Item updated successfully!" : "Error: " . $conn->error;
        } else {
            $message = "Please fill out all fields correctly.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Grocery Item</title>
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
    .header-link:hover {
      background: rgba(255,255,255,0.35);
    }
    .container {
      max-width: 600px;
      margin: 40px auto;
      background: #fff;
      padding: 32px;
      border-radius: 16px;
      box-shadow: 0 12px 32px rgba(0,0,0,0.1);
    }
    h2 {
      margin-bottom: 24px;
      color: #00796b;
      font-weight: 600;
      font-size: 24px;
    }
    input, textarea {
      width: 100%;
      padding: 14px;
      margin-bottom: 18px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
    }
    input:focus, textarea:focus {
      border-color: #009688;
      outline: none;
    }
    .preview-img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 8px;
      border: 1px solid #ccc;
      margin-bottom: 18px;
    }
    button {
      width: 100%;
      padding: 14px;
      background: #009688;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
    }
    button:hover {
      background: #00796b;
    }
    .message {
      margin-bottom: 18px;
      color: #388e3c;
      font-weight: 600;
      text-align: center;
    }
  </style>
</head>
<body>
  <header class="top-header">
    <h1>🛒 Grocery Store</h1>
    <a href="dashboard.php" class="header-link">← Back to Dashboard</a>
  </header>

  <div class="container">
    <h2>Edit Grocery Item</h2>
    <?php if ($message) echo "<p class='message'>$message</p>"; ?>
    <?php if ($item): ?>
    <form method="POST" enctype="multipart/form-data">
      <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" placeholder="Item Name" required>
      <input type="number" step="0.01" name="price" value="<?= htmlspecialchars(number_format($price, 2, '.', '')) ?>" placeholder="Price" required>
      <textarea name="description" rows="4" placeholder="Description" required><?= htmlspecialchars($description) ?></textarea>
      <p>Current Image:</p>
      <img src="uploads/<?= htmlspecialchars($image) ?>" class="preview-img" alt="Current Image">
      <input type="file" name="image" accept="image/*">
      <button type="submit">Update Item</button>
    </form>
    <?php else: ?>
      <p class="message">Item not found or invalid ID.</p>
    <?php endif; ?>
  </div>
</body>
</html>
