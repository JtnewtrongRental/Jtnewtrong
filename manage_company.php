<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'includes/db.php';
$updateSuccess = "";
$updateError = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $company_name          = $_POST['company_name'];
    $established           = $_POST['established'];
    $founder               = $_POST['founder'];
    $address               = $_POST['address'];
    $phone                 = $_POST['phone'];
    $email                 = $_POST['email'];
    $website               = $_POST['website'];
    $overview              = $_POST['overview'];
    $mission               = $_POST['mission'];
    $vision                = $_POST['vision'];
    $target_market         = $_POST['target_market'];
    $company_values        = $_POST['company_values'];
    $objectives            = $_POST['objectives'];
    $social_responsibility = $_POST['social_responsibility'];
    $future_goals          = $_POST['future_goals'];
    $company_logo = $_POST['current_company_logo'];
    $activity_picture = $_POST['current_activity_picture'];
    if (isset($_FILES['company_logo']) && $_FILES['company_logo']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        $target_file = $target_dir . uniqid("logo_", true) . "_" . basename($_FILES['company_logo']['name']);
        if(move_uploaded_file($_FILES['company_logo']['tmp_name'], $target_file)){
            $company_logo = $target_file;
        }
    }
    if (isset($_FILES['activity_picture']) && $_FILES['activity_picture']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        $target_file = $target_dir . uniqid("activity_", true) . "_" . basename($_FILES['activity_picture']['name']);
        if(move_uploaded_file($_FILES['activity_picture']['tmp_name'], $target_file)){
            $activity_picture = $target_file;
        }
    }
    $stmt = $conn->prepare("UPDATE company_info SET company_name = ?, established = ?, founder = ?, address = ?, phone = ?, email = ?, website = ?, overview = ?, mission = ?, vision = ?, target_market = ?, company_values = ?, objectives = ?, social_responsibility = ?, future_goals = ?, company_logo = ?, activity_picture = ? WHERE id = 1");
    $stmt->bind_param("sssssssssssssssss", 
        $company_name, $established, $founder, $address, $phone, $email, $website, $overview, $mission, $vision, $target_market, $company_values, $objectives, $social_responsibility, $future_goals, $company_logo, $activity_picture
    );
    if ($stmt->execute()) {
        $updateSuccess = "Company information updated successfully.";
    } else {
        $updateError = "Error updating company information: " . $stmt->error;
    }
    $stmt->close();
}
$sql = "SELECT * FROM company_info WHERE id = 1";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    $default_company_name = "JTNEWTRONG, UNIPESSOAL, LDA";
    $default_established = "2024";
    $default_founder = "Jerry Tou";
    $default_address = "Moris Foun, Bairo Pite, Dom Aleixo, Dili, Timor-Leste";
    $default_phone = "+670 7843 9095";
    $default_email = "jtnewtrong.rental@gmail.com";
    $default_website = "https://jtnewtronguniplda.blogspot.com/";
    $default_overview = "JTNEWTRONG, UNIPESSOAL, LDA is a premier enterprise dedicated to providing a wide array of services including printing, rentals, and IT support.";
    $default_mission = "Our mission is to empower our clients with exceptional printing and technology solutions.";
    $default_vision = "We aspire to be the leading provider in our field with innovation and customer focus.";
    $default_target_market = "Businesses, educational institutions, non-profits, government agencies, and individuals in Dili and beyond.";
    $default_company_values = "Excellence, Integrity, Innovation, Collaboration, Customer Focus";
    $default_objectives = "Expand services, maintain quality, and continuously innovate.";
    $default_social_responsibility = "Commitment to sustainable practices and community support.";
    $default_future_goals = "Growth, expansion, and diversification of services.";
    $default_company_logo = "";
    $default_activity_picture = "";
    $stmt = $conn->prepare("INSERT INTO company_info (id, company_name, established, founder, address, phone, email, website, overview, mission, vision, target_market, company_values, objectives, social_responsibility, future_goals, company_logo, activity_picture) VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssssssss", 
        $default_company_name, $default_established, $default_founder, $default_address, $default_phone, $default_email, $default_website, $default_overview, $default_mission, $default_vision, $default_target_market, $default_company_values, $default_objectives, $default_social_responsibility, $default_future_goals, $default_company_logo, $default_activity_picture
    );
    $stmt->execute();
    $stmt->close();
}
$sql = "SELECT * FROM company_info WHERE id = 1";
$result = $conn->query($sql);
$company = $result->fetch_assoc();
include 'includes/header.php';
?>
<div class="container">
  <h2>Manage Company Information</h2>
  <?php if ($updateError): ?>
    <div class="alert alert-danger"><?= $updateError; ?></div>
  <?php elseif ($updateSuccess): ?>
    <div class="alert alert-success"><?= $updateSuccess; ?></div>
  <?php endif; ?>
  <form method="post" action="manage_company.php" enctype="multipart/form-data">
    <!-- Company fields -->
    <div class="form-group">
      <label>Company Name</label>
      <input type="text" name="company_name" class="form-control" value="<?= htmlspecialchars($company['company_name']); ?>" required>
    </div>
    <div class="form-group">
      <label>Established</label>
      <input type="text" name="established" class="form-control" value="<?= htmlspecialchars($company['established']); ?>" required>
    </div>
    <div class="form-group">
      <label>Founder</label>
      <input type="text" name="founder" class="form-control" value="<?= htmlspecialchars($company['founder']); ?>" required>
    </div>
    <div class="form-group">
      <label>Address</label>
      <textarea name="address" class="form-control" required><?= htmlspecialchars($company['address']); ?></textarea>
    </div>
    <div class="form-group">
      <label>Phone</label>
      <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($company['phone']); ?>" required>
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($company['email']); ?>" required>
    </div>
    <div class="form-group">
      <label>Website</label>
      <input type="text" name="website" class="form-control" value="<?= htmlspecialchars($company['website']); ?>" required>
    </div>
    <div class="form-group">
      <label>Overview</label>
      <textarea name="overview" class="form-control" rows="3" required><?= htmlspecialchars($company['overview']); ?></textarea>
    </div>
    <div class="form-group">
      <label>Mission</label>
      <textarea name="mission" class="form-control" rows="3" required><?= htmlspecialchars($company['mission']); ?></textarea>
    </div>
    <div class="form-group">
      <label>Vision</label>
      <textarea name="vision" class="form-control" rows="3" required><?= htmlspecialchars($company['vision']); ?></textarea>
    </div>
    <div class="form-group">
      <label>Target Market</label>
      <textarea name="target_market" class="form-control" rows="3" required><?= htmlspecialchars($company['target_market']); ?></textarea>
    </div>
    <div class="form-group">
      <label>Company Values</label>
      <textarea name="company_values" class="form-control" rows="3" required><?= htmlspecialchars($company['company_values']); ?></textarea>
    </div>
    <div class="form-group">
      <label>Objectives</label>
      <textarea name="objectives" class="form-control" rows="3" required><?= htmlspecialchars($company['objectives']); ?></textarea>
    </div>
    <div class="form-group">
      <label>Social Responsibility</label>
      <textarea name="social_responsibility" class="form-control" rows="3" required><?= htmlspecialchars($company['social_responsibility']); ?></textarea>
    </div>
    <div class="form-group">
      <label>Future Goals</label>
      <textarea name="future_goals" class="form-control" rows="3" required><?= htmlspecialchars($company['future_goals']); ?></textarea>
    </div>
    <!-- Image Uploads -->
    <div class="form-group">
      <label>Company Logo</label>
      <?php if (!empty($company['company_logo'])): ?>
        <div class="mb-2">
          <img src="<?= htmlspecialchars($company['company_logo']); ?>" alt="Company Logo" style="max-width:150px;">
        </div>
      <?php endif; ?>
      <input type="file" name="company_logo" class="form-control-file">
      <input type="hidden" name="current_company_logo" value="<?= htmlspecialchars($company['company_logo']); ?>">
    </div>
    <div class="form-group">
      <label>Activity Picture</label>
      <?php if (!empty($company['activity_picture'])): ?>
        <div class="mb-2">
          <img src="<?= htmlspecialchars($company['activity_picture']); ?>" alt="Activity Picture" style="max-width:150px;">
        </div>
      <?php endif; ?>
      <input type="file" name="activity_picture" class="form-control-file">
      <input type="hidden" name="current_activity_picture" value="<?= htmlspecialchars($company['activity_picture']); ?>">
    </div>
    <button type="submit" class="btn btn-primary">Update Company Information</button>
  </form>
  <a href="admin_page.php" class="btn btn-secondary mt-3">Back to Admin Panel</a>
</div>
<?php include 'includes/footer.php'; ?>
