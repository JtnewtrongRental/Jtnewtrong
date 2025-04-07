<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: manage_products.php");
    exit();
}

$productId = intval($_GET['id']);

// Retrieve product to get the image path
$stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 1) {
    $product = $result->fetch_assoc();
    // If image file exists, delete it from server
    if (!empty($product['image']) && file_exists($product['image'])) {
        unlink($product['image']);
    }
}
$stmt->close();

// Now delete the product record
$stmt2 = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt2->bind_param("i", $productId);
$stmt2->execute();
$stmt2->close();

header("Location: manage_products.php");
exit();
?>
