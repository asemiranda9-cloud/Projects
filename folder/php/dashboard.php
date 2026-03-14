<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$search   = $_GET['search']   ?? '';
$sort     = $_GET['sort']     ?? '';
$category = $_GET['category'] ?? '';

$orderBy = "ORDER BY id DESC"; // default newest
if ($sort === 'price_asc')  $orderBy = "ORDER BY price ASC";
if ($sort === 'price_desc') $orderBy = "ORDER BY price DESC";
if ($sort === 'newest')     $orderBy = "ORDER BY id DESC";

$like = "%$search%";

if ($category) {
    $sql = "SELECT * FROM items WHERE name LIKE ? AND category = ? $orderBy";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $like, $category);
} else {
    $sql = "SELECT * FROM items WHERE name LIKE ? $orderBy";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $like);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Grocery Store Dashboard</title>
  <style>
  * { box-sizing: border-box; }
  body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #e0f7fa, #80deea);
    padding: 0;
  }
  .topbar {
    background: linear-gradient(to right, #009688, #00796b);
    padding: 20px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }
  .topbar h1 {
    margin: 0;
    font-size: 24px;
    font-weight: bold;
  }
  .profile-wrapper { position: relative; }
  .profile-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #fff;
    color: #00796b;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 18px;
    cursor: pointer;
  }
  .dropdown-menu {
    display: none;
    position: absolute;
    top: 50px;
    right: 0;
    background-color: white;
    border: 1px solid #ddd;
    border-radius: 6px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    min-width: 120px;
    z-index: 1000;
  }
  .dropdown-menu a {
    display: block;
    padding: 10px 16px;
    color: #009688;
    text-decoration: none;
    font-weight: bold;
  }
  .dropdown-menu a:hover { background-color: #f0f0f0; }

  .container {
    max-width: 1000px;
    margin: 40px auto;
    background: #fff;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 12px 32px rgba(0,0,0,0.1);
  }
  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
  }
  h2 {
    color: #00796b;
    margin: 0;
    font-size: 22px;
    font-weight: 600;
  }
  .add-btn {
    padding: 10px 16px;
    background: #009688;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.3s ease;
  }
  .add-btn:hover { background: #00796b; }
  .search-bar {
    margin-bottom: 20px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
  }
  .search-bar input, .search-bar select {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
  }
  .search-bar button {
    padding: 10px 16px;
    background: #009688;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
  }
  .search-bar button:hover { background: #00796b; }

  table {
    width: 100%;
    border-collapse: collapse;
  }
  th, td {
    padding: 12px 16px;
    border-bottom: 1px solid #ddd;
    text-align: left;
    vertical-align: top;
  }
  th {
    background-color: #f0f0f0;
    color: #333;
    font-weight: 600;
  }
  tr:hover { background-color: #f9f9f9; }
  .item-img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
  }
  .actions a {
    margin-right: 10px;
    text-decoration: none;
    font-size: 18px;
  }
  .edit { color: #2980b9; }
  .delete { color: #c0392b; }
</style>

</head>
<body>
  <!-- Top Header Bar -->
  <div class="topbar">
    <h1>🛒 Grocery Store</h1>
    <div class="profile-wrapper">
      <div class="profile-icon" onclick="toggleDropdown()">
        <?= strtoupper(substr($_SESSION['username'], 0, 1)) ?>
      </div>
      <div id="dropdown" class="dropdown-menu">
        <a href="#" onclick="confirmLogout()">Logout</a>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="container">
    <div class="header">
      <h2>Inventory</h2>
      <a href="create.php" class="add-btn">+ Add Item</a>
    </div>

    <form method="GET" class="search-bar">
      <input type="text" name="search" placeholder="Search items..." value="<?= htmlspecialchars($search) ?>">
      <select name="category">
        <option value="">All Categories</option>
        <option value="fruits" <?= $category === 'fruits' ? 'selected' : '' ?>>Fruits</option>
        <option value="snacks" <?= $category === 'snacks' ? 'selected' : '' ?>>Snacks</option>
        <option value="drinks" <?= $category === 'drinks' ? 'selected' : '' ?>>Drinks</option>
      </select>
      <select name="sort">
        <option value="">Sort by</option>
        <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option>
        <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
        <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
      </select>
      <button type="submit" class="add-btn">Apply</button>
    </form>

    <table>
      <tr>
        <th>Image</th>
        <th>Name</th>
        <th>Price</th>
        <th>Description</th>
        <th>Category</th>
        <th>Actions</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
          <td><img src="uploads/<?= htmlspecialchars($row['image']) ?>" class="item-img"></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td>₱<?= number_format($row['price'], 2) ?></td>
          <td><?= htmlspecialchars($row['description']) ?></td>
          <td><?= htmlspecialchars($row['category']) ?></td>
          <td class="actions">
            <a href="update.php?id=<?= $row['id'] ?>" class="edit">✏️</a>
            <a href="delete.php?id=<?= $row['id'] ?>" class="delete">🗑️</a>
          </td>
        </tr>
      <?php } ?>
    </table>
  </div>

  <script>
    function toggleDropdown() {
      const dropdown = document.getElementById('dropdown');
      dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }

    window.addEventListener('click', function(event) {
      if (!event.target.closest('.profile-wrapper')) {
        document.getElementById('dropdown').style.display = 'none';
      }
    });

    function confirmLogout() {
      if (confirm("Are you sure you want to logout?")) {
        window.location.href = "logout.php";
      }
    }
  </script>
</body>
</html>
