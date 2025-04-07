<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['contactName']);
    $email = trim($_POST['contactEmail']);
    $message = trim($_POST['contactMessage']);
    
    $to = "jtnewtrong.rental@gmail.com"; // Your company email
    $subject = "New Contact Message from $name";
    $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
    // Send email (this may require additional configuration)
    mail($to, $subject, $body);
    $success = "Thank you for contacting us. We'll get back to you soon!";
}
include 'includes/header.php';
?>
<div class="container">
  <h2>Contact Us</h2>
  <?php if (isset($success)): ?>
    <div class="alert alert-success"><?= $success; ?></div>
  <?php else: ?>
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
  <?php endif; ?>
  <a href="index.php" class="btn btn-secondary mt-3">Back to Home</a>
  
  <!-- Social Media Section -->
  <div class="mt-4">
    <h3>Follow Us on Social Media</h3>
    <ul class="list-inline">
      <li class="list-inline-item">
        <a href="https://www.facebook.com/jtnewtrongrental" target="_blank">Facebook</a>
      </li>
      <li class="list-inline-item">
        <a href="https://www.tiktok.com/tag/jtnewtrongrental" target="_blank">TikTok</a>
      </li>
      <li class="list-inline-item">
        <a href="https://www.youtube.com/@JtnewtrongRental" target="_blank">YouTube</a>
      </li>
    </ul>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
