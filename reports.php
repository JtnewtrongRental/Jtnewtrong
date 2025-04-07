<?php
session_start();
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'boss')) {
    header("Location: login.php");
    exit();
}
include 'includes/db.php';

// -----------------------------
// CSV DOWNLOAD FUNCTIONALITY
// -----------------------------
if (isset($_GET['download']) && isset($_GET['type'])) {
    $type = $_GET['type'];
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $type . '_report.csv"');
    $output = fopen('php://output', 'w');
    if ($type == 'products') {
        fputcsv($output, array('ID', 'Name', 'Brand', 'Unit', 'Category', 'Description', 'Price', 'Stock', 'Created At'));
        $query = "SELECT id, name, brand, unit, category, description, price, stock, created_at FROM products";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row);
        }
    } elseif ($type == 'rentals') {
        fputcsv($output, array('ID', 'Customer Name', 'Rental Item', 'Start Date', 'End Date', 'Total Price', 'Status', 'Created At'));
        $query = "SELECT id, customer_name, rental_item, start_date, end_date, total_price, status, created_at FROM rentals";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row);
        }
    } elseif ($type == 'support') {
        fputcsv($output, array('ID', 'Customer Name', 'Subject', 'Description', 'Status', 'Created At'));
        $query = "SELECT id, customer_name, subject, description, status, created_at FROM support_tickets";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row);
        }
    }
    fclose($output);
    exit();
}

// -----------------------------
// FETCH DATA FOR REPORTS
// -----------------------------
// Chart 1: Product Distribution by Category
$sqlProductsChart = "SELECT category, COUNT(*) AS count FROM products GROUP BY category";
$resultProductsChart = $conn->query($sqlProductsChart);
$chart_categories = [];
$chart_counts = [];
while ($row = $resultProductsChart->fetch_assoc()) {
    $chart_categories[] = $row['category'];
    $chart_counts[] = $row['count'];
}

// Chart 2: Rental Distribution by Status
$sqlRentalsChart = "SELECT status, COUNT(*) AS count FROM rentals GROUP BY status";
$resultRentalsChart = $conn->query($sqlRentalsChart);
$rental_statuses = [];
$rental_counts = [];
while ($row = $resultRentalsChart->fetch_assoc()) {
    $rental_statuses[] = $row['status'];
    $rental_counts[] = $row['count'];
}

// Summary Metrics
$resultTotalProducts = $conn->query("SELECT COUNT(*) as total FROM products");
$total_products = $resultTotalProducts->fetch_assoc()['total'];

$resultTotalRentals = $conn->query("SELECT COUNT(*) as total FROM rentals");
$total_rentals = $resultTotalRentals->fetch_assoc()['total'];

$resultTotalTickets = $conn->query("SELECT COUNT(*) as total FROM support_tickets");
$total_tickets = $resultTotalTickets->fetch_assoc()['total'];

$resultTotalUsers = $conn->query("SELECT COUNT(*) as total FROM users");
$total_users = $resultTotalUsers->fetch_assoc()['total'];

include 'includes/header.php';
?>

<div class="container">
  <h2 class="mb-4">Advanced Reports & Analytics</h2>
  <p>Welcome, <strong><?= htmlspecialchars($_SESSION['user']['username']); ?></strong>. Below you can view interactive reports and download data for further analysis.</p>
  
  <!-- Summary Metrics -->
  <div class="row mb-4">
    <div class="col-md-3">
      <div class="card text-white bg-primary">
        <div class="card-body">
          <h5 class="card-title">Products</h5>
          <p class="card-text display-4"><?= $total_products; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-success">
        <div class="card-body">
          <h5 class="card-title">Rentals</h5>
          <p class="card-text display-4"><?= $total_rentals; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-warning">
        <div class="card-body">
          <h5 class="card-title">Tickets</h5>
          <p class="card-text display-4"><?= $total_tickets; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-danger">
        <div class="card-body">
          <h5 class="card-title">Users</h5>
          <p class="card-text display-4"><?= $total_users; ?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Download Buttons -->
  <div class="mb-4">
    <h4>Download Reports</h4>
    <a href="reports.php?download=1&type=products" class="btn btn-outline-primary">Download Products CSV</a>
    <a href="reports.php?download=1&type=rentals" class="btn btn-outline-success">Download Rentals CSV</a>
    <a href="reports.php?download=1&type=support" class="btn btn-outline-warning">Download Support Tickets CSV</a>
  </div>
  
  <!-- Charts -->
  <div class="row">
    <!-- Chart: Product Distribution by Category -->
    <div class="col-md-6">
      <h4>Product Distribution by Category</h4>
      <canvas id="productsChart" width="400" height="300"></canvas>
    </div>
    <!-- Chart: Rental Distribution by Status -->
    <div class="col-md-6">
      <h4>Rental Distribution by Status</h4>
      <canvas id="rentalsChart" width="400" height="300"></canvas>
    </div>
  </div>
  
  <a href="dashboard.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
</div>

<!-- Include Chart.js via CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Chart for Product Distribution by Category
  const ctxProducts = document.getElementById('productsChart').getContext('2d');
  const productsChart = new Chart(ctxProducts, {
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

  // Chart for Rental Distribution by Status
  const ctxRentals = document.getElementById('rentalsChart').getContext('2d');
  const rentalsChart = new Chart(ctxRentals, {
      type: 'pie',
      data: {
          labels: <?= json_encode($rental_statuses); ?>,
          datasets: [{
              label: 'Rentals by Status',
              data: <?= json_encode($rental_counts); ?>,
              backgroundColor: [
                  'rgba(255, 205, 86, 0.5)',
                  'rgba(75, 192, 192, 0.5)',
                  'rgba(153, 102, 255, 0.5)'
              ],
              borderColor: [
                  'rgba(255, 205, 86, 1)',
                  'rgba(75, 192, 192, 1)',
                  'rgba(153, 102, 255, 1)'
              ],
              borderWidth: 1
          }]
      },
      options: {
          responsive: true
      }
  });
</script>

<?php include 'includes/footer.php'; ?>
