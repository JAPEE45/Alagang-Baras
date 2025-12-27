<?php
require_once '../../config/db.php';

// Fetch all health records with livestock and owner info
$stmt = $pdo->query("
  SELECT h.*, l.owner_name, l.species, l.breed, l.sex, o.firstName, o.surname
  FROM healthmonitoring h
  JOIN livestock l ON h.livestock_id = l.id
  LEFT JOIN owner o ON l.owner_id = o.id
  ORDER BY h.createdAt DESC
");
$healthRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Livestock Health Monitoring System</title>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css"
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
        <p>MAO PAGE</p>
      </div>

      <nav class="nav-menu">
        <div class="nav-item">
          <a href="./dashboard.php" class="nav-link ">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
          </a>
        </div>

        <div class="nav-item has-submenu ">
          <a href="#" class="nav-link submenu-toggle active">
            <i class="fas fa-heartbeat"></i>
            Health Monitoring
            <i class="fas fa-chevron-down submenu-icon"></i>
          </a>
          <div class="submenu">
            <a href="./new-health-record.php" class="submenu-link"
              >New Health Record</a
            >
            <a href="./livestock-health-monitoring.php" class="submenu-link active"
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

    <div class="main-content">
      <!-- Main Content -->

      <div class="content-area h-100">
        <div class="header-section">
          <h2 class="header-title">Livestock Health Monitoring</h2>
        </div>

        <!-- Animal Information Form -->
        <div class="form-section">
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="animalId" class="form-label">Animal ID:</label>
              <input
                type="text"
                class="form-control"
                id="animalId"
                placeholder="Enter Animal ID"
              />
            </div>
            <div class="col-md-4 mb-3">
              <label for="ownerId" class="form-label">Owner ID:</label>
              <input
                type="text"
                class="form-control"
                id="ownerId"
                placeholder="Enter Owner ID"
              />
            </div>
            <div class="col-md-4 mb-3">
              <label for="species" class="form-label">Species:</label>
              <select class="form-control" id="species">
                <option value="">Select Species</option>
                <option value="cattle">Cattle</option>
                <option value="pig">Pig</option>
                <option value="goat">Goat</option>
                <option value="sheep">Sheep</option>
                <option value="chicken">Chicken</option>
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
                  <th>Owner</th>
                  <th>Species</th>
                  <th>Breed</th>
                  <th>Diagnosis</th>
                  <th>Treatment</th>
                  <th>Vaccine</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if (count($healthRecords) > 0): ?>
                  <?php foreach ($healthRecords as $record): ?>
                    <tr data-id="<?php echo htmlspecialchars($record['id']); ?>">
                      <td><?php echo htmlspecialchars(date('m/d/Y', strtotime($record['createdAt']))); ?></td>
                      <td><?php echo htmlspecialchars($record['owner_name'] ?? ($record['firstName'] . ' ' . $record['surname'])); ?></td>
                      <td><?php echo htmlspecialchars($record['species']); ?></td>
                      <td><?php echo htmlspecialchars($record['breed']); ?></td>
                      <td><?php echo htmlspecialchars($record['diagnosis'] ?: '-'); ?></td>
                      <td><?php echo htmlspecialchars($record['treatment'] ?: '-'); ?></td>
                      <td><?php echo htmlspecialchars($record['vaccine_given'] ?: '-'); ?></td>
                      <td><span class="badge bg-<?php echo $record['livestock_status'] === 'Healthy' ? 'success' : 'warning'; ?>"><?php echo htmlspecialchars($record['livestock_status']); ?></span></td>
                      <td>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteHealthRecord(<?php echo $record['id']; ?>)">
                          <i class="fas fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="9" class="text-center">No health records found</td>
                  </tr>
                <?php endif; ?>
              </tbody>
                  <td>01/01/25</td>
                  <td>
                    <span class="status-badge status-deworming">Deworming</span>
                  </td>
                  <td>Albendazole</td>
                  <td>Dr. Reyes</td>
                  <td>
                    <button
                      class="btn btn-sm btn-outline-primary me-1"
                      onclick="editRecord(this)"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                    <button
                      class="btn btn-sm btn-outline-danger"
                      onclick="deleteRecord(this)"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
                <tr>
                  <td>01/01/25</td>
                  <td>
                    <span class="status-badge status-deworming">Deworming</span>
                  </td>
                  <td>Albendazole</td>
                  <td>Dr. Reyes</td>
                  <td>
                    <button
                      class="btn btn-sm btn-outline-primary me-1"
                      onclick="editRecord(this)"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                    <button
                      class="btn btn-sm btn-outline-danger"
                      onclick="deleteRecord(this)"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
                <tr>
                  <td>01/01/25</td>
                  <td>
                    <span class="status-badge status-deworming">Deworming</span>
                  </td>
                  <td>Albendazole</td>
                  <td>Dr. Reyes</td>
                  <td>
                    <button
                      class="btn btn-sm btn-outline-primary me-1"
                      onclick="editRecord(this)"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                    <button
                      class="btn btn-sm btn-outline-danger"
                      onclick="deleteRecord(this)"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
                <tr>
                  <td>01/01/25</td>
                  <td>
                    <span class="status-badge status-deworming">Deworming</span>
                  </td>
                  <td>Albendazole</td>
                  <td>Dr. Reyes</td>
                  <td>
                    <button
                      class="btn btn-sm btn-outline-primary me-1"
                      onclick="editRecord(this)"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                    <button
                      class="btn btn-sm btn-outline-danger"
                      onclick="deleteRecord(this)"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
                <tr>
                  <td>01/01/25</td>
                  <td>
                    <span class="status-badge status-deworming">Deworming</span>
                  </td>
                  <td>Albendazole</td>
                  <td>Dr. Reyes</td>
                  <td>
                    <button
                      class="btn btn-sm btn-outline-primary me-1"
                      onclick="editRecord(this)"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                    <button
                      class="btn btn-sm btn-outline-danger"
                      onclick="deleteRecord(this)"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
                <tr>
                  <td>01/01/25</td>
                  <td>
                    <span class="status-badge status-deworming">Deworming</span>
                  </td>
                  <td>Albendazole</td>
                  <td>Dr. Reyes</td>
                  <td>
                    <button
                      class="btn btn-sm btn-outline-primary me-1"
                      onclick="editRecord(this)"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                    <button
                      class="btn btn-sm btn-outline-danger"
                      onclick="deleteRecord(this)"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
                <tr>
                  <td>01/01/25</td>
                  <td>
                    <span class="status-badge status-deworming">Deworming</span>
                  </td>
                  <td>Albendazole</td>
                  <td>Dr. Reyes</td>
                  <td>
                    <button
                      class="btn btn-sm btn-outline-primary me-1"
                      onclick="editRecord(this)"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                    <button
                      class="btn btn-sm btn-outline-danger"
                      onclick="deleteRecord(this)"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
                <tr>
                  <td>02/15/25</td>
                  <td>
                    <span class="status-badge status-checkup">Check-up</span>
                  </td>
                  <td>N/A</td>
                  <td>Dr. Cruz</td>
                  <td>
                    <button
                      class="btn btn-sm btn-outline-primary me-1"
                      onclick="editRecord(this)"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                    <button
                      class="btn btn-sm btn-outline-danger"
                      onclick="deleteRecord(this)"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
                <tr>
                  <td>03/01/25</td>
                  <td>
                    <span class="status-badge status-vaccination"
                      >Vaccination</span
                    >
                  </td>
                  <td>FMD Vaccine</td>
                  <td>Dr. Santos</td>
                  <td>
                    <button
                      class="btn btn-sm btn-outline-primary me-1"
                      onclick="editRecord(this)"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                    <button
                      class="btn btn-sm btn-outline-danger"
                      onclick="deleteRecord(this)"
                    >
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
          <button
            class="btn btn-add-record"
            data-bs-toggle="modal"
            data-bs-target="#addRecordModal"
          >
            <i class="fas fa-plus me-2"></i>Add New Record
          </button>
        </div>
      </div>
    </div>

    <!-- Add New Record Modal -->
    <div
      class="modal fade"
      id="addRecordModal"
      tabindex="-1"
      aria-labelledby="addRecordModalLabel"
      aria-hidden="true"
    >
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
              aria-label="Close"
            ></button>
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
                    required
                  />
                </div>
                <div class="col-md-6 mb-3">
                  <label for="diagnosis" class="form-label">Diagnosis</label>
                  <select class="form-control" id="diagnosis" required>
                    <option value="">Select Diagnosis</option>
                    <option value="Deworming">Deworming</option>
                    <option value="Check-up">Check-up</option>
                    <option value="Vaccination">Vaccination</option>
                    <option value="Treatment">Treatment</option>
                    <option value="Surgery">Surgery</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="treatment" class="form-label">Treatment</label>
                  <input
                    type="text"
                    class="form-control"
                    id="treatment"
                    placeholder="Enter treatment"
                    required
                  />
                </div>
                <div class="col-md-6 mb-3">
                  <label for="vetName" class="form-label"
                    >Veterinarian Name</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    id="vetName"
                    placeholder="Enter vet name"
                    required
                  />
                </div>
              </div>
              <div class="mb-3">
                <label for="notes" class="form-label">Additional Notes</label>
                <textarea
                  class="form-control"
                  id="notes"
                  rows="3"
                  placeholder="Enter any additional notes..."
                ></textarea>
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
              type="button"
              class="btn btn-primary"
              onclick="addNewRecord()"
            >
            Save Record
            </button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

    <script src="../../assets/scripts/sidebar.js"></script>
    <script src="../../assets/scripts/vet-livestock-health-monitoring.js"></script>
    <script>
      async function deleteHealthRecord(id) {
        if (!confirm("Are you sure you want to delete this health record?")) {
          return;
        }

        try {
          const response = await fetch("../../helper/deleteHealthRecord.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: id })
          });

          const result = await response.json();

          if (result.status === "success") {
            alert("Health record deleted successfully!");
            location.reload();
          } else {
            alert("Error: " + result.message);
          }
        } catch (error) {
          console.error("Error deleting health record:", error);
          alert("Failed to delete health record. Please try again.");
        }
      }
    </script>
  </body>
</html>
