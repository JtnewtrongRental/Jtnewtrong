<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include 'includes/db.php';

$updateSuccess = "";
$updateError = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    if (empty($new_password)) {
        $updateError = "Please enter a new password.";
    } elseif ($new_password !== $confirm_password) {
        $updateError = "Passwords do not match.";
    } elseif (strlen($new_password) < 6) {
        $updateError = "Password must be at least 6 characters long.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $_SESSION['user']['id']);
        if ($stmt->execute()) {
            $updateSuccess = "Password updated successfully.";
        } else {
            $updateError = "Error updating password: " . $stmt->error;
        }
        $stmt->close();
    }
}
include 'includes/header.php';
?>
<div class="container">
  <h2>My Profile</h2>
  <p>Username: <strong><?= htmlspecialchars($_SESSION['user']['username']); ?></strong></p>
  
  <h4>Update Password</h4>
  <?php if ($updateError): ?>
    <div class="alert alert-danger"><?= $updateError; ?></div>
  <?php elseif ($updateSuccess): ?>
    <div class="alert alert-success"><?= $updateSuccess; ?></div>
  <?php endif; ?>
  
  <form method="post" action="user_profile.php">
    <div class="form-group">
      <label for="new_password">New Password</label>
      <input type="password" name="new_password" id="new_password" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="confirm_password">Confirm New Password</label>
      <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Update Password</button>
  </form>
  
  <a href="user_page.php" class="btn btn-secondary mt-3">Back to User Dashboard</a>
</div>
<?php include 'includes/footer.php'; ?>
