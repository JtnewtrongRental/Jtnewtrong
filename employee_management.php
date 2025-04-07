<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include 'includes/db.php';
include 'includes/header.php';

$empMsg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_employee'])) {
    $name = trim($_POST['name']);
    $role = trim($_POST['role']);
    $schedule = trim($_POST['schedule']);
    $payroll = floatval($_POST['payroll']);
    $performance = trim($_POST['performance']);
    
    $stmt = $conn->prepare("INSERT INTO employees (name, role, schedule, payroll, performance) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssds", $name, $role, $schedule, $payroll, $performance);
    if($stmt->execute()){
        $empMsg = '<div class="alert alert-info">Employee added successfully.</div>';
    } else {
        $empMsg = '<div class="alert alert-danger">Error adding employee: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}
$sql = "SELECT * FROM employees ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<div class="container mt-4">
  <h2>Employee Management</h2>
  <?= $empMsg; ?>
  <div class="mb-4">
    <h4>Add New Employee</h4>
    <form method="post" action="employee_management.php">
      <input type="hidden" name="add_employee" value="1">
      <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Role/Position</label>
        <select name="role" class="form-control" required>
          <option value="Manager">Manager</option>
          <option value="Staff">Staff</option>
          <option value="Technician">Technician</option>
          <option value="Other">Other</option>
        </select>
      </div>
      <div class="form-group">
        <label>Schedule</label>
        <textarea name="schedule" class="form-control" placeholder="E.g., Mon-Fri, 9am-5pm"></textarea>
      </div>
      <div class="form-group">
        <label>Payroll (Salary)</label>
        <input type="number" step="0.01" name="payroll" class="form-control">
      </div>
      <div class="form-group">
        <label>Performance</label>
        <select name="performance" class="form-control" required>
          <option value="">Select Performance</option>
          <option value="Excellent">Excellent</option>
          <option value="Good">Good</option>
          <option value="Average">Average</option>
          <option value="Below Average">Below Average</option>
          <option value="Poor">Poor</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Add Employee</button>
    </form>
  </div>
  <h4>Employee List</h4>
  <table class="table table-bordered table-hover">
    <thead class="thead-dark">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Role</th>
        <th>Schedule</th>
        <th>Payroll</th>
        <th>Performance</th>
        <th>Joined</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php while ($emp = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $emp['id']; ?></td>
            <td><?= htmlspecialchars($emp['name']); ?></td>
            <td><?= htmlspecialchars($emp['role']); ?></td>
            <td><?= htmlspecialchars($emp['schedule']); ?></td>
            <td>$<?= number_format($emp['payroll'], 2); ?></td>
            <td><?= htmlspecialchars($emp['performance']); ?></td>
            <td><?= $emp['created_at']; ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="7">No employees found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<?php include 'includes/footer.php'; ?>
