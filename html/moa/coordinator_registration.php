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
<?php

require_once __DIR__ . '/../../config/db.php';

$message = '';
$editing = false;
$edit_user = null;
$user_id = null;

// If a user_id is provided via GET, load existing coordinator for editing
// Accept both 'user_id' and legacy 'uid' as query parameters
if ((isset($_GET['user_id']) && is_numeric($_GET['user_id'])) || (isset($_GET['uid']) && is_numeric($_GET['uid']))) {
  $user_id = isset($_GET['user_id']) && is_numeric($_GET['user_id']) ? (int)$_GET['user_id'] : (int)$_GET['uid'];
  // ensure coordinators.contact_number column exists (safe ALTER)
  try {
    $colStmt = $pdo->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'coordinators' AND COLUMN_NAME = 'contact_number' LIMIT 1");
    $colStmt->execute();
    $colExists = $colStmt->fetch();
    if (!$colExists) {
      $pdo->exec("ALTER TABLE coordinators ADD COLUMN contact_number VARCHAR(50) NULL AFTER fullname");
    }
  } catch (Exception $e) {
    // if ALTER fails, continue — code will still try to work without contact_number
  }

  $stmt = $pdo->prepare('SELECT u.user_id,u.username,u.password_,c.fullname,c.barangay_assigned, c.contact_number FROM users u JOIN coordinators c ON u.user_id = c.user_id WHERE u.user_id = ? LIMIT 1');
  $stmt->execute([$user_id]);
  $edit_user = $stmt->fetch();
  if ($edit_user) {
    $editing = true;
    // Prefill form variables
    $fullname = $edit_user['fullname'];
    $barangay = $edit_user['barangay_assigned'];
    // Prefill contact: use coordinators.contact_number only (do not show users.password_ here)
    $contactNum = '';
    if (!empty($edit_user['contact_number'])) {
      $contactNum = trim($edit_user['contact_number']);
    }
    // If contact_number is not set but password_ looks like a phone number, mark for migration
    $migrate_contact_from_password = false;
    if ((empty($edit_user['contact_number']) || $edit_user['contact_number'] === null || $edit_user['contact_number'] === '') && !empty($edit_user['password_'])) {
      // simple heuristic: phone numbers are digits and may include +, spaces, hyphens
      if (preg_match('/^[0-9\s\-\+]+$/', $edit_user['password_'])) {
        $migrate_contact_from_password = true;
      }
    }
  }
}

