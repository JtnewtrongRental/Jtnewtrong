<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// Get company details
$sqlCompany = "SELECT * FROM company_info WHERE id = 1";
$resultCompany = $conn->query($sqlCompany);
$company = $resultCompany->fetch_assoc();

// Get distinct product categories
$categoryQuery = "SELECT DISTINCT category FROM products ORDER BY category ASC";
$resultCategories = $conn->query($categoryQuery);
$categories = [];
while ($row = $resultCategories->fetch_assoc()) {
    $categories[] = $row['category'];
}
?>

<!-- Custom styling for a beautiful, attractive UI -->
<style>
  /* Global Fonts and Colors */
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f7f7f7;
  }
  h1, h2, h3, h4, h5, h6, p, a {
    color: #333;
  }
  /* Hero Section with gradient overlay */
  .jumbotron {
    position: relative;
    background: url('https://via.placeholder.com/1920x600?text=Welcome+to+JTNEWTRONG') no-repeat center center;
    background-size: cover;
    height: 600px;
    margin-bottom: 0;
  }
  .jumbotron::before {
    content: "";
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    background: linear-gradient(45deg, rgba(0,0,0,0.6), rgba(0,0,0,0.2));
  }
  .jumbotron .container {
    position: relative;
    z-index: 2;
  }
  .jumbotron h1 {
    font-size: 3.5rem;
    font-weight: 700;
    text-shadow: 2px 2px 8px rgba(0,0,0,0.6);
  }
  .jumbotron p.lead {
    font-size: 1.5rem;
    text-shadow: 1px 1px 6px rgba(0,0,0,0.5);
  }
  
  /* Section styling */
  .section {
    padding: 60px 0;
  }
  
  /* 3D card effect for product cards with a smooth transition */
  .card-3d {
    perspective: 1000px;
    transition: transform 0.5s, box-shadow 0.5s;
    border: none;
  }
  .card-3d:hover {
    transform: translateY(-10px) scale(1.03);
    box-shadow: 0 12px 25px rgba(0,0,0,0.3);
  }
  
  /* Card image rounding and object-fit */
  .card-img-top {
    border-top-left-radius: .5rem;
    border-top-right-radius: .5rem;
    object-fit: cover;
  }
  
  /* Customers and products card styling */
  .card {
    border-radius: .5rem;
  }
  
  /* Buttons styling */
  .btn-primary {
    background-color: #5cb85c;
    border-color: #4cae4c;
  }
  .btn-primary:hover {
    background-color: #4cae4c;
    border-color: #449d44;
  }
</style>

<!-- Hero Section -->
<div class="jumbotron jumbotron-fluid">
  <div class="container text-center text-white">
    <h1 class="display-3"><?= htmlspecialchars($company['company_name']); ?></h1>
    <p class="lead"><?= htmlspecialchars($company['overview']); ?></p>
    <a href="dashboard.php" class="btn btn-primary btn-lg">Get Started</a>
  </div>
</div>

<!-- About Us Section -->
<div class="container section">
  <div class="row align-items-center">
    <div class="col-md-6">
      <?php if (!empty($company['company_logo'])): ?>
        <img src="<?= htmlspecialchars($company['company_logo']); ?>" alt="<?= htmlspecialchars($company['company_name']); ?>" class="img-fluid company-logo rounded shadow mb-3">
      <?php endif; ?>
      <h2>About Us</h2>
      <p><strong>Established:</strong> <?= htmlspecialchars($company['established']); ?></p>
      <p><strong>Founder:</strong> <?= htmlspecialchars($company['founder']); ?></p>
      <p><?= htmlspecialchars($company['mission']); ?></p>
      <p><?= htmlspecialchars($company['vision']); ?></p>
    </div>
    <div class="col-md-6">
      <img src="https://via.placeholder.com/600x400?text=Our+Story" class="img-fluid rounded shadow" alt="About Us">
    </div>
  </div>
</div>

