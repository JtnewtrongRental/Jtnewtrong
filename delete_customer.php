<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
// Only allow admin or higher roles to delete customers
if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] == 'user') {
    header("Location: manage_customers.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_customers.php");
    exit();
}

include 'includes/db.php';

$customer_id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM customers WHERE id = ?");
$stmt->bind_param("i", $customer_id);
if ($stmt->execute()) {
    $_SESSION['msg'] = '<div class="alert alert-success">Customer deleted successfully.</div>';
} else {
    $_SESSION['msg'] = '<div class="alert alert-danger">Error deleting customer: ' . $stmt->error . '</div>';
}
$stmt->close();
$conn->close();

header("Location: manage_customers.php");
exit();
?>