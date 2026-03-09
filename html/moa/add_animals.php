<?php
session_start();
require_once '../../config/db.php';
require_once '../../helper/phpqrcode/qrlib.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $owner_id = $_POST['owner_id'] ?? '';
  $owner_name = $_POST['owner_name'] ?? '';
  $species = $_POST['species'] ?? '';
  $breed = $_POST['breed'] ?? '';
  $sex = $_POST['sex'] ?? '';
  $dob = $_POST['dob'] ?? '';

  if (empty($owner_id) || empty($species) || empty($breed) || empty($sex) || empty($dob)) {
    $error = "Please fill in all required fields.";
  } else {
    try {
      // Insert into livestock table
      $stmt = $pdo->prepare("
        INSERT INTO livestock (owner_id, owner_name, species, breed, sex, dob)
        VALUES (?, ?, ?, ?, ?, ?)
      ");
      $stmt->execute([
        $owner_id,
        $owner_name,
        $species,
        $breed,
        $sex,
        $dob
      ]);
      
      $livestock_id = $pdo->lastInsertId();
      
      // Generate QR Code
      $qrData = "Livestock ID: $livestock_id\nOwner: $owner_name\nSpecies: $species\nBreed: $breed\nSex: $sex\nDate of Birth: $dob";
      $qrDir = __DIR__ . "/../../uploads/qrcodes/";
      
      if (!file_exists($qrDir)) {
        mkdir($qrDir, 0777, true);
      }

      $fileName = "livestock_" . $livestock_id . ".png";
      $filePath = $qrDir . $fileName;

      QRcode::png($qrData, $filePath, QR_ECLEVEL_L, 5);

      $relativePath = "uploads/qrcodes/" . $fileName;
      $update = $pdo->prepare("UPDATE livestock SET qr_code = ? WHERE id = ?");
      $update->execute([$relativePath, $livestock_id]);
      
      $success = "Livestock added successfully!";
      header("Location: livestock-profiling-list.php");
      exit();
    } catch (PDOException $e) {
      $error = "Error: " . $e->getMessage();
    }
  }
}

// Get all owners for dropdown
$ownersStmt = $pdo->query("SELECT id, CONCAT(firstName, ' ', COALESCE(middleName, ''), ' ', surname) as fullname FROM owner ORDER BY firstName");
$owners = $ownersStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Livestock - Alagang Baras</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../../assets/styles/components/sidebar.css" />
  <link rel="stylesheet" href="../../assets/styles/layouts/moa/livestock-profiling.css" />
</head>
<body>
  <div class="overlay" id="overlay"></div>

  <button class="hamburger-btn" id="hamburgerBtn">
    <i class="fas fa-bars"></i>
  </button>

  <div class="sidebar" id="sidebar">
    <div class="logo-container">
      <div class="logo">
        <img src="../../assets/images/64c57e19-d2a4-42cf-9a5b-4b35dd14f9f7.png" alt="">
      </div>
      <div class="logo secondary">
        <img src="../../assets/images/9302dfc3-307b-42b1-a9d2-8c31dc0cb9c6.png" alt="">
      </div>
      <div class="logo tertiary">
        <img src="../../assets/images/c567f4c0-7403-4ca0-8556-5bac257c9190.png" alt="">
      </div>
    </div>
    <div class="logo-text">
      <h4>Alagang Baras</h4>
      <p>MAO PAGE</p>
    </div>

    <nav class="nav-menu">
      <div class="nav-item">
        <a href="./dashboard.php" class="nav-link">
          <i class="fas fa-tachometer-alt"></i>
          Dashboard
        </a>
      </div>

      <div class="nav-item has-submenu">
        <a href="#" class="nav-link submenu-toggle">
          <i class="fas fa-heartbeat"></i>
          User Management
          <i class="fas fa-chevron-down submenu-icon"></i>
        </a>
        <div class="submenu">
          <a href="./coordinator_management.php" class="submenu-link">Coordinator</a>
          <a href="./vet_management.php" class="submenu-link">Veterinarian</a>
        </div>
      </div>
      
      <div class="nav-item">
        <a href="./livestock-profiling-list.php" class="nav-link active">
          <i class="fas fa-sign-out-alt"></i>
          Livestock Profiling
        </a>
      </div>
      <div class="nav-item">
        <a href="./reports.php" class="nav-link">
          <i class="fas fa-sign-out-alt"></i>
          Reports
        </a>
      </div>
      <div class="nav-item">
        <a href="../index.php" class="nav-link">
          <i class="fas fa-sign-out-alt"></i>
          Log out
        </a>
      </div>
    </nav>
  </div>

  <!-- Main Container -->
  <div class="main-container">
    <!-- Content Area -->
    <main class="content">
      <h2 class="page-title fade-in">
        <i class="fas fa-plus-circle me-3"></i>Add New Livestock
      </h2>

      <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?php echo htmlspecialchars($success); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?php echo htmlspecialchars($error); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <form method="POST" class="livestock-form fade-in">
        <div class="row">
          <div class="col-md-6 form-row">
            <label for="owner_id" class="form-label">Owner:</label>
            <select class="form-control" id="owner_id" name="owner_id" required>
              <option value="" selected disabled>-- Select Owner --</option>
              <?php foreach ($owners as $owner): ?>
                <option value="<?php echo htmlspecialchars($owner['id']); ?>">
                  <?php echo htmlspecialchars($owner['fullname']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          
          <input type="hidden" id="owner_name" name="owner_name" />

          <div class="col-md-6 form-row">
            <label for="species" class="form-label">Species:</label>
            <select class="form-control" id="species" name="species" required>
              <option value="" selected disabled>-- Select Species --</option>
              <option value="Cattle">Cattle</option>
              <option value="Swine">Swine</option>
              <option value="Goat">Goat</option>
              <option value="Sheep">Sheep</option>
              <option value="Poultry">Poultry</option>
              <option value="Carabao">Carabao</option>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 form-row">
            <label for="breed" class="form-label">Breed:</label>
            <input type="text" class="form-control" id="breed" name="breed" required />
          </div>

          <div class="col-md-3 form-row">
            <label for="sex" class="form-label">Sex:</label>
            <select class="form-control" id="sex" name="sex" required>
              <option value="" selected disabled>-- Select --</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </div>

          <div class="col-md-3 form-row">
            <label for="dob" class="form-label">Date of Birth:</label>
            <input type="date" class="form-control" id="dob" name="dob" required />
          </div>
        </div>

        <div class="form-actions mt-4">
          <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-save me-2"></i>Save Livestock
          </button>
          <a href="./livestock-profiling-list.php" class="btn btn-secondary btn-lg">
            <i class="fas fa-times me-2"></i>Cancel
          </a>
        </div>
      </form>
    </main>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/scripts/sidebar.js"></script>
  <script>
    // Update owner_name hidden field when owner is selected
    document.getElementById('owner_id').addEventListener('change', function() {
      const selectedOption = this.options[this.selectedIndex];
      document.getElementById('owner_name').value = selectedOption.text;
    });
  </script>
</body>
</html>