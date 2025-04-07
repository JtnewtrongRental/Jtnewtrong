<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'includes/db.php';

// Fetch metrics
$result_products = $conn->query("SELECT COUNT(*) AS total FROM products");
$total_products = $result_products->fetch_assoc()['total'];

$result_rentals = $conn->query("SELECT COUNT(*) AS total FROM rentals");
$total_rentals = $result_rentals->fetch_assoc()['total'];

$result_tickets = $conn->query("SELECT COUNT(*) AS total FROM support_tickets");
$total_tickets = $result_tickets->fetch_assoc()['total'];

$result_users = $conn->query("SELECT COUNT(*) AS total FROM users");
$total_users = $result_users->fetch_assoc()['total'];

// Recent products
$recent_products = $conn->query("SELECT id, name, created_at FROM products ORDER BY created_at DESC LIMIT 5");

// Chart data: Product distribution by category
$sqlChart = "SELECT category, COUNT(*) AS count FROM products GROUP BY category";
$resultChart = $conn->query($sqlChart);
$chart_categories = [];
$chart_counts = [];
while ($row = $resultChart->fetch_assoc()) {
    $chart_categories[] = $row['category'];
    $chart_counts[] = $row['count'];
}

include 'includes/header.php';
$settings = getSettings($conn);
$background_color = $settings['background_color'];
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <a class="navbar-brand" href="#">Admin Dashboard</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarAdmin" aria-controls="navbarAdmin" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarAdmin">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <span class="nav-link">Welcome, <strong><?= htmlspecialchars($_SESSION['user']['username']); ?></strong></span>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="manage_settings.php">Settings</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container">
  <p class="lead">Manage all aspects of the platform using the tools below.</p>

  <!-- Metrics Cards -->
  <div class="row">
    <div class="col-md-3 mb-3">
      <div class="card text-white bg-primary h-100 shadow">
        <div class="card-body">
          <h5 class="card-title">Total Products</h5>
          <p class="card-text display-4"><?= $total_products; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card text-white bg-success h-100 shadow">
        <div class="card-body">
          <h5 class="card-title">Total Rentals</h5>
          <p class="card-text display-4"><?= $total_rentals; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card text-white bg-warning h-100 shadow">
        <div class="card-body">
          <h5 class="card-title">Support Tickets</h5>
          <p class="card-text display-4"><?= $total_tickets; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card text-white bg-danger h-100 shadow">
        <div class="card-body">
          <h5 class="card-title">Total Users</h5>
          <p class="card-text display-4"><?= $total_users; ?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Management Cards -->
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="card shadow h-100">
        <div class="card-header bg-light">
          <h5 class="mb-0">Manage Products</h5>
        </div>
        <div class="card-body">
          <p>Add, edit, or remove products.</p>
          <a href="manage_products.php" class="btn btn-primary">Products</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow h-100">
        <div class="card-header bg-light">
          <h5 class="mb-0">Manage Rentals</h5>
        </div>
        <div class="card-body">
          <p>Review rental bookings.</p>
          <a href="manage_rentals.php" class="btn btn-primary">Rentals</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow h-100">
        <div class="card-header bg-light">
          <h5 class="mb-0">Support Tickets</h5>
        </div>
        <div class="card-body">
          <p>Handle support requests.</p>
          <a href="manage_support.php" class="btn btn-primary">Support</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Additional Management Cards -->
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="card shadow h-100">
        <div class="card-header bg-light">
          <h5 class="mb-0">Manage Users</h5>
        </div>
        <div class="card-body">
          <p>Manage user accounts and roles.</p>
          <a href="manage_users.php" class="btn btn-primary">Users</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow h-100">
        <div class="card-header bg-light">
          <h5 class="mb-0">Employee Management</h5>
        </div>
        <div class="card-body">
          <p>Manage employee records and performance.</p>
          <a href="employee_management.php" class="btn btn-primary">Employees</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow h-100">
        <div class="card-header bg-light">
          <h5 class="mb-0">Manage Customers</h5>
        </div>
        <div class="card-body">
          <p>Manage customer details linked to user accounts.</p>
          <a href="manage_customers.php" class="btn btn-primary">Customers</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Chart Card -->
  <div class="card shadow mb-4">
    <div class="card-header bg-info text-white">
      <h5 class="mb-0">Product Distribution by Category</h5>
    </div>
    <div class="card-body">
      <canvas id="adminChart" width="400" height="150"></canvas>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('adminChart').getContext('2d');
  const adminChart = new Chart(ctx, {
      type: 'bar',
      data: {
          labels: <?= json_encode($chart_categories); ?>,
          datasets: [{
              label: 'Products per Category',
              data: <?= json_encode($chart_counts); ?>,
              backgroundColor: 'rgba(54, 162, 235, 0.5)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1
          }]
      },
      options: {
          scales: {
              y: {
                  beginAtZero: true,
                  precision: 0
              }
          }
      }
  });
</script>
<?php include 'includes/footer.php'; ?>
