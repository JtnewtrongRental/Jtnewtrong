<?php
session_start();
include 'includes/db.php';
if(isset($_SESSION['user'])){
    header("Location: dashboard.php");
    exit();
}
$error = "";
$success = "";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    if($password !== $confirm_password){
        $error = "Passwords do not match.";
    } elseif(strlen($password) < 6){
        $error = "Password must be at least 6 characters long.";
    } else {
         $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
         $stmt->bind_param("s", $username);
         $stmt->execute();
         $result = $stmt->get_result();
         if($result->num_rows > 0){
             $error = "Username already exists.";
         } else {
             $hashed_password = password_hash($password, PASSWORD_DEFAULT);
             $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'other')");
             $stmt->bind_param("ss", $username, $hashed_password);
             if($stmt->execute()){
                $success = "Registration successful. You can now <a href='login.php'>login</a>.";
             } else {
                $error = "Registration failed.";
             }
         }
         $stmt->close();
    }
}
include 'includes/header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <h2>Register</h2>
    <?php if($error != ""): ?>
      <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>
    <?php if($success != ""): ?>
      <div class="alert alert-success"><?= $success; ?></div>
    <?php endif; ?>
    <form method="post" action="register.php">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
      </div>
      <button type="submit" class="btn btn-primary">Register</button>
    </form>
    <p class="mt-3">Already have an account? <a href="login.php">Login here</a>.</p>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