<!-- Our Activities Section -->
<?php if (!empty($company['activity_picture'])): ?>
<div class="container section">
  <h2 class="text-center mb-5">Our Activities</h2>
  <div class="row justify-content-center">
    <div class="col-md-8">
      <img src="<?= htmlspecialchars($company['activity_picture']); ?>" class="img-fluid rounded shadow" alt="Our Activities">
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Customers Section -->
<div class="container section">
  <h2 class="text-center mb-5">Our Customers</h2>
  <?php
  // Query to retrieve customer photos and details
  $customerQuery = "SELECT name, email, phone, address, photo FROM customers ORDER BY created_at DESC";
  $resultCustomers = $conn->query($customerQuery);
  ?>
  <?php if ($resultCustomers->num_rows > 0): ?>
    <div class="row">
      <?php while ($customer = $resultCustomers->fetch_assoc()): ?>
        <div class="col-md-4 mb-4">
          <div class="card h-100 shadow-sm">
            <?php if (!empty($customer['photo'])): ?>
              <img src="<?= htmlspecialchars($customer['photo']); ?>" class="card-img-top" alt="<?= htmlspecialchars($customer['name']); ?>" style="height:200px;">
            <?php else: ?>
              <img src="https://via.placeholder.com/400x300?text=No+Image" class="card-img-top" alt="No Image" style="height:200px;">
            <?php endif; ?>
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($customer['name']); ?></h5>
              <p class="card-text"><strong>Email:</strong> <?= htmlspecialchars($customer['email']); ?></p>
              <p class="card-text"><strong>Phone:</strong> <?= htmlspecialchars($customer['phone']); ?></p>
              <p class="card-text"><strong>Address:</strong> <?= htmlspecialchars($customer['address']); ?></p>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p class="text-center">No customers found.</p>
  <?php endif; ?>
</div>

<!-- Products by Category Section -->
<div class="container section">
  <h2 class="text-center mb-5">Our Products</h2>
  <?php if (!empty($categories)): ?>
    <?php foreach ($categories as $category): ?>
      <?php
      $stmt = $conn->prepare("SELECT id, name, price, stock, image FROM products WHERE category = ? ORDER BY name ASC");
      $stmt->bind_param("s", $category);
      $stmt->execute();
      $resultProducts = $stmt->get_result();
      ?>
      <div class="mb-5">
        <h3 class="mb-4"><?= htmlspecialchars($category); ?></h3>
        <?php if ($resultProducts->num_rows > 0): ?>
          <div class="row">
            <?php while ($product = $resultProducts->fetch_assoc()): ?>
              <div class="col-md-4 mb-4">
                <div class="card card-3d h-100 shadow-sm">
                  <?php
                  $img = !empty($product['image']) ? $product['image'] : "https://via.placeholder.com/400x300?text=No+Image";
                  ?>
                  <img src="<?= htmlspecialchars($img); ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']); ?>" style="height:200px;">
                  <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($product['name']); ?></h5>
                    <p class="card-text">Price: $<?= number_format($product['price'], 2); ?></p>
                    <p class="card-text"><small class="text-muted">Stock: <?= $product['stock']; ?></small></p>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        <?php else: ?>
          <p>No products found in this category.</p>
        <?php endif; ?>
        <?php $stmt->close(); ?>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p class="text-center">No product categories found.</p>
  <?php endif; ?>
</div>

<!-- Contact Section -->
<div class="container section">
  <div class="row">
    <div class="col-md-6 mb-4">
      <h2>Contact Us</h2>
      <p><strong>Address:</strong> <?= htmlspecialchars($company['address']); ?></p>
      <p><strong>Phone:</strong> <?= htmlspecialchars($company['phone']); ?></p>
      <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($company['email']); ?>"><?= htmlspecialchars($company['email']); ?></a></p>
      <p><strong>Website:</strong> <a href="<?= htmlspecialchars($company['website']); ?>" target="_blank"><?= htmlspecialchars($company['website']); ?></a></p>
    </div>
    <div class="col-md-6">
      <h2>Get in Touch</h2>
      <form method="post" action="contact.php">
        <div class="form-group">
          <label for="contactName">Your Name</label>
          <input type="text" class="form-control" id="contactName" name="contactName" required>
        </div>
        <div class="form-group">
          <label for="contactEmail">Your Email</label>
          <input type="email" class="form-control" id="contactEmail" name="contactEmail" required>
        </div>
        <div class="form-group">
          <label for="contactMessage">Message</label>
          <textarea class="form-control" id="contactMessage" name="contactMessage" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send Message</button>
      </form>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
