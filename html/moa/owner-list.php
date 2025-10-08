<?php 

include_once '../../helper/db.php';

$smtp = $pdo->prepare("SELECT * FROM owners ORDER BY id DESC");
if($smtp->execute()){
   $result = $smtp->fetchAll(PDO::FETCH_ASSOC);

}


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
      href="../../assets/styles/layouts/veterinarian/livestock-health-monitoring.css"
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
          <a href="./dashboard.php" class="nav-link ">
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
          <a href="./owner-list.php" class="nav-link active">
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
          <a href="./reports.php" class="nav-link">
            <i class="fas fa-sign-out-alt"></i>
            Reports
          </a>
        </div>
        <div class="nav-item">
          <a href="./qr.php" class="nav-link">
            <i class="fas fa-sign-out-alt"></i>
            QR Code
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
      <main class="main-content">
        <div class="header d-flex align-items-center justify-content-between">
          <h2 class="page-title fade-in">
            <i class="fas fa-clipboard-list me-3"></i>Livestock Owner Registration
          </h2>
          <a href="./owner-registration.php" class="btn btn-add-record">
            <i class="fas fa-plus me-2"></i>Add Owner
          </a>
        </div>

        <!-- Health Records Table -->
        <div class="table-container mt-5">
          <div class="table-responsive">
            <table class="table mb-0" id="healthRecordsTable">
              <thead>
                <tr>
                  <th>Owner ID</th>
              <th>Full Name</th>
              <th>No. of Livestock</th>
              <th>Type of Livestock</th>
              <th>Notes/Remarks</th>
              <th>Actions</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach($result as $r){ ?>
    <?php 
        echo "<tr>";
        echo "<td>".$r["id"]."</td>";
        echo "<td><span class=''>".$r['fullname']."</span></td>";
        echo "<td>".$r['number_of_livestock']."</td>";
        echo "<td>".$r["type_of_livestock"]."</td>";
        echo "<td>".$r["notes"]."</td>";
        echo "<td>
                <a class='btn btn-sm btn-outline-primary me-1' href='./owner-registration.php?uid=".$r['id']."'>
                    <i class='fas fa-edit'></i>
                </a>
                <button class='btn btn-sm btn-outline-danger' onclick='deleteRecord(this)'>
                    <i class='fas fa-trash'></i>
                </button>
              </td>";
        echo "</tr>";
    ?>
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
  if (confirm("Are you sure you want to delete this health record?")) {
    const row = button.closest("tr");
    row.style.animation = "fadeOut 0.3s ease-out";
    setTimeout(() => {
      row.remove();
      showNotification("Health record deleted successfully!", "danger");
    }, 300);
    // alert(row.children[0].innerHTML)
    const res = await fetch(`../../helper/deleteOwner.php?ownerId=${row.children[0].innerHTML}`)
    const r = await res.json();
    console.log(r)
  }
}

    </script>
    <script src="../../assets/scripts/sidebar.js"></script>
  </body>
</html>
