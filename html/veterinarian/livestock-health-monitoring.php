<?php
session_start();
include_once "../../helper/db.php";

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT v.fullname FROM users u JOIN veterinarians v ON u.user_id = v.user_id WHERE u.user_id = ?");
$stmt->execute([$userId]);
$vetName = $stmt->fetch();
echo $userId;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Livestock Health Monitoring System</title>
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css"
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
  <!-- Overlay for mobile -->
  <div class="overlay" id="overlay"></div>

  <!-- Hamburger Menu Button -->
  <button class="hamburger-btn" id="hamburgerBtn">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div class="logo">
      <h4>Alagang Baras</h4>
      <p>Livestock Management System</p>
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
          <a href="./new-health-record.php" class="submenu-link">New Health Record</a>
          <a href="./livestock-health-monitoring.php" class="submenu-link active">Livestock Health Monitoring</a>
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

  <div class="main-content">
    <!-- Main Content -->

    <div class="content-area h-100">
      <div class="header-section">
        <h2 class="header-title">Livestock Health Monitoring</h2>
      </div>

      <!-- Animal Information Form -->
      <div class="form-section">
        <div class="row">
          <div class="col-md-8 mb-3">
            <label for="search-owner" class="form-label">Owner Name:</label>
            <input
              type="search"
              class="form-control"
              id="search-owner"
              placeholder="Search..." />
          </div>
          <div class="col-md-4 mb-3">
            <label for="species" class="form-label">Vaccine Given:</label>
            <select class="form-control" id="species">
              <option value="" disabled selected>Select Vaccine</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Health Records Table -->
      <div class="table-container">
        <div class="table-responsive">
          <table class="table mb-0" id="healthRecordsTable">
            <thead>
              <tr>
                <th>Date</th>
                <th>Owner Name</th>
                <th>Species</th>
                <th>Breed</th>
                <th>Vaccine Given</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>01/01/25</td>
                <td>
                  <span class="status-badge">Hannah Denielle Teodoro</span>
                </td>
                <td>Christian Jireh Briol</td>
                <td>Goat</td>
                <td>Anti Rabies</td>
                <td>
                  <a
                    href="./new-health-record.php"
                    class="btn btn-sm btn-outline-primary me-1">
                    <i class="fas fa-edit"></i>
                  </a>
                  <button
                    class="btn btn-sm btn-outline-danger"
                    onclick="deleteRecord(this)">
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Add New Record Button -->
      <div class="text-end mt-4">
        <a
          class="btn btn-add-record"
          href="./new-health-record.php">
          <i class="fas fa-plus me-2"></i>Add New Record
        </a>
      </div>
    </div>
  </div>

  <!-- Add New Record Modal -->
  <div
    class="modal fade"
    id="addRecordModal"
    tabindex="-1"
    aria-labelledby="addRecordModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addRecordModalLabel">
            <i class="fas fa-plus-circle me-2"></i>Add New Health Record
          </h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addRecordForm">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="recordDate" class="form-label">Date</label>
                <input
                  type="date"
                  class="form-control"
                  id="recordDate"
                  required />
              </div>
              <div class="col-md-6 mb-3">
                <label for="ownerName" class="form-label">Owner Name</label>
                <input type="text" class="form-control" id="ownerName" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="animalType" class="form-label">Animal Name</label>
                <input
                  type="text"
                  class="form-control"
                  id="animalType"
                  placeholder="Enter treatment"
                  required />
              </div>
              <div class="col-md-6 mb-3">
                <label for="vaccineGiven" class="form-label">Vaccine Given</label>
                <input
                  type="text"
                  class="form-control"
                  id="vaccineGiven"
                  placeholder="Enter vet name"
                  required />
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="diagnosis" class="form-label">Diagnosis</label>
                <input
                  type="text"
                  class="form-control"
                  id="diagnosis"
                  required />
              </div>
              <div class="col-md-6 mb-3">
                <label for="treatment" class="form-label">Treatment</label>
                <input
                  type="text"
                  class="form-control"
                  id="treatment"
                  required />
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="vaccineGiven" class="form-label">Vaccine Given</label>
                <input
                  type="text"
                  class="form-control"
                  id="vaccineGiven"
                  required />
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal">
            Cancel
          </button>
          <button
            type="button"
            class="btn btn-primary"
            onclick="addNewRecord()">
            Save Record
          </button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

  <script src="../../assets/scripts/sidebar.js"></script>
  <script src="../../assets/scripts/vet-livestock-health-monitoring.js"></script>
</body>

</html>