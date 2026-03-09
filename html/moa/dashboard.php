<?php
require_once '../../config/db.php';

// Get total livestock count
$livestockStmt = $pdo->query("SELECT COUNT(*) as count FROM livestock");
$totalLivestock = $livestockStmt->fetch()['count'];

// Get total owners count
$ownerStmt = $pdo->query("SELECT COUNT(*) as count FROM owner");
$totalOwners = $ownerStmt->fetch()['count'];

// Get health records count (for animals needing attention)
$healthStmt = $pdo->query("SELECT COUNT(*) as count FROM healthmonitoring WHERE livestock_status != 'Healthy'");
$needsAttention = $healthStmt->fetch()['count'];

// Get livestock by species
$speciesStmt = $pdo->query("SELECT species, COUNT(*) as count FROM livestock GROUP BY species");
$speciesData = $speciesStmt->fetchAll(PDO::FETCH_ASSOC);
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
          <a href="./dashboard.php" class="nav-link active">
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
            <a href="./coordinator_management.php" class="submenu-link"
              >Coordinator</a
            >
            <a href="./vet_management.php" class="submenu-link"
              >Veterinarian</a
            >
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
            <div class="stats-number"><?php echo $totalOwners; ?></div>
            <div class="stats-label">Total Registered Owners</div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="stats-card green">
            <div class="stats-number"><?php echo $needsAttention; ?></div>
            <div class="stats-label">Animals Needing Attention</div>
          </div>
        </div>
      </div>

      <!-- Charts Section -->
      <div class="row mt-4">
        <!-- Bar Chart -->
        <div class="col-lg-6 mb-4">
          <div class="chart-container">
            <h5 class="chart-title">Livestock by Location</h5>
            <canvas id="barChart" width="400" height="300"></canvas>
          </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-lg-6 mb-4">
          <div class="chart-container">
            <h5 class="chart-title">Livestock Distribution</h5>
            <div class="row">
              <div class="col-8">
                <canvas id="pieChart" width="300" height="300"></canvas>
              </div>
              <div class="col-4">
                <div class="livestock-breakdown">
                  <div class="breakdown-item">
                    <span class="breakdown-label">Cattle</span>
                    <span class="breakdown-percentage">55%</span>
                  </div>
                  <div class="breakdown-item">
                    <span class="breakdown-label">Goats</span>
                    <span class="breakdown-percentage">30%</span>
                  </div>
                  <div class="breakdown-item">
                    <span class="breakdown-label">Pigs</span>
                    <span class="breakdown-percentage">15%</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <script src="../../assets/scripts/sidebar.js"></script>
    <script>
        const barCtx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: [
                    'January', 'February', 'March', 'April', 'May', 'June', 
                    'July', 'August', 'September', 'October', 'November', 'December'
                ],
                datasets: [
                    {
                        label: 'Total Livestock',
                        data: [120, 125, 130, 128, 140, 145, 150, 148, 155, 160, 158, 165], // Dummy Data
                        backgroundColor: 'rgba(54, 162, 235, 0.6)', // Blue
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Diseased Livestock',
                        data: [5, 8, 4, 6, 12, 15, 10, 8, 5, 7, 9, 6], // Dummy Data
                        backgroundColor: 'rgba(255, 99, 132, 0.6)', // Red
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Animals'
                        }
                    }
                }
            }
        });

        const pieCtx = document.getElementById('pieChart').getContext('2d');
        const speciesData = <?php echo json_encode($speciesData); ?>;
        const speciesLabels = speciesData.map(item => item.species || 'Unknown');
        const speciesCounts = speciesData.map(item => parseInt(item.count));
        const totalCount = speciesCounts.reduce((a, b) => a + b, 0);
        
        const pieChart = new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: speciesLabels,
                datasets: [{
                    data: speciesCounts,
                    backgroundColor: ['#FF6B6B', '#4ECDC4', '#FFE066', '#95E1D3', '#F38181', '#AA96DA'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const percentage = totalCount > 0 ? ((value / totalCount) * 100).toFixed(1) : 0;
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });
    </script>
  </body>
</html>
