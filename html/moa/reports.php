<?php
require_once '../../config/db.php';

$reportStmt = $pdo->query("
    SELECT 
        COUNT(DISTINCT o.id) as total_farmers,
        COUNT(DISTINCT l.id) as total_livestock,
        SUM(CASE WHEN hm.vaccine_given IS NOT NULL AND hm.vaccine_given != '' THEN 1 ELSE 0 END) as total_vaccinated,
        SUM(CASE WHEN hm.livestock_status != 'Healthy' THEN 1 ELSE 0 END) as under_observation
    FROM owner o
    LEFT JOIN livestock l ON l.owner_id = o.id
    LEFT JOIN healthmonitoring hm ON hm.livestock_id = l.id
");
$reportData = $reportStmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Livestock Report Dashboard</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../../assets/styles/components/sidebar.css">
  <link rel="stylesheet" href="../../assets/styles/layouts/moa/reports.css">
</head>

<body>
  <div class="overlay" id="overlay"></div>
  <button class="hamburger-btn" id="hamburgerBtn">
    <i class="fas fa-bars"></i>
  </button>

  <div class="sidebar" id="sidebar">
    <div class="logo-container">
      <div class="logo"><img src="../../assets/images/64c57e19-d2a4-42cf-9a5b-4b35dd14f9f7.png" alt=""></div>
      <div class="logo secondary"><img src="../../assets/images/9302dfc3-307b-42b1-a9d2-8c31dc0cb9c6.png" alt=""></div>
      <div class="logo tertiary"><img src="../../assets/images/c567f4c0-7403-4ca0-8556-5bac257c9190.png" alt=""></div>
    </div>
    <div class="logo-text">
      <h4>Alagang Baras</h4>
      <p>MAO PAGE</p>
    </div>
    <nav class="nav-menu">
      <div class="nav-item">
        <a href="./dashboard.php" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      </div>
      <div class="nav-item has-submenu">
        <a href="#" class="nav-link submenu-toggle">
          <i class="fas fa-heartbeat"></i> User Management
          <i class="fas fa-chevron-down submenu-icon"></i>
        </a>
        <div class="submenu">
          <a href="./coordinator_management.php" class="submenu-link">Coordinator</a>
          <a href="./vet_management.php" class="submenu-link">Veterinarian</a>
        </div>
      </div>
      <div class="nav-item">
        <a href="./livestock-profiling-list.php" class="nav-link"><i class="fas fa-paw"></i> Livestock Profiling</a>
      </div>
      <div class="nav-item">
        <a href="./reports.php" class="nav-link active"><i class="fas fa-file"></i> Reports</a>
      </div>
      <div class="nav-item">
        <a href="../index.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Log out</a>
      </div>
    </nav>
  </div>

  <div class="main-content" id="mainContent">
    <h1 class="page-title mb-4">
      <i class="fa-solid fa-file me-3"></i>Reports
    </h1>


    <div class="container">
  <div class="table-container">
    <table id="dataTable">
      <thead>
        <tr>
          <th>Barangay</th>
          <th>Total Livestock</th>
          <th>Total Farmers</th>
          <th>Vaccinated</th>
          <th>Under Observation</th>
        </tr>
      </thead>
      <tbody id="tableBody">
        <tr>
          <td>Paniquihan</td>
          <td><?php echo $reportData['total_livestock'] ?? 0; ?></td>
          <td><?php echo $reportData['total_farmers'] ?? 0; ?></td>
          <td><?php echo $reportData['total_vaccinated'] ?? 0; ?></td>
          <td><?php echo $reportData['under_observation'] ?? 0; ?></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="export-buttons">
    <button class="export-btn pdf" onclick="exportToPDF()">Export to PDF</button>
    <button class="export-btn excel" onclick="exportToExcel()">Export to Excel</button>
    <button class="export-btn print" onclick="printReport()">Print Report</button>
  </div>
</div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

  <script>
    // Filter table by barangay dropdown or search input
    function filterTable() {
      const barangayVal = document.getElementById('barangayFilter').value.toLowerCase();
      const searchVal = document.getElementById('searchFilter').value.toLowerCase();
      const rows = document.querySelectorAll('#tableBody tr[data-barangay]');

      rows.forEach(row => {
        const barangay = row.getAttribute('data-barangay').toLowerCase();
        const matchesDropdown = !barangayVal || barangay === barangayVal;
        const matchesSearch = !searchVal || barangay.includes(searchVal);
        row.style.display = (matchesDropdown && matchesSearch) ? '' : 'none';
      });
    }

    // Export to PDF
    function exportToPDF() {
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF();
      doc.text('Livestock Report', 14, 15);
      doc.autoTable({
        head: [['Barangay', 'Total Livestock', 'Total Farmers', 'Vaccinated', 'Under Observation']],
        body: getVisibleRows(),
        startY: 20
      });
      doc.save('livestock-report.pdf');
    }

    // Export to Excel
    function exportToExcel() {
      const rows = getVisibleRows();
      const header = [['Barangay', 'Total Livestock', 'Total Farmers', 'Vaccinated', 'Under Observation']];
      const ws = XLSX.utils.aoa_to_sheet([...header, ...rows]);
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, 'Report');
      XLSX.writeFile(wb, 'livestock-report.xlsx');
    }

    // Print
    function printReport() {
      window.print();
    }

    // Helper: get visible table rows as array
    function getVisibleRows() {
      const rows = document.querySelectorAll('#tableBody tr[data-barangay]');
      const data = [];
      rows.forEach(row => {
        if (row.style.display !== 'none') {
          const cells = row.querySelectorAll('td');
          data.push([...cells].map(td => td.innerText));
        }
      });
      return data;
    }
  </script>

  <script src="../../assets/scripts/sidebar.js"></script>
</body>
</html>