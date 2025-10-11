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
      href="../../assets/styles/layouts/veterinarian/dashboard.css"
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

      <div class="user-profile">
        <div class="avatar">
          <img
            src="../../assets/images/blank-profile-picture-973460_1280.png"
            alt=""
          />
          <input type="file" id="imageInput" accept="image/*" />
          <label for="imageInput">
            <i class="fa-solid fa-plus"></i>
          </label>
        </div>
        <h6>
          <?php echo ($vetName && isset($vetName['fullname'])) ? htmlspecialchars($vetName['fullname']) : 'Unknown'; ?>
        </h6>
        <small>Veterinarian</small>
      </div>

      <nav class="nav-menu">
        <div class="nav-item">
          <a href="#" class="nav-link active">
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
          <a href="#" class="nav-link">
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
            <div class="stats-number">10</div>
            <div class="stats-label">Animals Assigned</div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="stats-card orange">
            <div class="stats-number">8</div>
            <div class="stats-label">Healthy Animals</div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="stats-card green">
            <div class="stats-number">2</div>
            <div class="stats-label">Sick</div>
          </div>
        </div>
      </div>

      <div class="table-container">
        <h5>Animal Health Overview</h5>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>ANIMAL ID</th>
                <th>SPECIES</th>
                <th>BARANGAY</th>
                <th>HEALTH STATUS</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>01</strong></td>
                <td>Pig</td>
                <td>Cabcab</td>
                <td>
                  <span class="status-badge status-healthy">HEALTHY</span>
                </td>
              </tr>
              <tr>
                <td><strong>02</strong></td>
                <td>Carabao</td>
                <td>Poblacion</td>
                <td><span class="status-badge status-sick">SICK</span></td>
              </tr>
              <tr>
                <td><strong>03</strong></td>
                <td>Chicken</td>
                <td>San Jose</td>
                <td>
                  <span class="status-badge status-healthy">HEALTHY</span>
                </td>
              </tr>
              <tr>
                <td><strong>04</strong></td>
                <td>Goat</td>
                <td>Maligaya</td>
                <td>
                  <span class="status-badge status-healthy">HEALTHY</span>
                </td>
              </tr>
              <tr>
                <td><strong>05</strong></td>
                <td>Cow</td>
                <td>Riverside</td>
                <td><span class="status-badge status-sick">SICK</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="action-buttons">
        <h2 class="quick-action-header">Quick Actions</h2>

        <button
          class="btn btn-custom btn-update"
          data-bs-toggle="modal"
          data-bs-target="#updateHealthModal"
        >
          <i class="fas fa-plus"></i>
          Update Health Record
        </button>
        <button
          class="btn btn-custom btn-treatment"
          data-bs-toggle="modal"
          data-bs-target="#recordTreatmentModal"
        >
          <i class="fas fa-pen"></i>
          Record Treatment
        </button>
      </div>
    </div>

    <div class="modal fade" id="updateHealthModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              <i class="fas fa-plus me-2"></i>
              Update Health Record
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
            ></button>
          </div>
          <div class="modal-body">
            <form id="healthRecordForm">
              <div class="mb-3">
                <label for="animalId" class="form-label">Animal ID</label>
                <select class="form-select" id="animalId" required>
                  <option value="">Select Animal</option>
                  <option value="01">01 - Pig (Cabcab)</option>
                  <option value="02">02 - Carabao (Poblacion)</option>
                  <option value="03">03 - Chicken (San Jose)</option>
                  <option value="04">04 - Goat (Maligaya)</option>
                  <option value="05">05 - Cow (Riverside)</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="healthStatus" class="form-label"
                  >Health Status</label
                >
                <select class="form-select" id="healthStatus" required>
                  <option value="">Select Status</option>
                  <option value="healthy">Healthy</option>
                  <option value="sick">Sick</option>
                  <option value="under-observation">Under Observation</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="symptoms" class="form-label">Symptoms/Notes</label>
                <textarea
                  class="form-control"
                  id="symptoms"
                  rows="3"
                  placeholder="Enter any symptoms or notes..."
                ></textarea>
              </div>
              <div class="mb-3">
                <label for="checkupDate" class="form-label">Checkup Date</label>
                <input
                  type="date"
                  class="form-control"
                  id="checkupDate"
                  required
                />
              </div>
              <div class="mb-3">
                <label for="temperature" class="form-label"
                  >Temperature (°C)</label
                >
                <input
                  type="number"
                  class="form-control"
                  id="temperature"
                  step="0.1"
                  placeholder="38.5"
                />
              </div>
              <div class="mb-3">
                <label for="weight" class="form-label">Weight (kg)</label>
                <input
                  type="number"
                  class="form-control"
                  id="weight"
                  step="0.1"
                  placeholder="250.5"
                />
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="btn btn-primary"
              form="healthRecordForm"
              style="
                background-color: var(--primary-green);
                border-color: var(--primary-green);
              "
            >
              Update Record
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="recordTreatmentModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #ff9800">
            <h5 class="modal-title">
              <i class="fas fa-pen me-2"></i>
              Record Treatment
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
            ></button>
          </div>
          <div class="modal-body">
            <form id="treatmentForm">
              <div class="mb-3">
                <label for="treatmentAnimalId" class="form-label"
                  >Animal ID</label
                >
                <select class="form-select" id="treatmentAnimalId" required>
                  <option value="">Select Animal</option>
                  <option value="01">01 - Pig (Cabcab)</option>
                  <option value="02">02 - Carabao (Poblacion)</option>
                  <option value="03">03 - Chicken (San Jose)</option>
                  <option value="04">04 - Goat (Maligaya)</option>
                  <option value="05">05 - Cow (Riverside)</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="treatmentType" class="form-label"
                  >Treatment Type</label
                >
                <select class="form-select" id="treatmentType" required>
                  <option value="">Select Treatment Type</option>
                  <option value="vaccination">Vaccination</option>
                  <option value="medication">Medication</option>
                  <option value="surgery">Surgery</option>
                  <option value="routine-checkup">Routine Checkup</option>
                  <option value="emergency">Emergency Treatment</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="treatmentDate" class="form-label"
                  >Treatment Date</label
                >
                <input
                  type="date"
                  class="form-control"
                  id="treatmentDate"
                  required
                />
              </div>
              <div class="mb-3">
                <label for="medication" class="form-label"
                  >Medication/Vaccine</label
                >
                <input
                  type="text"
                  class="form-control"
                  id="medication"
                  placeholder="Enter medication or vaccine name"
                />
              </div>
              <div class="mb-3">
                <label for="dosage" class="form-label">Dosage</label>
                <input
                  type="text"
                  class="form-control"
                  id="dosage"
                  placeholder="Enter dosage"
                />
              </div>
              <div class="mb-3">
                <label for="treatmentNotes" class="form-label"
                  >Treatment Notes</label
                >
                <textarea
                  class="form-control"
                  id="treatmentNotes"
                  rows="4"
                  placeholder="Enter detailed treatment notes..."
                ></textarea>
              </div>
              <div class="mb-3">
                <label for="nextCheckup" class="form-label"
                  >Next Checkup Date</label
                >
                <input type="date" class="form-control" id="nextCheckup" />
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Cancel
            </button>
            <button type="submit" class="btn btn-warning" form="treatmentForm">
              Record Treatment
            </button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script src="../../assets/scripts/sidebar.js"></script>
    <script src="../../assets/scripts/vet-dashboard.js"></script>
  </body>
</html>
