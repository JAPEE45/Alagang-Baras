<!-- <?php

      include_once '../../helper/db.php';

      $smtp = $pdo->prepare("SELECT * FROM owner ORDER BY id DESC");
      if ($smtp->execute()) {
        $result = $smtp->fetchAll(PDO::FETCH_ASSOC);
      }


      ?> -->
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
    href="../../assets/styles/layouts/veterinarian/livestock-health-monitoring.css" />
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
        <a href="#" class="nav-link submenu-toggle active">
          <i class="fas fa-heartbeat"></i>
          User Management
          <i class="fas fa-chevron-down submenu-icon"></i>
        </a>
        <div class="submenu">
          <a href="./coordinator_management.php" class="submenu-link active">Coordinator</a>
          <a href="./vet_management.php" class="submenu-link">Veterinarian</a>
        </div>
      </div>

      <div class="nav-item">
        <a href="./livestock-profiling-list.php" class="nav-link">
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
      <!-- <div class="nav-item">
          <a href="./qr.php" class="nav-link">
            <i class="fas fa-sign-out-alt"></i>
            QR Code
          </a>
        </div> -->
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
    <main class="main-content">
      <div class="header d-flex align-items-center justify-content-between">
        <h2 class="page-title fade-in">
          <i class="fas fa-clipboard-list me-3"></i>Coordinator Management
        </h2>
        <a href="./coordinator_registration.php" class="btn btn-add-record">
          <i class="fas fa-plus me-2"></i>Add Coordinator
        </a>
      </div>

      <?php
    // Fetch coordinators joined with users
    require_once __DIR__ . '/../../config/db.php';
    // Check if contact_number column exists in coordinators
    $colExists = false;
    try {
      $colStmt = $pdo->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'coordinators' AND COLUMN_NAME = 'contact_number' LIMIT 1");
      $colStmt->execute();
      $colExists = (bool)$colStmt->fetch();
    } catch (Exception $e) {
      $colExists = false;
    }

    if ($colExists) {
      $sql = 'SELECT c.coordinator_id, c.user_id, c.position_role, c.barangay_assigned, c.fullname, c.contact_number, u.username FROM coordinators c JOIN users u ON c.user_id = u.user_id ORDER BY c.coordinator_id DESC';
    } else {
      // Fall back to showing password_ as contact_number when column doesn't exist (old data)
      $sql = 'SELECT c.coordinator_id, c.user_id, c.position_role, c.barangay_assigned, c.fullname, u.password_ AS contact_number, u.username FROM coordinators c JOIN users u ON c.user_id = u.user_id ORDER BY c.coordinator_id DESC';
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $coordinators = $stmt->fetchAll();
      ?>

      <!-- Coordinator Table -->
      <div class="table-container mt-5">
        <div class="table-responsive">
          <table class="table mb-0" id="healthRecordsTable">
            <thead>
              <tr>
                <th>Full Name</th>
                <th>Contact Number</th>
                <th>Barangay</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($coordinators as $r) { ?>
                <tr data-user-id="<?php echo htmlspecialchars($r['user_id']); ?>">
                  <td><?php echo htmlspecialchars($r['fullname']); ?></td>
                  <td><?php echo htmlspecialchars($r['contact_number'] ?? ''); ?></td>
                  <td><?php echo htmlspecialchars($r['barangay_assigned']); ?></td>
                  <td>
                    <a class="btn btn-sm btn-outline-primary me-1" href="./coordinator_registration.php?user_id=<?php echo urlencode($r['user_id']); ?>&uid=<?php echo urlencode($r['user_id']); ?>">
                      <i class="fas fa-edit"></i>
                    </a>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteRecord(this)">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>

    </main>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script>
    async function deleteRecord(button) {
      if (!confirm("Are you sure you want to delete this coordinator?")) return;
      const row = button.closest("tr");
      const userId = row.getAttribute('data-user-id');
      try {
        const res = await fetch('../../helper/deleteCoordinator.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ user_id: userId })
        });
        const data = await res.json();
        if (data.status === 'success') {
          row.style.animation = "fadeOut 0.3s ease-out";
          setTimeout(() => row.remove(), 300);
        } else {
          alert('Delete failed: ' + data.message);
        }
      } catch (err) {
        console.error(err);
        alert('Delete failed. See console.');
      }
    }
  </script>
  <script src="../../assets/scripts/sidebar.js"></script>
</body>

</html>