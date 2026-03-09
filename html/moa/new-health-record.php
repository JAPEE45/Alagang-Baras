<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Alagang Baras - Health Monitoring</title>
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
      href="../../assets/styles/layouts/veterinarian/health-monitoring.css"
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
            <a href="./new-health-record.php" class="submenu-link active"
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

    <!-- Main Content -->
    <div class="main-content">
      <!-- Main Content Area -->
      <div class="col-md-9">
        <div class="content-card">
          <h1 class="page-title">New Health Record</h1>

          <form id="healthRecordForm">
            <div class="form-row search-cont">
              <label for="checkupDate" class="form-label"
                >Search</label
              >
              <input
                type="text"
                class="form-control"
                id="searchOwner"
                name="searchOwner"
                required
                placeholder="Search ..."
              />


            </div>
            <!-- Date Field -->
            <div class="form-row">
              <label for="checkupDate" class="form-label"
                >Date of Check-up:</label
              >
              <input
                type="date"
                class="form-control"
                id="checkupDate"
                name="checkupDate"
                required
              />
            </div>

            <!-- Diagnosis Field -->
            <div class="form-row">
              <label for="diagnosis" class="form-label">Diagnosis:</label>
              <textarea
                class="form-control"
                id="diagnosis"
                name="diagnosis"
                rows="4"
                placeholder="Enter diagnosis details..."
              ></textarea>
            </div>

            <!-- Treatment Field -->
            <div class="form-row">
              <label for="treatment" class="form-label">Treatment:</label>
              <textarea
                class="form-control"
                id="treatment"
                name="treatment"
                rows="4"
                placeholder="Enter treatment details..."
              ></textarea>
            </div>

            <!-- Vaccine Field -->
            <div class="form-row">
              <label for="vaccine" class="form-label">Vaccine Given:</label>
              <select class="form-select" id="vaccine" name="vaccine">
                <option value="">Select vaccine...</option>
                <option value="rabies">Rabies Vaccine</option>
                <option value="distemper">Distemper Vaccine</option>
                <option value="parvovirus">Parvovirus Vaccine</option>
                <option value="hepatitis">Hepatitis Vaccine</option>
                <option value="leptospirosis">Leptospirosis Vaccine</option>
                <option value="other">Other</option>
              </select>
            </div>

            <!-- Veterinarian Name Field -->
            <div class="form-row">
              <label for="vetName" class="form-label"
                >Name of Veterinarian:</label
              >
              <select class="form-select" id="vetName" name="vetName">
                <option value="">Select veterinarian...</option>
                <option value="dr-santos">Dr. Maria Santos</option>
                <option value="dr-cruz">Dr. Juan Cruz</option>
                <option value="dr-reyes">Dr. Ana Reyes</option>
                <option value="dr-garcia">Dr. Carlos Garcia</option>
                <option value="other">Other</option>
              </select>
            </div>

            <!-- Remarks Field -->
            <div class="form-row">
              <label for="remarks" class="form-label">Remarks:</label>
              <textarea
                class="form-control"
                id="remarks"
                name="remarks"
                rows="4"
                placeholder="Additional notes or observations..."
                height="100px"
              ></textarea>
            </div>

            <!-- Action Buttons -->
            <div class="btn-group-custom">
              <button type="submit" class="btn btn-save">
                <i class="fas fa-save me-2"></i>Save Record
              </button>
              <button type="reset" class="btn btn-reset">
                <i class="fas fa-redo me-2"></i>Reset
              </button>
              <button type="button" class="btn btn-cancel" onclick="goBack()">
                <i class="fas fa-times me-2"></i>Cancel
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script src="../../assets/scripts/sidebar.js"></script>
    <script src="../../assets/scripts/vet-health-monitoring.js"></script>
  </body>
</html>
