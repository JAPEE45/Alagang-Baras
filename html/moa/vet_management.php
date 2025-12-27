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
    <div class="logo">
      <h4>Alagang Baras</h4>
      <p>MAO PAGE</p>
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
          User Management
          <i class="fas fa-chevron-down submenu-icon"></i>
        </a>
        <div class="submenu">
          <a href="./coordinator_management.php" class="submenu-link">Coordinator</a>
          <a href="./vet_management.php" class="submenu-link active">Veterinarian</a>
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
          <i class="fas fa-clipboard-list me-3"></i>Veterinarian Management
        </h2>
        <a href="./vet_registration.php" class="btn btn-add-record">
          <i class="fas fa-plus me-2"></i>Add Veterinarian
        </a>
      </div>

      <?php
      require_once __DIR__ . '/../../config/db.php';
      $stmt = $pdo->prepare('SELECT v.vet_id, v.user_id, v.prc_number, v.specialization, v.fullname, u.username FROM veterinarians v JOIN users u ON v.user_id = u.user_id ORDER BY v.vet_id DESC');
      $stmt->execute();
      $vets = $stmt->fetchAll();
      ?>

      <!-- Veterinarians Table -->
      <div class="table-container mt-5">
        <div class="table-responsive">
          <table class="table mb-0" id="healthRecordsTable">
            <thead>
              <tr>
                <th>PRC Number</th>
                <th>Full Name</th>
                <th>Specialization</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($vets as $r) { ?>
                <tr data-user-id="<?php echo htmlspecialchars($r['user_id']); ?>">
                  <td><?php echo htmlspecialchars($r['prc_number']); ?></td>
                  <td><?php echo htmlspecialchars($r['fullname']); ?></td>
                  <td><?php echo htmlspecialchars($r['specialization']); ?></td>
                  <td>
                    <a class="btn btn-sm btn-outline-primary me-1" href="./vet_registration.php?user_id=<?php echo urlencode($r['user_id']); ?>&uid=<?php echo urlencode($r['user_id']); ?>">
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
      if (!confirm("Are you sure you want to delete this veterinarian?")) return;
      const row = button.closest('tr');
      const userId = row.getAttribute('data-user-id');
      try {
        const res = await fetch('../../helper/deleteVeterinarian.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ user_id: userId })
        });
        const data = await res.json();
        if (data.status === 'success') {
          row.style.animation = 'fadeOut 0.3s ease-out';
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