<?php 
session_start();
include 'includes/header.php';
include 'includes/db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $customer_name = $_POST['customer_name'];
  $rental_item = $_POST['rental_item'];
  $start_date = $_POST['start_date'];
  $end_date = $_POST['end_date'];
  $total_price = $_POST['total_price'];

  $stmt = $conn->prepare("INSERT INTO rentals (customer_name, rental_item, start_date, end_date, total_price) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("ssssd", $customer_name, $rental_item, $start_date, $end_date, $total_price);
  if($stmt->execute()){
    echo '<div class="alert alert-success">Rental booking submitted successfully.</div>';
  } else {
    echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
  }
  $stmt->close();
}
?>
<h2>Rental Booking</h2>
<form method="post" action="rental.php">
  <div class="form-group">
    <label for="customer_name">Your Name</label>
    <input type="text" class="form-control" id="customer_name" name="customer_name" required>
  </div>
  <div class="form-group">
    <label for="rental_item">Rental Item</label>
    <input type="text" class="form-control" id="rental_item" name="rental_item" required>
  </div>
  <div class="form-group">
    <label for="start_date">Start Date</label>
    <input type="date" class="form-control" id="start_date" name="start_date" required>
  </div>
  <div class="form-group">
    <label for="end_date">End Date</label>
    <input type="date" class="form-control" id="end_date" name="end_date" required>
  </div>
  <div class="form-group">
    <label for="total_price">Total Price ($)</label>
    <input type="number" step="0.01" class="form-control" id="total_price" name="total_price" required>
  </div>
  <button type="submit" class="btn btn-primary">Submit Rental Booking</button>
</form>
<?php include 'includes/footer.php'; ?>
