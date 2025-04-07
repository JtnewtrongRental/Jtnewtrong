<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'includes/db.php';

// Initialize messages
$addError = "";
$addSuccess = "";

// Handle "Add New Product" form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $brand = trim($_POST['brand']);
    $unit = trim($_POST['unit']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $image = ""; // Default image path

    // Process image upload if available
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        // Create a unique file name to avoid collisions
        $target_file = $target_dir . uniqid("prod_", true) . "_" . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = $target_file;
        }
    }
    
    $stmt = $conn->prepare("INSERT INTO products (name, brand, unit, category, description, price, stock, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssdis", $name, $brand, $unit, $category, $description, $price, $stock, $image);
    if ($stmt->execute()) {
        $addSuccess = "Product added successfully.";
    } else {
        $addError = "Error adding product: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all products ordered by newest first
$sql = "SELECT * FROM products ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<?php include 'includes/header.php'; ?>
<div class="container">

  <?php
  // Alarm: Display out-of-stock products
  $sql_out_of_stock = "SELECT * FROM products WHERE stock <= 0";
  $result_out_of_stock = $conn->query($sql_out_of_stock);
  if ($result_out_of_stock && $result_out_of_stock->num_rows > 0): ?>
      <div class="alert alert-warning">
        <strong>Alarm: The following product(s) are out of stock!</strong>
        <ul>
          <?php while ($row_out = $result_out_of_stock->fetch_assoc()): ?>
             <li><?= htmlspecialchars($row_out['name']) ?> (ID: <?= $row_out['id'] ?>) - Stock: <?= $row_out['stock'] ?></li>
          <?php endwhile; ?>
        </ul>
      </div>
  <?php endif; ?>

  <h2 class="mb-4">Manage Products</h2>

  <!-- Display Success/Error Messages -->
  <?php if ($addError): ?>
    <div class="alert alert-danger"><?= $addError; ?></div>
  <?php elseif ($addSuccess): ?>
    <div class="alert alert-success"><?= $addSuccess; ?></div>
  <?php endif; ?>

  <!-- Collapsible Add New Product Form -->
  <button class="btn btn-success mb-3" type="button" data-toggle="collapse" data-target="#addProductForm" aria-expanded="false" aria-controls="addProductForm">
    Add New Product
  </button>
  <div class="collapse" id="addProductForm">
    <div class="card card-body">
      <form method="post" action="manage_products.php" enctype="multipart/form-data">
        <input type="hidden" name="add_product" value="1">
        <div class="form-row">
          <div class="form-group col-md-6">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="form-group col-md-6">
            <label>Brand</label>
            <input type="text" name="brand" class="form-control" required>
          </div>
        </div>
        <div class="form-row">
           <div class="form-group col-md-4">
            <label>Unit</label>
            <input type="text" name="unit" class="form-control" required>
          </div>
           <div class="form-group col-md-4">
            <label>Category</label>
            <input type="text" name="category" class="form-control" required>
          </div>
           <div class="form-group col-md-4">
            <label>Price ($)</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
          </div>
        </div>
        <div class="form-group">
          <label>Description</label>
          <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label>Stock Quantity</label>
            <input type="number" name="stock" class="form-control" required>
          </div>
          <div class="form-group col-md-6">
            <label>Product Image</label>
            <input type="file" name="image" class="form-control-file">
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Add Product</button>
      </form>
    </div>
  </div>

  <!-- Products List Table -->
  <div class="table-responsive mt-4">
    <table class="table table-bordered table-striped">
      <thead class="thead-dark">
        <tr>
          <th>No</th> <!-- Changed from ID to No -->
          <th>Image</th>
          <th>Name</th>
          <th>Brand</th>
          <th>Category</th>
          <th>Price ($)</th>
          <th>Stock</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $i++; ?></td> <!-- Sequential numbering -->
              <td>
                <?php
                $img = !empty($row['image']) ? $row['image'] : "https://via.placeholder.com/100x100?text=No+Image";
                ?>
                <img src="<?= htmlspecialchars($img); ?>" alt="<?= htmlspecialchars($row['name']); ?>" style="max-width:100px;">
              </td>
              <td><?= htmlspecialchars($row['name']); ?></td>
              <td><?= htmlspecialchars($row['brand']); ?></td>
              <td><?= htmlspecialchars($row['category']); ?></td>
              <td><?= number_format($row['price'], 2); ?></td>
              <td><?= $row['stock']; ?></td>
              <td><?= $row['created_at']; ?></td>
              <td>
                <a href="edit_product.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="delete_product.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?');">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="9" class="text-center">No products found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
