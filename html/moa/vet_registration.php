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
  $edit_vet = null;
  $user_id = null;

  // Support edit via user_id or legacy uid
  if ((isset($_GET['user_id']) && is_numeric($_GET['user_id'])) || (isset($_GET['uid']) && is_numeric($_GET['uid']))) {
    $user_id = isset($_GET['user_id']) && is_numeric($_GET['user_id']) ? (int)$_GET['user_id'] : (int)$_GET['uid'];
    $stmt = $pdo->prepare('SELECT v.vet_id, v.user_id, v.prc_number, v.specialization, v.fullname FROM veterinarians v JOIN users u ON v.user_id = u.user_id WHERE v.user_id = ? LIMIT 1');
    $stmt->execute([$user_id]);
    $edit_vet = $stmt->fetch();
    if ($edit_vet) {
      $editing = true;
      $prc = $edit_vet['prc_number'];
      $fullname = $edit_vet['fullname'];
      $specialization = $edit_vet['specialization'];
    }
  }

  // Handle POST
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user_id']) && is_numeric($_POST['user_id'])) {
      $user_id = (int)$_POST['user_id'];
      $editing = true;
    }
    $prc = isset($_POST['prcNum']) ? trim($_POST['prcNum']) : '';
    $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
    $specialization = isset($_POST['specialization']) ? trim($_POST['specialization']) : '';

    $errors = [];
    if ($prc === '') $errors[] = 'PRC number is required.';
    if ($fullname === '') $errors[] = 'Full name is required.';

    if (empty($errors)) {
      try {
        if ($editing && $user_id) {
          // update veterinarians table
          $stmt = $pdo->prepare('UPDATE veterinarians SET prc_number = ?, specialization = ?, fullname = ? WHERE user_id = ?');
          $stmt->execute([$prc, $specialization, $fullname, $user_id]);
          $message = '<div class="alert alert-success">Veterinarian updated successfully.</div>';
        } else {
          // create user and veterinarian
          $usernameBase = preg_replace('/[^a-z0-9]/i','',strtolower($fullname)) ?: 'vet';
          $username = '';
          $attempt = 0;
          do {
            $username = $usernameBase . rand(100,999);
            $s = $pdo->prepare('SELECT user_id FROM users WHERE username = ?');
            $s->execute([$username]);
            $exists = $s->fetch();
            $attempt++;
          } while ($exists && $attempt < 20);

          $tempPass = 'Vet' . rand(1000,9999);

          $pdo->beginTransaction();
          $s = $pdo->prepare('INSERT INTO users (username, password_, role, status) VALUES (?, ?, "Vet", "Active")');
          $s->execute([$username, $tempPass]);
          $user_id = $pdo->lastInsertId();

          $v = $pdo->prepare('INSERT INTO veterinarians (user_id, prc_number, specialization, fullname) VALUES (?, ?, ?, ?)');
          $v->execute([$user_id, $prc, $specialization, $fullname]);

          $pdo->commit();
          $message = '<div class="alert alert-success">Veterinarian registered. Username: ' . htmlspecialchars($username) . '. Temporary password: ' . htmlspecialchars($tempPass) . '</div>';
        }
      } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        $message = '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
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
          <a href="./coordinator_management.php" class="submenu-link ">Coordinator</a>
          <a href="./vet_management.php" class="submenu-link active">Veterinarian</a>
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
        <i class="fas fa-clipboard-list me-3"></i>Veterinarian Registration
      </h2>


      <form id="livestockForm" class="livestock-form fade-in" method="post" action="">
        <?php if (!empty($message)) echo $message; ?>
        <?php if (!empty($editing) && !empty($user_id)) { ?>
          <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>" />
        <?php } ?>
        <div class="row">
          <div class="col-md-6 form-row">
            <label for="prcNum" class="form-label">PRC NUMBER:</label>
            <input
              type="text"
              class="form-control"
              id="prcNum"
              name="prcNum"
              value="<?php echo isset($prc) ? htmlspecialchars($prc) : ''; ?>" />
          </div>

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
        </div>

        <div class="row">

          <div class="col-md-12 form-row">
            <label for="specialization" class="form-label">Specialization:</label>
            <input
              type="text"
              class="form-control"
              id="specialization"
              name="specialization"
              placeholder="Enter specialization"
              value="<?php echo isset($specialization) ? htmlspecialchars($specialization) : ''; ?>" />
          </div>
        </div>



        <div id="message-container" class="message-container"></div>
        <div class="btn-container">
          <button type="submit" class="btn btn-custom btn-save">Save</button>
          <button type="reset" class="btn btn-custom btn-reset" id="resetBtn">
            Reset
          </button>
          <a
          href="./vet_management.php"
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