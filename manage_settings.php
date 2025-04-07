<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $background_color = $_POST['background_color'];
    $text_color = $_POST['text_color'];
    $button_color = $_POST['button_color'];
    $user_background_color = $_POST['user_background_color'];
    $staff_background_color = $_POST['staff_background_color'];

    $stmt = $conn->prepare("UPDATE settings SET background_color = ?, text_color = ?, button_color = ?, user_background_color = ?, staff_background_color = ? WHERE id = 1");
    $stmt->bind_param("sssss", $background_color, $text_color, $button_color, $user_background_color, $staff_background_color);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_settings.php?success=1");
    exit();
}

$settings = getSettings($conn);
?>
<?php include 'includes/header.php'; ?>
<div class="container">
  <h2>Manage Settings</h2>
  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Settings updated successfully!</div>
  <?php endif; ?>
  <form method="POST">
    <div class="form-group">
      <label for="background_color">Background Color</label>
      <input type="color" id="background_color" name="background_color" class="form-control" value="<?= htmlspecialchars($settings['background_color']); ?>">
    </div>
    <div class="form-group">
      <label for="text_color">Text Color</label>
      <input type="color" id="text_color" name="text_color" class="form-control" value="<?= htmlspecialchars($settings['text_color']); ?>">
    </div>
    <div class="form-group">
      <label for="button_color">Button Color</label>
      <input type="color" id="button_color" name="button_color" class="form-control" value="<?= htmlspecialchars($settings['button_color']); ?>">
    </div>
    <div class="form-group">
      <label for="user_background_color">User Background Color</label>
      <input type="color" id="user_background_color" name="user_background_color" class="form-control" value="<?= htmlspecialchars($settings['user_background_color']); ?>">
    </div>
    <div class="form-group">
      <label for="staff_background_color">Staff Background Color</label>
      <input type="color" id="staff_background_color" name="staff_background_color" class="form-control" value="<?= htmlspecialchars($settings['staff_background_color']); ?>">
    </div>
    <button type="submit" class="btn btn-primary">Save Settings</button>
  </form>
</div>
<?php include 'includes/footer.php'; ?>
