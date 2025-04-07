<?php
session_start();
// Allow both regular users and admin to view this page
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include 'includes/db.php';
include 'includes/header.php';

$msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_customer'])) {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $phone   = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    // Process photo upload if provided
    $photo = "";
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK){
        $uploadDir = 'uploads/';
        // Ensure the folder exists and is writable
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
    
    // If the logged-in user is a regular user, tie the record to their user id;
    // otherwise, they can optionally select a user (here we default to 0 for admin added customers)
    $user_id = isset($_SESSION['user']['id']) ? intval($_SESSION['user']['id']) : 0;
    
    // Update the INSERT statement to include the photo field.
    $stmt = $conn->prepare("INSERT INTO customers (user_id, name, email, phone, address, photo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $name, $email, $phone, $address, $photo);
    if ($stmt->execute()) {
        $msg = '<div class="alert alert-success">Customer added successfully.</div>';
    } else {
        $msg = '<div class="alert alert-danger">Error adding customer: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}

// Regular users (role "user") see only their customer record; higher roles see all.
if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 'user') {
    $user_id = intval($_SESSION['user']['id']);
    $sql = "SELECT * FROM customers WHERE user_id = $user_id ORDER BY created_at DESC";
} else {
    $sql = "SELECT * FROM customers ORDER BY created_at DESC";
}
$result = $conn->query($sql);
?>
<div class="container mt-4">
  <h2>Manage Customers</h2>
  <?= $msg; ?>
  <div class="mb-4">
    <h4>Add New Customer</h4>
    <!-- Added enctype for file uploads -->
    <form method="post" action="manage_customers.php" enctype="multipart/form-data">
      <input type="hidden" name="add_customer" value="1">
      <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Phone</label>
        <input type="text" name="phone" class="form-control">
      </div>
      <div class="form-group">
        <label>Address</label>
        <textarea name="address" class="form-control"></textarea>
      </div>
      <!-- New customer photo upload field -->
      <div class="form-group">
        <label>Photo</label>
        <input type="file" name="photo" class="form-control-file">
      </div>
      <button type="submit" class="btn btn-primary">Add Customer</button>
    </form>
  </div>
  <h4>Customer List</h4>
  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="thead-dark">
        <tr>
          <th>No</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Address</th>
          <th>Joined</th>
          <!-- Optionally show photo thumbnail -->
          <th>Photo</th>
          <?php if(isset($_SESSION['user']['role']) && $_SESSION['user']['role'] != 'user'): ?>
            <th>Actions</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $i++; ?></td>
              <td><?= htmlspecialchars($row['name']); ?></td>
              <td><?= htmlspecialchars($row['email']); ?></td>
              <td><?= htmlspecialchars($row['phone']); ?></td>
              <td><?= htmlspecialchars($row['address']); ?></td>
              <td><?= $row['created_at']; ?></td>
              <td>
                <?php if(!empty($row['photo'])): ?>
                  <img src="<?= $row['photo']; ?>" alt="Photo" style="max-width:50px; max-height:50px;">
                <?php else: ?>
                  N/A
                <?php endif; ?>
              </td>
              <?php if(isset($_SESSION['user']['role']) && $_SESSION['user']['role'] != 'user'): ?>
                <td>
                  <a href="edit_customer.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                  <a href="delete_customer.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this customer?');">Delete</a>
                </td>
              <?php endif; ?>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="<?=(isset($_SESSION['user']['role']) && $_SESSION['user']['role'] != 'user') ? 8 : 7?>" class="text-center">No customers found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include 'includes/footer.php'; ?>