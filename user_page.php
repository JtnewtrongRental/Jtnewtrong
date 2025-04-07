<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include 'includes/db.php';
include 'includes/header.php';

// Fetch settings dynamically
$settings = getSettings($conn);
$background_color = $settings['user_background_color'];
?>
<style>
  body {
    background-color: <?= htmlspecialchars($background_color); ?>;
  }
  .card-3d {
    perspective: 1000px;
    transition: transform 0.5s;
  }
  .card-3d:hover {
    transform: scale(1.05) rotateY(5deg);
    box-shadow: 0 10px 20px rgba(0,0,0,0.3);
  }
</style>

<div class="container mt-5">
  <h2 class="mb-4 text-center">User Dashboard</h2>
  <p class="text-center">Welcome, <?= htmlspecialchars($_SESSION['user']['username']); ?>.</p>
  
  <!-- Products Listing in Card Layout -->
  <h3>Our Products</h3>
  <?php if ($resultProducts->num_rows > 0): ?>
    <div class="row">
      <?php while($product = $resultProducts->fetch_assoc()): ?>
        <div class="col-md-4 mb-4">
          <div class="card card-3d h-100">
            <?php if (!empty($product['image'])): ?>
              <img src="<?= htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']); ?>" style="height:200px; object-fit:cover;">
            <?php else: ?>
              <img src="uploads/default.jpg" class="card-img-top" alt="No Image" style="height:200px; object-fit:cover;">
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($product['name']); ?></h5>
              <p class="card-text mb-2">$<?= number_format($product['price'], 2); ?></p>
              <a href="#" class="btn btn-primary mt-auto">View Details</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p>No products available at the moment.</p>
  <?php endif; ?>

  <!-- Company Details as a Card with 3D Animation -->
  <h3 class="mt-5">About Our Company</h3>
  <?php if ($company): ?>
    <div class="card card-3d mb-5">
      <div class="row no-gutters">
        <?php if (!empty($company['company_logo'])): ?>
          <div class="col-md-4">
            <img src="<?= htmlspecialchars($company['company_logo']); ?>" class="card-img" alt="<?= htmlspecialchars($company['company_name']); ?>" style="height:100%; object-fit:cover;">
          </div>
        <?php endif; ?>
        <div class="col-md-<?= !empty($company['company_logo']) ? '8' : '12' ?>">
          <div class="card-body">
            <h4 class="card-title"><?= htmlspecialchars($company['company_name']); ?></h4>
            <p class="card-text"><strong>Established:</strong> <?= htmlspecialchars($company['established']); ?></p>
            <p class="card-text"><strong>Founder:</strong> <?= htmlspecialchars($company['founder']); ?></p>
            <p class="card-text"><strong>Address:</strong> <?= htmlspecialchars($company['address']); ?></p>
            <p class="card-text"><strong>Phone:</strong> <?= htmlspecialchars($company['phone']); ?></p>
            <p class="card-text"><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($company['email']); ?>"><?= htmlspecialchars($company['email']); ?></a></p>
            <p class="card-text"><strong>Website:</strong> <a href="<?= htmlspecialchars($company['website']); ?>" target="_blank"><?= htmlspecialchars($company['website']); ?></a></p>
            <p class="card-text"><strong>Overview:</strong> <?= htmlspecialchars($company['overview']); ?></p>
          </div>
        </div>
      </div>
    </div>
  <?php else: ?>
    <p>Company details are not available at this time.</p>
  <?php endif; ?>

  <div class="text-center">
    <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
