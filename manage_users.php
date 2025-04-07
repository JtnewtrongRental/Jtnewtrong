<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'includes/db.php';
include 'includes/header.php';

// Handle user role update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $user_id = intval($_POST['user_id']);
    $role = $_POST['role'];
    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $role, $user_id);
    $stmt->execute();
    $stmt->close();
    echo '<div class="alert alert-success">User role updated successfully.</div>';
}

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    if ($delete_id != $_SESSION['user']['id']) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();
        echo '<div class="alert alert-success">User deleted successfully.</div>';
    }
}

$sql = "SELECT * FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<div class="container mt-4">
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">Manage Users</h4>
    </div>
    <div class="card-body">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Created At</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while ($user = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $user['id']; ?></td>
                <td><?= htmlspecialchars($user['username']); ?></td>
                <td><?= $user['role']; ?></td>
                <td><?= $user['created_at']; ?></td>
                <td class="text-center">
                  <form method="post" action="manage_users.php" class="form-inline d-inline">
                    <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                    <select name="role" class="form-control form-control-sm mr-2" required>
                      <option value="admin" <?= ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                      <option value="staff" <?= ($user['role'] == 'staff') ? 'selected' : ''; ?>>Staff</option>
                      <option value="boss" <?= ($user['role'] == 'boss') ? 'selected' : ''; ?>>Boss</option>
                      <option value="other" <?= ($user['role'] == 'other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                    <button type="submit" name="update_user" class="btn btn-sm btn-primary">Update</button>
                  </form>
                  <?php if ($user['id'] != $_SESSION['user']['id']): ?>
                    <a href="manage_users.php?delete_id=<?= $user['id']; ?>" class="btn btn-sm btn-danger ml-1" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center">No users found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
