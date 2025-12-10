<?php
require_once '../../config/db.php';

// Get total livestock count
$livestockStmt = $pdo->query("SELECT COUNT(*) as count FROM livestock");
$totalLivestock = $livestockStmt->fetch()['count'];

// Get total owners count
$ownerStmt = $pdo->query("SELECT COUNT(*) as count FROM owner");
$totalOwners = $ownerStmt->fetch()['count'];

// Get health records count
$healthStmt = $pdo->query("SELECT COUNT(*) as count FROM healthmonitoring");
$totalHealthRecords = $healthStmt->fetch()['count'];

// Get livestock needing attention (not healthy)
$needsAttentionStmt = $pdo->query("SELECT COUNT(*) as count FROM healthmonitoring WHERE livestock_status != 'Healthy'");
$needsAttention = $needsAttentionStmt->fetch()['count'];

// Get livestock by species for chart
$speciesStmt = $pdo->query("SELECT species, COUNT(*) as count FROM livestock GROUP BY species");
$speciesData = $speciesStmt->fetchAll(PDO::FETCH_ASSOC);

// Get health status distribution
$healthStatusStmt = $pdo->query("
    SELECT livestock_status, COUNT(*) as count 
    FROM healthmonitoring 
    GROUP BY livestock_status
");
$healthStatusData = $healthStatusStmt->fetchAll(PDO::FETCH_ASSOC);

// Get recent health records (last 5)
$recentHealthStmt = $pdo->query("
    SELECT h.*, l.owner_name, l.species, l.breed, DATE_FORMAT(h.createdAt, '%M %d, %Y') as formatted_date
    FROM healthmonitoring h
    JOIN livestock l ON h.livestock_id = l.id
    ORDER BY h.createdAt DESC
    LIMIT 5
");
$recentHealthRecords = $recentHealthStmt->fetchAll(PDO::FETCH_ASSOC);

// Get recent owner registrations (last 5)
$recentOwnersStmt = $pdo->query("
    SELECT CONCAT(firstName, ' ', surname) as fullName, 
           address,
           DATE_FORMAT(id, '%M %d, %Y') as registered_date
    FROM owner
    ORDER BY id DESC
    LIMIT 5
");
$recentOwners = $recentOwnersStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Alagang Baras - Livestock Management System</title>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="../../assets/styles/components/sidebar.css" />
    <link
      rel="stylesheet"
      href="../../assets/styles/layouts/moa/dashboard.css"
    />
  </head>
  <body>
    <div class="overlay" id="overlay"></div>

    <button class="hamburger-btn" id="hamburgerBtn">
      <i class="fas fa-bars"></i>
    </button>

    <div class="sidebar" id="sidebar">
      <div class="logo">
        <h4>Alagang Baras</h4>
        <p>Livestock Management System</p>
      </div>

      <nav class="nav-menu">
        <div class="nav-item">
          <a href="./dashboard.php" class="nav-link active">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
          </a>
        </div>

        <div class="nav-item">
          <a href="./owner-list.php" class="nav-link">
            <i class="fas fa-sign-out-alt"></i>
            Owner Management
          </a>
        </div>

        <div class="nav-item">
          <a href="./livestock-health-monitoring.php" class="nav-link">
            <i class="fas fa-tachometer-alt"></i>
            Health Monitoring
          </a>
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

    <div class="main-content" id="mainContent">
      <h1 class="mb-4">Dashboard</h1>

      <div class="row">
        <div class="col-lg-3 col-md-6 mb-3">
          <div class="stats-card purple">
            <div class="stats-number"><?php echo $totalLivestock; ?></div>
            <div class="stats-label">Total Livestock</div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
          <div class="stats-card orange">
            <div class="stats-number"><?php echo $totalOwners; ?></div>
            <div class="stats-label">Total Registered Owners</div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
          <div class="stats-card green">
            <div class="stats-number"><?php echo $totalHealthRecords; ?></div>
            <div class="stats-label">Total Health Records</div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
          <div class="stats-card red">
            <div class="stats-number"><?php echo $needsAttention; ?></div>
            <div class="stats-label">Needs Attention</div>
          </div>
        </div>
      </div>

      <!-- Charts Section -->
      <div class="row mt-4">
        <!-- Livestock by Species Chart -->
        <div class="col-lg-6 mb-4">
          <div class="chart-container">
            <h5 class="chart-title">Livestock by Species</h5>
            <div class="chart-cont">
              <canvas id="animalChart" width="400" height="300"></canvas>
            </div>
          </div>
        </div>

        <!-- Health Status Pie Chart -->
        <div class="col-lg-6 mb-4">
          <div class="chart-container">
            <h5 class="chart-title">Health Status Distribution</h5>
            <div class="chart-cont">
              <canvas id="healthStatusChart" width="400" height="300"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activities Section -->
      <div class="row mt-4">
        <!-- Recent Health Records -->
        <div class="col-lg-6 mb-4">
          <div class="chart-container">
            <h5 class="chart-title">Recent Health Records</h5>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Owner</th>
                    <th>Species</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (count($recentHealthRecords) > 0): ?>
                    <?php foreach ($recentHealthRecords as $record): ?>
                      <tr>
                        <td><?php echo $record['formatted_date']; ?></td>
                        <td><?php echo htmlspecialchars($record['owner_name']); ?></td>
                        <td><?php echo htmlspecialchars($record['species'] . ' - ' . $record['breed']); ?></td>
                        <td>
                          <span class="badge bg-<?php echo $record['livestock_status'] == 'Healthy' ? 'success' : ($record['livestock_status'] == 'Sick' ? 'danger' : 'warning'); ?>">
                            <?php echo htmlspecialchars($record['livestock_status']); ?>
                          </span>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="4" class="text-center">No recent health records</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Recent Owner Registrations -->
        <div class="col-lg-6 mb-4">
          <div class="chart-container">
            <h5 class="chart-title">Recent Owner Registrations</h5>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Registered</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (count($recentOwners) > 0): ?>
                    <?php foreach ($recentOwners as $owner): ?>
                      <tr>
                        <td><?php echo htmlspecialchars($owner['fullName']); ?></td>
                        <td><?php echo htmlspecialchars($owner['address'] ?: 'N/A'); ?></td>
                        <td><?php echo $owner['registered_date']; ?></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="3" class="text-center">No recent registrations</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <script src="../../assets/scripts/sidebar.js"></script>
    <script>
      // Livestock by Species Chart
      const ctx = document.getElementById("animalChart").getContext("2d");
      
      <?php
      $speciesLabels = [];
      $speciesCounts = [];
      foreach ($speciesData as $species) {
          $speciesLabels[] = $species['species'];
          $speciesCounts[] = $species['count'];
      }
      ?>

      const animalChart = new Chart(ctx, {
        type: "bar",
        data: {
          labels: <?php echo json_encode($speciesLabels); ?>,
          datasets: [
            {
              label: "Number of Animals",
              data: <?php echo json_encode($speciesCounts); ?>,
              backgroundColor: "#4A90E2",
              borderRadius: 6,
              maxBarThickness: 50,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          plugins: {
            legend: {
              display: true,
              position: "top",
              labels: { color: "#000" },
            },
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: { color: "rgba(0,0,0,0.1)" },
              ticks: { color: "#333" },
            },
            x: {
              grid: { display: false },
              ticks: { color: "#333" },
            },
          },
        },
      });

      // Health Status Pie Chart
      const healthCtx = document.getElementById("healthStatusChart").getContext("2d");
      
      <?php
      $statusLabels = [];
      $statusCounts = [];
      $statusColors = [];
      $colorMap = [
          'Healthy' => '#28a745',
          'Sick' => '#dc3545',
          'Under Treatment' => '#ffc107',
          'Recovering' => '#17a2b8',
          'Quarantined' => '#fd7e14'
      ];
      
      foreach ($healthStatusData as $status) {
          $statusLabels[] = $status['livestock_status'];
          $statusCounts[] = $status['count'];
          $statusColors[] = $colorMap[$status['livestock_status']] ?? '#6c757d';
      }
      ?>

      const healthStatusChart = new Chart(healthCtx, {
        type: "pie",
        data: {
          labels: <?php echo json_encode($statusLabels); ?>,
          datasets: [
            {
              label: "Health Status",
              data: <?php echo json_encode($statusCounts); ?>,
              backgroundColor: <?php echo json_encode($statusColors); ?>,
              borderWidth: 2,
              borderColor: '#fff'
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          plugins: {
            legend: {
              display: true,
              position: "right",
              labels: { color: "#000", padding: 15 },
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  let label = context.label || '';
                  let value = context.parsed || 0;
                  let total = context.dataset.data.reduce((a, b) => a + b, 0);
                  let percentage = ((value / total) * 100).toFixed(1);
                  return label + ': ' + value + ' (' + percentage + '%)';
                }
              }
            }
          },
        },
      });
    </script>
  </body>
</html>