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
    href="../../assets/styles/layouts/moa/livestock-profiling.css" />
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

      <!-- <div class="nav-item has-submenu">
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
        </div> -->

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
    <main class="content">
      <h2 class="page-title fade-in">
        <i class="fas fa-clipboard-list me-3"></i>User Registration
      </h2>


      <form id="livestockForm" class="livestock-form fade-in">
        <div class="row">
          <div class="col-md-6 form-row">
            <label for="userID" class="form-label">ID:</label>
            <input
              type="text"
              class="form-control"
              id="userID"
              name="userID"
              disabled />
          </div>

          <div class="col-md-6 form-row">
            <label for="fullName" class="form-label">Full Name:</label>
            <input
              type="text"
              class="form-control"
              id="fullName"
              name="fullname"
              placeholder="Enter Full Name" />
          </div>
        </div>

        <div class="row">
          
          <div class="col-md-6 form-row">
            <label for="contactNum" class="form-label">Contact No:</label>
            <input
              type="text"
              class="form-control"
              id="contactNum"
              name="contact"
              placeholder="Enter Contact No." />
          </div>
          <div class="col-md-6 form-row">
            <label for="address" class="form-label">Sex:</label>
            <select name="" id="sex" class="form-control">
              <option hidden disabled selected>-- Select --</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 form-row">
            <label for="barangay" class="form-label">Barangay:</label>
            <input
              type="text"
              class="form-control"
              id="barangay"
              name="barangay"/>
          </div>
          <div class="col-md-6 form-row">
            <label for="role" class="form-label">Role:</label>
            <select name="" id="role" class="form-control">
              <option hidden disabled selected>-- Select --</option>
              <option value="Owner">Veterinarian</option>
              <option value="Coordinator">Coordinator</option>
              <option value="MAO">MAO</option>
            </select>
        </div>

        <div id="message-container" class="message-container"></div>
        <div class="btn-container">
          <button type="submit" class="btn btn-custom btn-save">Save</button>
          <button type="reset" class="btn btn-custom btn-reset" id="resetBtn">
            Reset
          </button>
          <button
            type="button"
            class="btn btn-custom btn-cancel"
            onclick="cancelForm()">
            Cancel
          </button>
        </div>
      </form>
    </main>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/scripts/moa-owner-reg.js"></script>
  <script src="../../assets/scripts/sidebar.js"></script>
</body>

</html>