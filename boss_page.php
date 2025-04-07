<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'boss') {
    header("Location: login.php");
    exit();
}
include 'includes/db.php';

// Key metrics
$result_products = $conn->query("SELECT COUNT(*) as total FROM products");
$total_products = $result_products->fetch_assoc()['total'];

$result_rentals = $conn->query("SELECT COUNT(*) as total FROM rentals");
$total_rentals = $result_rentals->fetch_assoc()['total'];

$result_support = $conn->query("SELECT COUNT(*) as total FROM support_tickets");
$total_tickets = $result_support->fetch_assoc()['total'];

$result_users = $conn->query("SELECT COUNT(*) as total FROM users");
$total_users = $result_users->fetch_assoc()['total'];

$result_approved_rentals = $conn->query("SELECT COUNT(*) as total FROM rentals WHERE status = 'Approved'");
$total_approved_rentals = $result_approved_rentals->fetch_assoc()['total'];

$result_pending_tickets = $conn->query("SELECT COUNT(*) as total FROM support_tickets WHERE status = 'Open'");
$total_pending_tickets = $result_pending_tickets->fetch_assoc()['total'];

// Calculate financial metrics
// Total Income from all orders
$totalIncome = 0;
$resultOrders = $conn->query("SELECT SUM(total_price) as total_income FROM orders");
if ($resultOrders) {
    $row = $resultOrders->fetch_assoc();
    $totalIncome = $row['total_income'] ? $row['total_income'] : 0;
}

// Total Expenses: sum of (quantity * cost) for each order item (assumes 'cost' column exists in products)
$totalExpenses = 0;
$sqlExpense = "SELECT SUM(oi.quantity * p.cost) as total_expenses 
               FROM order_items oi 
               JOIN products p ON oi.product_id = p.id";
$resultExpense = $conn->query($sqlExpense);
if ($resultExpense) {
    $row = $resultExpense->fetch_assoc();
    $totalExpenses = $row['total_expenses'] ? $row['total_expenses'] : 0;
}

// Net Profit
$netProfit = $totalIncome - $totalExpenses;

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
?>
<div class="container">
  <h2 class="mb-4">Boss Dashboard</h2>
  <p>Welcome, <strong><?= htmlspecialchars($_SESSION['user']['username']); ?></strong>. Here are the latest metrics and financial reports.</p>
  
  <!-- Financial Metrics -->
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="card text-white bg-info mb-3">
        <div class="card-body">
          <h5 class="card-title">Total Income</h5>
          <p class="card-text display-4">$<?= number_format($totalIncome, 2); ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-white bg-secondary mb-3">
        <div class="card-body">
          <h5 class="card-title">Total Expenses</h5>
          <p class="card-text display-4">$<?= number_format($totalExpenses, 2); ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-white bg-success mb-3">
        <div class="card-body">
          <h5 class="card-title">Net Profit</h5>
          <p class="card-text display-4">$<?= number_format($netProfit, 2); ?></p>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Standard Metrics -->
  <div class="row mb-4">
    <div class="col-md-3">
      <div class="card text-white bg-primary h-100">
        <div class="card-body">
          <h5 class="card-title">Total Products</h5>
          <p class="card-text display-4"><?= $total_products; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-success h-100">
        <div class="card-body">
          <h5 class="card-title">Total Rentals</h5>
          <p class="card-text display-4"><?= $total_rentals; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-warning h-100">
        <div class="card-body">
          <h5 class="card-title">Support Tickets</h5>
          <p class="card-text display-4"><?= $total_tickets; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-danger h-100">
        <div class="card-body">
          <h5 class="card-title">Total Users</h5>
          <p class="card-text display-4"><?= $total_users; ?></p>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Additional Metrics -->
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title">Approved Rentals</h5>
          <p class="card-text display-4"><?= $total_approved_rentals; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title">Pending Tickets</h5>
          <p class="card-text display-4"><?= $total_pending_tickets; ?></p>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Management Cards -->
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title">Manage Products</h5>
          <p class="card-text">Add, edit, or remove products.</p>
          <a href="manage_products.php" class="btn btn-primary">Products</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title">Manage Rentals</h5>
          <p class="card-text">Review rental bookings.</p>
          <a href="manage_rentals.php" class="btn btn-primary">Rentals</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title">Support Tickets</h5>
          <p class="card-text">Handle support requests.</p>
          <a href="manage_support.php" class="btn btn-primary">Support</a>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Product Distribution Chart -->
  <h3 class="mt-5">Product Distribution by Category</h3>
  <div class="row">
    <div class="col-md-12">
      <canvas id="bossChart" width="400" height="150"></canvas>
    </div>
  </div>
  
  <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('bossChart').getContext('2d');
  const bossChart
