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

      <nav class="nav-menu">
        <div class="nav-item">
          <a href="./dashboard.php" class="nav-link active">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
          </a>
        </div>

        <div class="nav-item">
          <a href="./owner-list.php" class="nav-link">
            <i class="fas fa-sign-out-alt"></i>
            Owner Management
          </a>
        </div>

        <div class="nav-item">
          <a href="./livestock-health-monitoring.php" class="nav-link">
            <i class="fas fa-tachometer-alt"></i>
            Health Monitoring
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
                placeholder="Search..."
              />
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
                  <td>Anti Rabies</td>
                  <td>
                    <button
                      class="btn btn-sm btn-outline-primary me-1"
                      onclick="editRecord(this)"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Add New Record Button -->
        <!-- <div class="text-end mt-4">
          <a class="btn btn-add-record" href="./new-health-record.html">
            <i class="fas fa-plus me-2"></i>Add New Record
          </a>
        </div> -->
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
              <i class="fas fa-plus-circle me-2"></i>Health Record
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
                    disabled
                  />
                </div>
                <div class="col-md-6 mb-3">
                  <label for="ownerName" class="form-label">Owner Name</label>
                  <input type="text" class="form-control" id="ownerName" disabled/>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="animalType" class="form-label">Animal Name</label>
                  <input
                    type="text"
                    class="form-control"
                    id="animalType"
                    disabled
                  />
                </div>
                <div class="col-md-6 mb-3">
                  <label for="vaccineGiven" class="form-label"
                    >Vaccine Given</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    id="vaccineGiven"
                    placeholder="Enter vet name"
                    disabled
                  />
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="diagnosis" class="form-label">Diagnosis</label>
                  <input
                    type="text"
                    class="form-control"
                    id="diagnosis"
                    disabled
                  />
                </div>
                <div class="col-md-6 mb-3">
                  <label for="treatment" class="form-label">Treatment</label>
                  <input
                    type="text"
                    class="form-control"
                    id="treatment"
                    disabled
                  />
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 mb-3">
                  <label for="vaccineGiven" class="form-label"
                    >Vaccine Given</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    id="vaccineGiven"
                    disabled
                  />
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

    <script src="../../assets/scripts/sidebar.js"></script>
    <script>
      const searchOwnerInput = document.getElementById("search-owner");
      const vaccineSelect = document.getElementById("species");
      const table = document.getElementById("healthRecordsTable");
      const rows = table.getElementsByTagName("tr");

      function filterTable() {
        const ownerValue = searchOwnerInput.value.toLowerCase();
        const vaccineValue = vaccineSelect.value.toLowerCase();

        for (let i = 1; i < rows.length; i++) {
          const cells = rows[i].getElementsByTagName("td");
          if (cells.length === 0) continue; // skip header

          const ownerName = cells[1].innerText.toLowerCase();
          const vaccine = cells[3].innerText.toLowerCase();

          const matchesOwner = ownerName.includes(ownerValue);
          const matchesVaccine =
            vaccineValue === "" || vaccine === vaccineValue;

          // show or hide row
          rows[i].style.display = matchesOwner && matchesVaccine ? "" : "none";
        }
      }

      // Attach event listeners
      searchOwnerInput.addEventListener("input", filterTable);
      vaccineSelect.addEventListener("change", filterTable);
    </script>
    <script>
      document.getElementById("recordDate").valueAsDate = new Date();

      function editRecord(button) {
        const row = button.closest("tr");
        const cells = row.querySelectorAll("td");

        const date = cells[0].textContent;
        const ownerName = cells[1].textContent;
        const animalType = cells[2].textContent;
        const vaccineGiven = cells[3].textContent;

        const dateObj = new Date(date);
        document.getElementById("recordDate").value = dateObj
          .toISOString()
          .split("T")[0];
        document.getElementById("ownerName").value = ownerName;
        document.getElementById("animalType").value = animalType;
        document.getElementById("vaccineGiven").value = vaccineGiven;

        const modal = new bootstrap.Modal(
          document.getElementById("addRecordModal")
        );
        modal.show();
      }

      function updateRecord(rowIndex) {
        const form = document.getElementById("addRecordForm");

        if (!form.checkValidity()) {
          form.reportValidity();
          return;
        }

        const date = document.getElementById("recordDate").value;
        const ownerName = document.getElementById("ownerName").value.trim();
        const animalType = document.getElementById("animalType").value;
        const vaccineGiven = document.getElementById("vaccineGiven").value;

        const formattedDate = new Date(date).toLocaleDateString("en-US", {
          month: "2-digit",
          day: "2-digit",
          year: "2-digit",
        });

        const row = document.querySelector("#healthRecordsTable tbody").rows[
          rowIndex - 1
        ];
        row.cells[0].textContent = formattedDate;
        row.cells[1].innerHTML = `<span>${ownerName}</span>`;
        row.cells[2].textContent = animalType;
        row.cells[3].textContent = vaccineGiven;

        resetModal();

        const modal = bootstrap.Modal.getInstance(
          document.getElementById("addRecordModal")
        );
        modal.hide();

        showNotification("Health record updated successfully!", "success");
      }

      function showNotification(message, type) {
        const alertDiv = document.createElement("div");
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText =
          "top: 20px; right: 20px; z-index: 9999; min-width: 300px;";
        alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

        document.body.appendChild(alertDiv);

        setTimeout(() => {
          alertDiv.remove();
        }, 3000);
      }

      document
        .getElementById("addRecordModal")
        .addEventListener("hidden.bs.modal", function () {
          resetModal();
        });

      const style = document.createElement("style");
      style.textContent = `
            @keyframes fadeOut {
                from { opacity: 1; transform: translateX(0); }
                to { opacity: 0; transform: translateX(-20px); }
            }
            
            .new-row {
                animation: slideIn 0.3s ease-out;
            }
            
            @keyframes slideIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        `;
      document.head.appendChild(style);
    </script>
  </body>
</html>
