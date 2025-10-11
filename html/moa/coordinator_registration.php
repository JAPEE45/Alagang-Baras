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
    <main class="content">
      <h2 class="page-title fade-in">
        <i class="fas fa-clipboard-list me-3"></i>Coordinator Registration
      </h2>


      <form id="livestockForm" class="livestock-form fade-in">
        <div class="row">
          <div class="col-md-6 form-row">
            <label for="fullName" class="form-label">Full Name:</label>
            <input
              type="text"
              class="form-control"
              id="fullName"
              name="fullname"
              placeholder="Enter Full Name" />
          </div>

          <div class="col-md-6 form-row">
            <label for="userID" class="form-label">Contact No:</label>
            <input
              type="text"
              class="form-control"
              id="contactNum"
              name="contactNum" />
          </div>
        </div>

        <div class="row">
          <div class="col-md- form-row">
            <label for="contactNum" class="form-label">Barangay:</label>
            <select name="barangay" id="barangay" class="form-control">
              <option disabled selected hidden>-- Select Barangay --</option>
              <option value="Abihao">Abihao</option>
              <option value="Agban">Agban</option>
              <option value="Bagong Sirang">Bagong Sirang</option>
              <option value="Benticayan">Benticayan</option>
              <option value="Buenavista">Buenavista</option>
              <option value="Caragumihan">Caragumihan</option>
              <option value="Batolinao">Batolinao</option>
              <option value="Danao">Danao</option>
              <option value="Sagrada">Sagrada</option>
              <option value="Ginitligan">Ginitligan</option>
              <option value="Guinsaanan">Guinsaanan</option>
              <option value="J.M. Alberto">J.M. Alberto</option>
              <option value="Macutal">Macutal</option>
              <option value="Moning">Moning</option>
              <option value="Nagbarorong">Nagbarorong</option>
              <option value="Osmeña">Osmeña</option>
              <option value="P. Teston">P. Teston</option>
              <option value="Paniquihan">Paniquihan</option>
              <option value="Eastern Poblacion">Eastern Poblacion</option>
              <option value="Puraran">Puraran</option>
              <option value="Putsan">Putsan</option>
              <option value="Quezon">Quezon</option>
              <option value="Rizal">Rizal</option>
              <option value="Salvacion">Salvacion</option>
              <option value="San Lorenzo">San Lorenzo</option>
              <option value="San Miguel">San Miguel</option>
              <option value="Santa Maria">Santa Maria</option>
              <option value="Tilod">Tilod</option>
              <option value="Western Poblacion">Western Poblacion</option>
            </select>

          </div>
        </div>

        <div id="message-container" class="message-container"></div>
        <div class="btn-container">
          <button type="submit" class="btn btn-custom btn-save">Save</button>
          <button type="reset" class="btn btn-custom btn-reset" id="resetBtn">
            Reset
          </button>
          <a
            href="./coordinator_management.php"
            class="btn btn-custom btn-cancel"
            onclick="cancelForm()">
            Cancel
          </a>
        </div>
      </form>
    </main>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/scripts/moa-owner-reg.js"></script>
  <script src="../../assets/scripts/sidebar.js"></script>
</body>

</html>