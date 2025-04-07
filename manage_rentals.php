<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'includes/db.php';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $rental_id = intval($_POST['rental_id']);
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE rentals SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $rental_id);
    $stmt->execute();
    $stmt->close();
    echo '<div class="alert alert-success">Rental status updated.</div>';
}

$sql = "SELECT * FROM rentals ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<div class="container">
  <h2>Manage Rentals</h2>
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Customer Name</th>
        <th>Rental Item</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Total Price ($)</th>
        <th>Status</th>
        <th>Created At</th>
        <th>Update Status</th>
      </tr>
    </thead>
    <tbody>
      <?php if($result->num_rows > 0): ?>
        <?php while($rental = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $rental['id']; ?></td>
            <td><?= htmlspecialchars($rental['customer_name']); ?></td>
            <td><?= htmlspecialchars($rental['rental_item']); ?></td>
            <td><?= $rental['start_date']; ?></td>
            <td><?= $rental['end_date']; ?></td>
            <td><?= $rental['total_price']; ?></td>
            <td><?= $rental['status']; ?></td>
            <td><?= $rental['created_at']; ?></td>
            <td>
              <form method="post" action="manage_rentals.php">
                <input type="hidden" name="rental_id" value="<?= $rental['id']; ?>">
                <select name="status" class="form-control form-control-sm" required>
                  <option value="Pending" <?= ($rental['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                  <option value="Approved" <?= ($rental['status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                  <option value="Completed" <?= ($rental['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                </select>
                <button type="submit" name="update_status" class="btn btn-sm btn-primary mt-1">Update</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="9">No rentals found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<?php include 'includes/footer.php'; ?>
