<?php 
session_start();
include 'includes/header.php';
include 'includes/db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $customer_name = $_POST['customer_name'];
  $subject = $_POST['subject'];
  $description = $_POST['description'];

  $stmt = $conn->prepare("INSERT INTO support_tickets (customer_name, subject, description) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $customer_name, $subject, $description);
  if($stmt->execute()){
    echo '<div class="alert alert-success">Support ticket submitted successfully.</div>';
  } else {
    echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
  }
  $stmt->close();
}
?>
<h2>IT Support Ticket</h2>
<form method="post" action="support.php">
  <div class="form-group">
    <label for="customer_name">Your Name</label>
    <input type="text" class="form-control" id="customer_name" name="customer_name" required>
  </div>
  <div class="form-group">
    <label for="subject">Subject</label>
    <input type="text" class="form-control" id="subject" name="subject" required>
  </div>
  <div class="form-group">
    <label for="description">Description</label>
    <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
  </div>
  <button type="submit" class="btn btn-primary">Submit Ticket</button>
</form>
<?php include 'includes/footer.php'; ?>
