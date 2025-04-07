<?php
session_start();
// Removed session check for direct access convenience
include 'includes/header.php';

// Redirect users directly to their respective pages based on roles
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] == 'admin') {
        header("Location: admin_page.php");
        exit();
    } elseif ($_SESSION['user']['role'] == 'staff') {
        header("Location: staff_page.php");
        exit();
    } elseif ($_SESSION['user']['role'] == 'boss') {
        header("Location: boss_page.php");
        exit();
    } else {
        header("Location: user_page.php");
        exit();
    }
}
?>
<h2>Dashboard</h2>
<p>Welcome, <?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['username']) : 'Guest'; ?>! 
   You are logged in as <strong><?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['role']) : 'Guest'; ?></strong>.</p>
<a href="logout.php" class="btn btn-secondary mt-3">Logout</a>
<?php include 'includes/footer.php'; ?>
