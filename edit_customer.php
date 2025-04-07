<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include 'includes/db.php';
include 'includes/header.php';

$msg = '';
if (!isset($_GET['id'])) {
    header("Location: manage_customers.php");
    exit();
}

$customer_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo '<div class="alert alert-danger">Customer not found.</div>';
    include 'includes/footer.php';
    exit();
}
$customer = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_customer'])) {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $phone   = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $photo   = $customer['photo']; // keep previously uploaded photo if no new one
    
    // Process new photo upload if provided
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK){
        $uploadDir = 'uploads/';
        if(!file_exists($uploadDir)){
            mkdir($uploadDir, 0755, true);
        }
        $tmpName  = $_FILES['photo']['tmp_name'];
        $filename = basename($_FILES['photo']['name']);
        $targetFile = $uploadDir . time() . "_" . $filename;
        if(move_uploaded_file($tmpName, $targetFile)){
            $photo = $targetFile;
        }
    }
    
    $stmt = $conn->prepare("UPDATE customers SET name = ?, email = ?, phone = ?, address = ?, photo = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $name, $email, $phone, $address, $photo, $customer_id);
    if ($stmt->execute()) {
        $msg = '<div class="alert alert-success">Customer updated successfully.</div>';
        $customer['name']    = $name;
        $customer['email']   = $email;
        $customer['phone']   = $phone;
        $customer['address'] = $address;
        $customer['photo']   = $photo;
    } else {
        $msg = '<div class="alert alert-danger">Error updating customer: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}
?>
<div class="container mt-4">
  <h2>Edit Customer</h2>
  <?= $msg; ?>
  <!-- Added enctype for file uploads -->
  <form method="post" action="edit_customer.php?id=<?= $customer_id; ?>" enctype="multipart/form-data">
    <input type="hidden" name="update_customer" value="1">
    <div class="form-group">
      <label>Name</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($customer['name']); ?>" required>
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($customer['email']); ?>" required>
    </div>
    <div class="form-group">
      <label>Phone</label>
      <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($customer['phone']); ?>">
    </div>
    <div class="form-group">
      <label>Address</label>
      <textarea name="address" class="form-control"><?= htmlspecialchars($customer['address']); ?></textarea>
    </div>
    <div class="form-group">
      <label>Photo</label>
      <?php if(!empty($customer['photo'])): ?>
        <div class="mb-2">
          <img src="<?= $customer['photo']; ?>" alt="Photo" style="max-width:100px; max-height:100px;">
        </div>
      <?php endif; ?>
      <input type="file" name="photo" class="form-control-file">
    </div>
    <button type="submit" class="btn btn-primary">Update Customer</button>
    <a href="manage_customers.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
<?php include 'includes/footer.php'; ?>