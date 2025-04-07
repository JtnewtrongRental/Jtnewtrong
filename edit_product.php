<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: manage_products.php");
    exit();
}

$productId = intval($_GET['id']);
$error = "";
$success = "";

// Fetch product details
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows != 1) {
    die("Product not found.");
}
$product = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $brand = trim($_POST['brand']);
    $unit = trim($_POST['unit']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    
    // Default to current image if no new image uploaded
    $image = $_POST['current_image'];
    
    // Process new image upload if provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . uniqid("prod_", true) . "_" . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = $target_file;
            // Optionally, delete the old image file if it exists and is different from placeholder
            if (!empty($_POST['current_image']) && file_exists($_POST['current_image'])) {
                unlink($_POST['current_image']);
            }
        }
    }
    
    // Update product
    $stmt = $conn->prepare("UPDATE products SET name = ?, brand = ?, unit = ?, category = ?, description = ?, price = ?, stock = ?, image = ? WHERE id = ?");
    $stmt->bind_param("sssssdisi", $name, $brand, $unit, $category, $description, $price, $stock, $image, $productId);
    if ($stmt->execute()) {
        $success = "Product updated successfully.";
        // Refresh product info
        $product['name'] = $name;
        $product['brand'] = $brand;
        $product['unit'] = $unit;
        $product['category'] = $category;
        $product['description'] = $description;
        $product['price'] = $price;
        $product['stock'] = $stock;
        $product['image'] = $image;
    } else {
        $error = "Error updating product: " . $stmt->error;
    }
    $stmt->close();
}
include 'includes/header.php';
?>
<div class="container">
  <h2>Edit Product</h2>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?= $error; ?></div>
  <?php elseif ($success): ?>
    <div class="alert alert-success"><?= $success; ?></div>
  <?php endif; ?>
  <form method="post" action="edit_product.php?id=<?= $productId; ?>" enctype="multipart/form-data">
    <div class="form-group">
      <label>Product Name</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']); ?>" required>
    </div>
    <div class="form-group">
      <label>Brand</label>
      <input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($product['brand']); ?>" required>
    </div>
    <div class="form-group">
      <label>Unit</label>
      <input type="text" name="unit" class="form-control" value="<?= htmlspecialchars($product['unit']); ?>" required>
    </div>
    <div class="form-group">
      <label>Category</label>
      <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($product['category']); ?>" required>
    </div>
    <div class="form-group">
      <label>Description</label>
      <textarea name="description" class="form-control"><?= htmlspecialchars($product['description']); ?></textarea>
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label>Price ($)</label>
        <input type="number" step="0.01" name="price" class="form-control" value="<?= $product['price']; ?>" required>
      </div>
      <div class="form-group col-md-6">
        <label>Stock Quantity</label>
        <input type="number" name="stock" class="form-control" value="<?= $product['stock']; ?>" required>
      </div>
    </div>
    <div class="form-group">
      <label>Current Product Image</label><br>
      <?php
      $img = !empty($product['image']) ? $product['image'] : "https://via.placeholder.com/150x150?text=No+Image";
      ?>
      <img src="<?= htmlspecialchars($img); ?>" alt="<?= htmlspecialchars($product['name']); ?>" style="max-width:150px;">
    </div>
    <div class="form-group">
      <label>Upload New Product Image (optional)</label>
      <input type="file" name="image" class="form-control-file">
      <!-- Keep current image path -->
      <input type="hidden" name="current_image" value="<?= htmlspecialchars($product['image']); ?>">
    </div>
    <button type="submit" class="btn btn-primary">Update Product</button>
    <a href="manage_products.php" class="btn btn-secondary">Back to Products</a>
  </form>
</div>
<?php include 'includes/footer.php'; ?>
