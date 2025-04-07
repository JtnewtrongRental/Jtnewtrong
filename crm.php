<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include 'includes/db.php';
include 'includes/header.php';

$crmMsg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_customer'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    $stmt = $conn->prepare("INSERT INTO customers (name, email, phone, address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $address);
    if ($stmt->execute()) {
        $crmMsg = "Customer added successfully.";
    } else {
        $crmMsg = "Error adding customer: " . $stmt->error;
    }
    $stmt->close();
}
$sql = "SELECT * FROM customers ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<h2>Customer Relationship Management (CRM)</h2>
<?php if ($crmMsg != ""): ?>
  <div class="alert alert-info"><?= $crmMsg; ?></div>
<?php endif; ?>
<div class="mb-4">
  <h4>Add New Customer</h4>
  <form method="post" action="crm.php">
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
    <button type="submit" class="btn btn-primary">Add Customer</button>
  </form>
</div>
<h4>Customer List</h4>
<table class="table table-bordered table-hover">
  <thead class="thead-dark">
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Address</th>
      <th>Joined</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($result->num_rows > 0): ?>
      <?php while ($customer = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $customer['id']; ?></td>
          <td><?= htmlspecialchars($customer['name']); ?></td>
          <td><?= htmlspecialchars($customer['email']); ?></td>
          <td><?= htmlspecialchars($customer['phone']); ?></td>
          <td><?= htmlspecialchars($customer['address']); ?></td>
          <td><?= $customer['created_at']; ?></td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="6">No customers found.</td></tr>
    <?php endif; ?>
  </tbody>
</table>
<?php include 'includes/footer.php'; ?>
