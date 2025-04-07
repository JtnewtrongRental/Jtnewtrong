<?php
session_start();
if (!isset($_SESSION['user'])) {  // Optionally restrict by boss/admin
    header("Location: login.php");
    exit();
}
include 'includes/db.php';
include 'includes/header.php';

// Prepare sales data for last 12 months
$salesData = array();
$months = array();
for ($i = 11; $i >= 0; $i--) {
    $month = date("Y-m", strtotime("-$i months"));
    $months[] = date("M Y", strtotime("$month-01"));
    $stmt = $conn->prepare("SELECT SUM(total_price) as month_total FROM orders WHERE DATE_FORMAT(created_at, '%Y-%m') = ?");
    $stmt->bind_param("s", $month);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $salesData[] = $row['month_total'] ? $row['month_total'] : 0;
    $stmt->close();
}
$resultTotalSales = $conn->query("SELECT SUM(total_price) as total_sales FROM orders");
$rowTotal = $resultTotalSales->fetch_assoc();
$totalSales = $rowTotal['total_sales'] ? $rowTotal['total_sales'] : 0;

// Handle date range filter
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date("Y-m-01");
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date("Y-m-t");

$stmt = $conn->prepare("SELECT SUM(total_price) as total_sales FROM orders WHERE created_at BETWEEN ? AND ?");
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$filteredTotalSales = $row['total_sales'] ? $row['total_sales'] : 0;
$stmt->close();
?>
<h2>Sales Reporting and Analytics</h2>

<!-- Date Range Filter Form -->
<form method="GET" class="mb-4">
  <div class="row">
    <div class="col-md-4">
      <label for="start_date">Start Date:</label>
      <input type="date" id="start_date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate); ?>">
    </div>
    <div class="col-md-4">
      <label for="end_date">End Date:</label>
      <input type="date" id="end_date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate); ?>">
    </div>
    <div class="col-md-4 align-self-end">
      <button type="submit" class="btn btn-primary">Filter</button>
    </div>
  </div>
</form>

<div class="row mb-4">
  <div class="col-md-4">
    <div class="card text-white bg-success">
      <div class="card-body">
        <h5 class="card-title">Filtered Sales</h5>
        <p class="card-text display-4">$<?= number_format($filteredTotalSales, 2); ?></p>
      </div>
    </div>
  </div>
</div>

<div class="row mb-4">
  <div class="col-md-4">
    <div class="card text-white bg-success">
      <div class="card-body">
        <h5 class="card-title">Total Sales</h5>
        <p class="card-text display-4">$<?= number_format($totalSales, 2); ?></p>
      </div>
    </div>
  </div>
</div>
<div class="card mb-4">
  <div class="card-header bg-info text-white">
    <h4>Monthly Sales Trends (Last 12 Months)</h4>
  </div>
  <div class="card-body">
    <canvas id="salesChart" width="400" height="200"></canvas>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('salesChart').getContext('2d');
  const salesChart = new Chart(ctx, {
      type: 'line',
      data: {
          labels: <?= json_encode($months); ?>,
          datasets: [{
              label: 'Sales ($)',
              data: <?= json_encode($salesData); ?>,
              fill: false,
              borderColor: 'rgba(75, 192, 192, 1)',
              tension: 0.1
          }]
      },
      options: {
          scales: {
              y: {
                  beginAtZero: true,
                  ticks: {
                      callback: function(value) { return '$' + value; }
                  }
              }
          }
      }
  });
</script>
<a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
<?php include 'includes/footer.php'; ?>