// Only handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // If editing, capture user_id from POST
  if (isset($_POST['user_id']) && is_numeric($_POST['user_id'])) {
    $user_id = (int)$_POST['user_id'];
    $editing = true;
  }
  // Collect and sanitize inputs
  // Collect and sanitize inputs
  $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
  $contactNum = isset($_POST['contactNum']) ? trim($_POST['contactNum']) : '';
  $barangay = isset($_POST['barangay']) ? trim($_POST['barangay']) : '';

  $errors = [];
  if ($fullname === '') {
    $errors[] = 'Full name is required.';
  }
  if ($barangay === '' || stripos($barangay, '-- Select') !== false) {
    $errors[] = 'Barangay is required.';
  }

  
  if (mb_strlen($fullname) > 20) {
    $fullname_db = mb_substr($fullname, 0, 20);
  } else {
    $fullname_db = $fullname;
  }

  if (empty($errors)) {
    try {
  
      // If editing, update existing records; otherwise create new user+coordinator
    if ($editing && $user_id) {
      // Update coordinator info; do NOT overwrite users.password_ with contact number unless migrating
      $pdo->beginTransaction();
      // Check if contact_number column exists
      $colExists = false;
      try {
        $colStmt = $pdo->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'coordinators' AND COLUMN_NAME = 'contact_number' LIMIT 1");
        $colStmt->execute();
        $colExists = (bool)$colStmt->fetch();
      } catch (Exception $e) {
        $colExists = false;
      }

      // Determine value to store in contact_number: prefer posted contactNum if provided; otherwise if migrating, use old password_
      $postedContact = isset($_POST['contactNum']) ? trim($_POST['contactNum']) : '';
      $contactToStore = $postedContact !== '' ? $postedContact : (isset($contactNum) ? $contactNum : '');

      // If migrating (old data stored password_ as contact), we will copy it into contact_number and reset user's password to a new temp value
      $migration_notice = '';
      if (!empty($migrate_contact_from_password) && $colExists) {
        // old contact was in $edit_user['password_']
        $oldContact = $edit_user['password_'];
        $contactToStore = $oldContact;
        // generate a new temporary password for the user
        $newTempPassword = 'Coord' . rand(1000, 9999);
        // update users.password_ to new temporary password
        $uStmt = $pdo->prepare('UPDATE users SET password_ = ? WHERE user_id = ?');
        $uStmt->execute([$newTempPassword, $user_id]);
        $migration_notice = ' New temporary password: ' . htmlspecialchars($newTempPassword) . '.';
      }

      if ($colExists) {
        $stmt = $pdo->prepare('UPDATE coordinators SET fullname = ?, barangay_assigned = ?, contact_number = ? WHERE user_id = ?');
        $stmt->execute([$fullname_db, $barangay, $contactToStore, $user_id]);
      } else {
        $stmt = $pdo->prepare('UPDATE coordinators SET fullname = ?, barangay_assigned = ? WHERE user_id = ?');
        $stmt->execute([$fullname_db, $barangay, $user_id]);
      }

      $pdo->commit();
      $message = '<div class="alert alert-success">Coordinator updated successfully.' . $migration_notice . '</div>';
      } else {
          $base = preg_replace('/[^a-z0-9]/i', '', strtolower($fullname_db));
          if ($base === '') $base = 'coord';
          $username = '';
          $attempt = 0;
          do {
            $username = $base . rand(100, 999);
            $stmt = $pdo->prepare('SELECT user_id FROM users WHERE username = ?');
            $stmt->execute([$username]);
            $exists = $stmt->fetch();
            $attempt++;
          } while ($exists && $attempt < 20);

      $plainPassword = 'Coord' . rand(1000, 9999);
      // Store plain password as requested (note: insecure, consider hashing in production)
      $passwordHash = $plainPassword;

      // Begin transaction
      $pdo->beginTransaction();

      // Insert into users
      $stmt = $pdo->prepare('INSERT INTO users (username, password_, role, status) VALUES (?, ?, "Coordinator", "Active")');
      $stmt->execute([$username, $passwordHash]);
      $user_id = $pdo->lastInsertId();

      // Insert into coordinators (include contact_number if column exists)
      $position_role = NULL;
      // Check for contact_number column
      $colExists = false;
      try {
        $colStmt = $pdo->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'coordinators' AND COLUMN_NAME = 'contact_number' LIMIT 1");
        $colStmt->execute();
        $colExists = (bool)$colStmt->fetch();
      } catch (Exception $e) {
        $colExists = false;
      }

      if ($colExists) {
        $stmt = $pdo->prepare('INSERT INTO coordinators (user_id, position_role, barangay_assigned, fullname, contact_number) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$user_id, $position_role, $barangay, $fullname_db, $contactNum]);
      } else {
        $stmt = $pdo->prepare('INSERT INTO coordinators (user_id, position_role, barangay_assigned, fullname) VALUES (?, ?, ?, ?)');
        $stmt->execute([$user_id, $position_role, $barangay, $fullname_db]);
      }

      $pdo->commit();

      $message = '<div class="alert alert-success">Coordinator registered successfully. Username: ' . htmlspecialchars($username) . '. Temporary password: ' . htmlspecialchars($plainPassword) . '</div>';
      }
    } catch (Exception $e) {
      if ($pdo->inTransaction()) $pdo->rollBack();
      $message = '<div class="alert alert-danger">Registration failed: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
  } else {
    $message = '<div class="alert alert-danger"><ul>' . implode('', array_map(function($e){ return '<li>' . htmlspecialchars($e) . '</li>'; }, $errors)) . '</ul></div>';
  }
}
?>
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


  <form id="livestockForm" class="livestock-form fade-in" method="post" action="">
        <?php if (!empty($message)) echo $message; ?>
        <?php if (!empty($editing) && !empty($user_id)) { ?>
          <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>" />
        <?php } ?>
        <div class="row">
          <div class="col-md-6 form-row">
            <label for="fullName" class="form-label">Full Name:</label>
            <input
              type="text"
              class="form-control"
              id="fullName"
              name="fullname"
              placeholder="Enter Full Name"
              value="<?php echo isset($fullname) ? htmlspecialchars($fullname) : ''; ?>" />
          </div>

          <div class="col-md-6 form-row">
            <label for="userID" class="form-label">Contact No:</label>
            <input
              type="text"
              class="form-control"
              id="contactNum"
              name="contactNum"
              value="<?php echo isset($contactNum) ? htmlspecialchars($contactNum) : ''; ?>" />
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

            <script>
              (function(){
                var selected = <?php echo isset($barangay) ? json_encode($barangay) : 'null'; ?>;
                if (selected) {
                  var sel = document.getElementById('barangay');
                  for (var i=0;i<sel.options.length;i++) {
                    if (sel.options[i].value === selected) { sel.selectedIndex = i; break; }
                  }
                }
              })();
            </script>

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