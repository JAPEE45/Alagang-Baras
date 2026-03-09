<?php
session_start();
require_once '../../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT v.fullname FROM users u JOIN veterinarians v ON u.user_id = v.user_id WHERE u.user_id = ?");
$stmt->execute([$userId]);
$vetName = $stmt->fetch();

// If vet not found, redirect to login
if (!$vetName) {
    session_destroy();
    header('Location: ../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Alagang Baras - Health Monitoring</title>
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"
    rel="stylesheet" />
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    rel="stylesheet" />

  <link rel="stylesheet" href="../../assets/styles/components/sidebar.css" />
  <link
    rel="stylesheet"
    href="../../assets/styles/layouts/veterinarian/health-monitoring.css" />
</head>

<body>
  <!-- Overlay for mobile -->
  <div class="overlay" id="overlay"></div>

  <!-- Hamburger Menu Button -->
  <button class="hamburger-btn" id="hamburgerBtn">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Sidebar -->
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
      <p>VETERINARIAN PAGE</p>
    </div>

    <div class="user-profile">
      <div class="avatar">
        <img
          src="../../assets/images/blank-profile-picture-973460_1280.png"
          alt="" />
      </div>
      <h6>
        <?php echo ($vetName && isset($vetName['fullname'])) ? htmlspecialchars($vetName['fullname']) : 'Unknown'; ?>
      </h6>
      <small>Veterinarian</small>
    </div>

    <nav class="nav-menu">
      <div class="nav-item">
        <a href="./dashboard.php" class="nav-link ">
          <i class="fas fa-tachometer-alt"></i>
          Dashboard
        </a>
      </div>

      <div class="nav-item has-submenu">
        <a href="#" class="nav-link submenu-toggle active">
          <i class="fas fa-heartbeat"></i>
          Health Monitoring
          <i class="fas fa-chevron-down submenu-icon"></i>
        </a>
        <div class="submenu">
          <a href="./new-health-record.php" class="submenu-link active">New Health Record</a>
          <a href="./livestock-health-monitoring.php" class="submenu-link ">Livestock Health Monitoring</a>
        </div>
      </div>

      <div class="nav-item">
        <a href="../index.php" class="nav-link">
          <i class="fas fa-sign-out-alt"></i>
          Log out
        </a>
      </div>
    </nav>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Main Content Area -->
    <div class="col-md-9">
      <div class="content-card">
        <h1 class="page-title">New Health Record</h1>

        <form id="healthRecordForm">
          <!-- Date Field -->
          <div class="form-row">
            <label for="checkupDate" class="form-label">Date of Check-up:</label>
            <input
              type="date"
              class="form-control"
              id="checkupDate"
              name="checkupDate"
              required />
          </div>

          <div class="form-row row">
            <div class="col-md-6">
              <label for="search-owner" class="form-label">Owner:</label>
              <input type="search" class="form-control" id="search-owner" />
              <ul class="list-group position-absolute w-100 mt-1" id="owner-suggestions" style="z-index: 1000; display: none;"></ul>
            </div>
            <div class="col-md-6">
              <label for="search-owner" class="form-label">livestock:</label>
              <select class="form-control" id="liveStockSelect">
                <option class="option-control" selected disabled>--Select Livestock--</option>
              </select>
            </div>
          </div>

          <div class="form-row row">
            <div class="col-md-6">
              <label for="search-species" class="form-label">Species</label>
              <input class="form-control" id="search-species" disabled />
            </div>
            <div class="col-md-6">
              <label for="search-breed" class="form-label">Breed</label>
              <input class="form-control" id="search-breed" disabled />

            </div>
          </div>

          <!-- Diagnosis Field -->
          <div class="form-row row">
            <div class="col-md-6">
              <label for="diagnosis" class="form-label">Diagnosis:</label>
              <textarea
                class="form-control"
                id="diagnosis"
                name="diagnosis"
                rows="4"
                placeholder="Enter diagnosis details..."></textarea>
            </div>
            <div class="col-md-6">
              <label for="treatment" class="form-label">Treatment:</label>
              <textarea
                class="form-control"
                id="treatment"
                name="treatment"
                rows="4"
                placeholder="Enter treatment details..."></textarea>
            </div>
          </div>

          <!-- Vaccine Field -->
          <div class="form-row row">
            <div class="col-md-6">
              <label for="vaccine" class="form-label">Vaccine Given:</label>
              <select class="form-select" id="vaccine" name="vaccine">
                <option value="" disabled selected hidden>-- Select vaccine --</option>
                <option value="rabies">Rabies Vaccine</option>
                <option value="distemper">Distemper Vaccine</option>
                <option value="parvovirus">Parvovirus Vaccine</option>
                <option value="hepatitis">Hepatitis Vaccine</option>
                <option value="leptospirosis">Leptospirosis Vaccine</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="healthStatus" class="form-label">Health Status:</label>
              <select class="form-select" id="healthStatus" name="healthStatus">
                <option value="" disabled selected hidden>-- Select status --</option>
                <option value="Healthy">Healthy</option>
                <option value="Healthy">Sick</option>
                <option value="Recovering">Recovering</option>
                <option value="Under Observation">Under Observation</option>
              </select>
            </div>
          </div>

          <!-- Remarks Field -->
          <!-- <div class="form-row">
              <label for="remarks" class="form-label">Remarks:</label>
              <textarea
                class="form-control"
                id="remarks"
                name="remarks"
                rows="4"
                placeholder="Additional notes or observations..."
                height="100px"
              ></textarea>
            </div> -->

          <!-- Action Buttons -->
          <div class="btn-group-custom">
            <button type="submit" class="btn btn-save">
              <i class="fas fa-save me-2"></i>Save Record
            </button>
            <button type="reset" class="btn btn-reset">
              <i class="fas fa-redo me-2"></i>Reset
            </button>
            <a href="./livestock-health-monitoring.php" class="btn btn-cancel">
              <i class="fas fa-times me-2"></i>Cancel
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <input type="hidden" id="owner-id" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

  <script src="../../assets/scripts/sidebar.js"></script>
  <script src="../../assets/scripts/search-owner.js"></script>
  <script src="../../assets/scripts/vet-health-monitoring.js"></script>
</body>

</html>