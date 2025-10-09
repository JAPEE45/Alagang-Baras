<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Livestock Report Dashboard</title>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="../../assets/styles/components/sidebar.css">
    <link rel="stylesheet" href="../../assets/styles/layouts/moa/reports.css">
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
          <a href="./dashboard.php" class="nav-link">
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
            <a href="./new-health-record.php" class="submenu-link"
              >New Health Record</a
            >
            <a href="./livestock-health-monitoring.php" class="submenu-link"
              >Livestock Health Monitoring</a
            >
          </div>
        </div>

        <div class="nav-item">
          <a href="./owner-list.php" class="nav-link">
            <i class="fas fa-sign-out-alt"></i>
            Owner Registration
          </a>
        </div>
        <div class="nav-item">
          <a href="./livestock-profiling-list.php" class="nav-link">
            <i class="fas fa-sign-out-alt"></i>
            Livestock Profiling
          </a>
        </div>
        <div class="nav-item">
          <a href="#" class="nav-link active">
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
      <h1 class="page-title mb-4">
        <i class="fa-solid fa-file me-3"></i>Reports</h1>

      <div class="container">
      <div class="filters">
        <div class="filter-group">
          <label for="barangayFilter">Select Barangay</label>
          <select id="barangayFilter">
            <option value="">All Barangays</option>
          </select>
        </div>
        <div class="filter-group">
          <label for="reportTypeFilter">Report Type</label>
          <select id="reportTypeFilter">
            <option value="">All Reports</option>
            <option value="vaccination">Vaccination Report</option>
            <option value="farmer">Farmer Report</option>
            <option value="livestock">Livestock Report</option>
          </select>
        </div>
      </div>

      <div class="table-container">
        <table id="dataTable">
          <thead>
            <tr>
              <th>Barangay</th>
              <th>Total Livestock</th>
              <th>Farmers</th>
              <th>Vaccinated</th>
              <th>Pending</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            <!-- Data will be populated here -->
          </tbody>
        </table>
      </div>

      <div class="export-buttons">
        <button class="export-btn pdf" onclick="exportToPDF()">
          Export to PDF
        </button>
        <button class="export-btn excel" onclick="exportToExcel()">
          Export to Excel
        </button>
        <button class="export-btn print" onclick="printReport()">
          Print Report
        </button>
      </div>
    </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script src="../../assets/scripts/moa-reports.js"></script>
    <script src="../../assets/scripts/sidebar.js"></script>
  </body>
</html>
