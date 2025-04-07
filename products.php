<?php
session_start();
// Removed the redirect check so that guests and staff can see the products
include 'includes/db.php';

// Initialize messages
$addError = "";
$addSuccess = "";

// Handle product addition form submission (only available for admin/boss)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    // For security, only allow admin or boss to add products
    if (!(isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['admin', 'boss']))) {
        header("Location: products.php");
        exit();
    }
    $name = trim($_POST['name']);
    $brand = trim($_POST['brand']);
    $unit = trim($_POST['unit']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $image = ""; // Default empty image path

    // Process image upload if available
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
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
  <h2 class="mb-4"><center></center>Our Products</h2></h2>

  <!-- Display Messages -->
  <?php if ($addError): ?>
    <div class="alert alert-danger"><?= $addError; ?></div>
  <?php elseif ($addSuccess): ?>
    <div class="alert alert-success"><?= $addSuccess; ?></div>
  <?php endif; ?>

  <!-- Add Product Form: Only visible to admin or boss -->
  <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['admin', 'boss'])): ?>
    <button class="btn btn-success mb-3" type="button" data-toggle="collapse" data-target="#addProductForm" aria-expanded="false" aria-controls="addProductForm">
      Add New Product
    </button>
    <div class="collapse" id="addProductForm">
      <div class="card card-body">
        <form method="post" action="products.php" enctype="multipart/form-data">
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
  <?php endif; ?>

  <!-- Products List Cards -->
  <div class="row mt-4">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-md-4 mb-4">
          <div class="card h-100">
            <img src="<?= htmlspecialchars(!empty($row['image']) ? $row['image'] : "https://via.placeholder.com/300x200?text=No+Image"); ?>" 
                 class="card-img-top" 
                 alt="<?= htmlspecialchars($row['name']); ?>" 
                 style="max-height: 200px; object-fit: cover;">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($row['name']); ?></h5>
              <p class="card-text">
                <strong>Brand:</strong> <?= htmlspecialchars($row['brand']); ?><br>
                <strong>Category:</strong> <?= htmlspecialchars($row['category']); ?><br>
                <strong>Price:</strong> $<?= number_format($row['price'], 2); ?><br>
                <strong>Stock:</strong> <?= $row['stock']; ?>
              </p>
            </div>
            <div class="card-footer text-center">
              <small class="text-muted">Added on <?= $row['created_at']; ?></small>
              <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['admin', 'boss'])): ?>
                <div class="mt-2">
                  <a href="edit_product.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                  <a href="delete_product.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?');">Delete</a>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12 text-center">
        <p>No products found.</p>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
