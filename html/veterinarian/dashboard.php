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

// Get total livestock count
$livestockStmt = $pdo->query("SELECT COUNT(*) as count FROM livestock");
$totalLivestock = $livestockStmt->fetch()['count'];

// Get total health records
$healthStmt = $pdo->query("SELECT COUNT(*) as count FROM healthmonitoring");
$totalHealthRecords = $healthStmt->fetch()['count'];

// Get animals needing attention
$attentionStmt = $pdo->query("SELECT COUNT(*) as count FROM healthmonitoring WHERE livestock_status != 'Healthy'");
$needsAttention = $attentionStmt->fetch()['count'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Alagang Baras - Livestock Management System</title>
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"
    rel="stylesheet" />
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    rel="stylesheet" />

  <link rel="stylesheet" href="../../assets/styles/components/sidebar.css" />
  <link
    rel="stylesheet"
    href="../../assets/styles/layouts/veterinarian/dashboard.css" />
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
        <a href="#" class="nav-link active">
          <i class="fas fa-tachometer-alt"></i>
          Dashboard
        </a>
      </div>

      <div class="nav-item has-submenu">
        <a href="#" class="nav-link submenu-toggle">
          <i class="fas fa-heartbeat"></i>
          Health Monitoring
          <i class="fas fa-chevron-down submenu-icon"></i>
        </a>
        <div class="submenu">
          <a href="./new-health-record.php" class="submenu-link">New Health Record</a>
          <a href="./livestock-health-monitoring.php" class="submenu-link">Livestock Health Monitoring</a>
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

  <div class="main-content" id="mainContent">
    <h1 class="mb-4">Dashboard</h1>

    <div class="row">
      <div class="col-lg-4 col-md-6">
        <div class="stats-card purple">
          <div class="stats-number"><?php echo $totalLivestock; ?></div>
          <div class="stats-label">Total Livestock</div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="stats-card orange">
          <div class="stats-number"><?php echo $totalHealthRecords; ?></div>
          <div class="stats-label">Total Health Records</div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="stats-card green">
          <div class="stats-number"><?php echo $needsAttention; ?></div>
          <div class="stats-label">Animals Needing Attention</div>
        </div>
      </div>
    </div>

    <div class="charts-container mt-4">
      <div class="chart-card">
        <h2 class="chart-title">Animal Health Distribution</h2>
        <div class="chart-wrapper">
          <canvas id="donutChart"></canvas>
        </div>
      </div>

      <div class="chart-card">
        <h2 class="chart-title">Animal Health by Barangay</h2>
        <div class="chart-wrapper">
          <canvas id="barChart"></canvas>
        </div>
      </div>
    </div>

    <!-- <div class="table-container">
      <h5>Animal Health Overview</h5>
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>ANIMAL ID</th>
              <th>SPECIES</th>
              <th>BARANGAY</th>
              <th>HEALTH STATUS</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>01</strong></td>
              <td>Pig</td>
              <td>Cabcab</td>
              <td>
                <span class="status-badge status-healthy">HEALTHY</span>
              </td>
            </tr>
            <tr>
              <td><strong>02</strong></td>
              <td>Carabao</td>
              <td>Poblacion</td>
              <td><span class="status-badge status-sick">SICK</span></td>
            </tr>
            <tr>
              <td><strong>03</strong></td>
              <td>Chicken</td>
              <td>San Jose</td>
              <td>
                <span class="status-badge status-healthy">HEALTHY</span>
              </td>
            </tr>
            <tr>
              <td><strong>04</strong></td>
              <td>Goat</td>
              <td>Maligaya</td>
              <td>
                <span class="status-badge status-healthy">HEALTHY</span>
              </td>
            </tr>
            <tr>
              <td><strong>05</strong></td>
              <td>Cow</td>
              <td>Riverside</td>
              <td><span class="status-badge status-sick">SICK</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div> -->

    <!-- <div class="action-buttons">
        <h2 class="quick-action-header">Quick Actions</h2>

        <button
          class="btn btn-custom btn-update"
          data-bs-toggle="modal"
          data-bs-target="#updateHealthModal"
        >
          <i class="fas fa-plus"></i>
          Update Health Record
        </button>
        <button
          class="btn btn-custom btn-treatment"
          data-bs-toggle="modal"
          data-bs-target="#recordTreatmentModal"
        >
          <i class="fas fa-pen"></i>
          Record Treatment
        </button>
      </div> -->
  </div>



  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.3.0/chart.umd.min.js"></script>
  <script src="../../assets/scripts/sidebar.js"></script>
  <script src="../../assets/scripts/vet-dashboard.js"></script>
</body>

</html>