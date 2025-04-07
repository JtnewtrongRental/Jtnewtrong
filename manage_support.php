<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'includes/db.php';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_ticket'])) {
    $ticket_id = intval($_POST['ticket_id']);
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE support_tickets SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $ticket_id);
    $stmt->execute();
    $stmt->close();
    echo '<div class="alert alert-success">Ticket status updated.</div>';
}

$sql = "SELECT * FROM support_tickets ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<div class="container">
  <h2>Manage Support Tickets</h2>
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Customer Name</th>
        <th>Subject</th>
        <th>Description</th>
        <th>Status</th>
        <th>Created At</th>
        <th>Update Status</th>
      </tr>
    </thead>
    <tbody>
      <?php if($result->num_rows > 0): ?>
        <?php while($ticket = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $ticket['id']; ?></td>
            <td><?= htmlspecialchars($ticket['customer_name']); ?></td>
            <td><?= htmlspecialchars($ticket['subject']); ?></td>
            <td><?= htmlspecialchars($ticket['description']); ?></td>
            <td><?= $ticket['status']; ?></td>
            <td><?= $ticket['created_at']; ?></td>
            <td>
              <form method="post" action="manage_support.php">
                <input type="hidden" name="ticket_id" value="<?= $ticket['id']; ?>">
                <select name="status" class="form-control form-control-sm" required>
                  <option value="Open" <?= ($ticket['status'] == 'Open') ? 'selected' : ''; ?>>Open</option>
                  <option value="In Progress" <?= ($ticket['status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                  <option value="Closed" <?= ($ticket['status'] == 'Closed') ? 'selected' : ''; ?>>Closed</option>
                </select>
                <button type="submit" name="update_ticket" class="btn btn-sm btn-primary mt-1">Update</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="7">No support tickets found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<?php include 'includes/footer.php'; ?>
