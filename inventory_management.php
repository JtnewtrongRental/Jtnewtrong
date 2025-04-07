<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include 'includes/db.php';
include 'includes/header.php';

$updateMsg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_stock'])) {
    $product_id = intval($_POST['product_id']);
    $new_stock = intval($_POST['new_stock']);
    $stmt = $conn->prepare("UPDATE products SET stock = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_stock, $product_id);
    if($stmt->execute()){
        $updateMsg = '<div class="alert alert-info">Stock updated successfully.</div>';
    } else {
        $updateMsg = '<div class="alert alert-danger">Error updating stock: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}
$lowStockThreshold = 5;
$sql = "SELECT * FROM products ORDER BY name ASC";
$result = $conn->query($sql);
?>
<div class="container mt-4">
  <h2>Inventory Management</h2>
  <?= $updateMsg; ?>
  <table class="table table-bordered table-hover">
    <thead class="thead-dark">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Stock</th>
        <th>Price ($)</th>
        <th>Low Stock?</th>
        <th>Update Stock</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php while ($product = $result->fetch_assoc()): ?>
          <tr class="<?= ($product['stock'] < $lowStockThreshold) ? 'table-danger' : ''; ?>">
            <td><?= $product['id']; ?></td>
            <td><?= htmlspecialchars($product['name']); ?></td>
            <td><?= $product['stock']; ?></td>
            <td><?= number_format($product['price'], 2); ?></td>
            <td><?= ($product['stock'] < $lowStockThreshold) ? 'Yes' : 'No'; ?></td>
            <td>
              <form method="post" action="inventory_management.php" class="form-inline">
                <input type="hidden" name="update_stock" value="1">
                <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                <input type="number" name="new_stock" min="0" class="form-control form-control-sm mr-2" style="width:80px;" required>
                <button type="submit" class="btn btn-sm btn-primary">Update</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="6">No products found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<?php include 'includes/footer.php'; ?>
