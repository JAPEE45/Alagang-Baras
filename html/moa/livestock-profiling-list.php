<?php
include_once '../../config/db.php';

// Get all livestock with owner information
$stmt = $pdo->prepare("
  SELECT l.*, o.firstName, o.surname, o.middleName,
  TIMESTAMPDIFF(YEAR, l.dob, CURDATE()) as age_years
  FROM livestock l
  LEFT JOIN owner o ON l.owner_id = o.id
  ORDER BY l.id DESC
");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
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
    href="../../assets/styles/layouts/veterinarian/livestock-health-monitoring.css" />
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
          <a href="./dashboard.php" class="nav-link">
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
          <a href="./owner-list.php" class="nav-link">
            <i class="fas fa-sign-out-alt"></i>
            User Management
          </a>
        </div>
        <div class="nav-item">
          <a href="./livestock-profiling-list.php" class="nav-link active">
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
    <!-- Main Content -->
    <div class="main-content">
      <!-- Success/Error Messages -->
      <div id="alertContainer"></div>

      <!-- Header -->
      <div class="header-section">
        <h1><i class="fas fa-clipboard-list"></i> Livestock List</h1>
        <a href="./add_animals.php" class="btn btn-add">
          <i class="fas fa-plus"></i> Add Livestock
        </a>
      </div>

      <!-- Search Box -->
      <div class="search-box">
        <input
          type="text"
          id="searchInput"
          class="form-control"
          placeholder="Search by owner, species, breed..."
        />
      </div>

      <!-- Table -->
      <div class="table-container">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>OWNER NAME</th>
              <th>SPECIES</th>
              <th>BREED</th>
              <th>AGE</th>
              <th>SEX</th>
              <th>QR CODE</th>
              <th>ACTIONS</th>
            </tr>
          </thead>
          <tbody id="livestockTableBody">
            <?php if (count($result) > 0): ?>
              <?php foreach ($result as $livestock): ?>
                <tr data-id="<?php echo htmlspecialchars($livestock['id']); ?>">
                  <td><?php echo htmlspecialchars($livestock['owner_name'] ?? ($livestock['firstName'] . ' ' . $livestock['surname'])); ?></td>
                  <td><?php echo htmlspecialchars($livestock['species']); ?></td>
                  <td><?php echo htmlspecialchars($livestock['breed']); ?></td>
                  <td><?php echo htmlspecialchars($livestock['age_years'] ?? 'N/A'); ?> yrs</td>
                  <td><?php echo htmlspecialchars($livestock['sex']); ?></td>
                  <td>
                    <?php if ($livestock['qr_code']): ?>
                      <a href="../../<?php echo htmlspecialchars($livestock['qr_code']); ?>" target="_blank" class="btn btn-sm btn-outline-info">
                        <i class="fas fa-qrcode"></i> View
                      </a>
                    <?php else: ?>
                      <span class="text-muted">No QR</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteLivestock(<?php echo $livestock['id']; ?>)">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="7" class="text-center text-muted py-5">
                  No livestock data available.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Add Livestock Modal -->
    <div class="modal fade" id="addLivestockModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              <i class="fas fa-plus-circle"></i> Add Livestock
            </h5>
            <button
              type="button"
              class="btn-close btn-close-white"
              data-bs-dismiss="modal"
            ></button>
          </div>
          <div class="modal-body">
            <form id="addLivestockForm">
              <div class="mb-3">
                <label class="form-label">Owner Name:</label>
                <input
                  type="text"
                  class="form-control"
                  id="ownerName"
                  placeholder="Search owner..."
                  required
                />
                <div id="ownerDropdown"></div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Species:</label>
                  <select class="form-select" id="species" required>
                    <option value="">-- Please Select --</option>
                    <option value="Cattle">Cattle</option>
                    <option value="Swine">Swine</option>
                    <option value="Goat">Goat</option>
                    <option value="Sheep">Sheep</option>
                    <option value="Poultry">Poultry</option>
                    <option value="Carabao">Carabao</option>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Breed:</label>
                  <input type="text" class="form-control" id="breed" required />
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Sex:</label>
                  <select class="form-select" id="sex" required>
                    <option value="">-- Please Select --</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Date of Birth:</label>
                  <input type="date" class="form-control" id="dob" required />
                </div>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- QR Code Modal -->
    <div class="modal fade" id="qrModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">QR Code</h5>
            <button
              type="button"
              class="btn-close btn-close-white"
              data-bs-dismiss="modal"
            ></button>
          </div>
          <div class="modal-body text-center">
            <img id="qrModalImage" class="qr-modal-img" alt="QR Code" />
            <div class="mt-3">
              <button class="btn btn-primary" onclick="downloadQR()">
                <i class="fas fa-download"></i> Download QR Code
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        
      const owners = [
        { id: 1, ownerName: "John Doe" },
        { id: 2, ownerName: "Jane Smith" },
        { id: 3, ownerName: "Carlos Reyes" },
        { id: 4, ownerName: "Anna Dela Cruz" },
        { id: 5, ownerName: "Mark Santos" },
        { id: 6, ownerName: "Maria Lopez" }
      ];

      const ownerInput = document.getElementById("ownerName");
      const dropdown = document.getElementById("ownerDropdown");

      // Apply dropdown base style from JS
      dropdown.style.position = "absolute";
      dropdown.style.background = "white";
      dropdown.style.border = "1px solid #ccc";
      dropdown.style.width = "100%";
      dropdown.style.maxHeight = "180px";
      dropdown.style.overflowY = "auto";
      dropdown.style.zIndex = "1000";
      dropdown.style.display = "none";

      ownerInput.addEventListener("input", function () {
        const query = this.value.toLowerCase().trim();
        dropdown.innerHTML = "";

        if (!query) {
          dropdown.style.display = "none";
          return;
        }

        const filtered = owners.filter(o =>
          o.ownerName.toLowerCase().includes(query)
        );

        if (filtered.length === 0) {
          dropdown.style.display = "none";
          return;
        }

        filtered.forEach(o => {
          const item = document.createElement("div");
          item.textContent = o.ownerName;
          item.classList.add("p-2"); // Bootstrap padding
          item.style.cursor = "pointer";
          item.addEventListener("mouseenter", () => item.style.background = "#f1f1f1");
          item.addEventListener("mouseleave", () => item.style.background = "white");
          item.addEventListener("click", () => {
            ownerInput.value = o.ownerName;
            dropdown.style.display = "none";
            console.log("Selected ID:", o.id);
          });
          dropdown.appendChild(item);
        });

        dropdown.style.display = "block";
      });

      // Hide dropdown when clicking outside
      document.addEventListener("click", (e) => {
        if (!e.target.closest("#ownerName")) {
          dropdown.style.display = "none";
        }
      });
    </script>
    <script>
      let livestockData = [];
      let currentQRData = "";

      // Calculate age from date of birth
      function calculateAge(dob) {
        const birthDate = new Date(dob);
        const today = new Date();
        let years = today.getFullYear() - birthDate.getFullYear();
        let months = today.getMonth() - birthDate.getMonth();

        if (months < 0) {
          years--;
          months += 12;
        }

        if (years > 0) {
          return `${years} year${years > 1 ? "s" : ""}`;
        } else {
          return `${months} month${months > 1 ? "s" : ""}`;
        }
      }

      // Generate QR Code
      function generateQRCode(data) {
        const qrData = JSON.stringify(data);
        const qrDiv = document.createElement("div");
        qrDiv.style.display = "none";
        document.body.appendChild(qrDiv);

        const qr = new QRCode(qrDiv, {
          text: qrData,
          width: 200,
          height: 200,
          colorDark: "#115d33",
          colorLight: "#ffffff",
          correctLevel: QRCode.CorrectLevel.H,
        });

        setTimeout(() => {
          const img = qrDiv.querySelector("img");
          const qrCodeURL = img.src;
          document.body.removeChild(qrDiv);
          data.qrCode = qrCodeURL;
          renderTable();
        }, 100);
      }

      // Show alert message
      function showAlert(message, type = "success") {
        const alertContainer = document.getElementById("alertContainer");
        const alert = document.createElement("div");
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
        alertContainer.appendChild(alert);

        setTimeout(() => {
          alert.remove();
        }, 5000);
      }

      // Render table
      function renderTable(data = livestockData) {
        const tbody = document.getElementById("livestockTableBody");

        if (data.length === 0) {
          tbody.innerHTML =
            '<tr><td colspan="7" class="text-center text-muted py-5">No livestock data available.</td></tr>';
          return;
        }

        tbody.innerHTML = data
          .map(
            (item, index) => `
                <tr>
                    <td>${item.ownerName}</td>
                    <td>${item.species}</td>
                    <td>${item.breed}</td>
                    <td>${item.age}</td>
                    <td>${item.sex}</td>
                    <td class="qr-code-cell">
                        ${
                          item.qrCode
                            ? `<img src="${item.qrCode}" class="qr-code-img" onclick="showQRModal(${index})" alt="QR Code">`
                            : "Generating..."
                        }
                    </td>
                    <td>
                        <button class="action-btn btn-edit" 
                        data-bs-toggle="modal"
          data-bs-target="#addLivestockModal"
          onclick="editLivestock(${index})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            `
          )
          .join("");
      }

      // Show QR Modal
      function showQRModal(index) {
        const data = livestockData[index];
        currentQRData = data.qrCode;
        document.getElementById("qrModalImage").src = data.qrCode;
        new bootstrap.Modal(document.getElementById("qrModal")).show();
      }

      // Download QR Code
      function downloadQR() {
        const link = document.createElement("a");
        link.download = "livestock_qr_code.png";
        link.href = currentQRData;
        link.click();
      }

      // Add livestock
      document
        .getElementById("addLivestockForm")
        .addEventListener("submit", function (e) {
          e.preventDefault();

          const newLivestock = {
            id: Date.now(),
            ownerName: document.getElementById("ownerName").value,
            species: document.getElementById("species").value,
            breed: document.getElementById("breed").value,
            sex: document.getElementById("sex").value,
            dob: document.getElementById("dob").value,
            age: calculateAge(document.getElementById("dob").value),
            qrCode: null,
          };

          livestockData.push(newLivestock);
          generateQRCode(newLivestock);

          this.reset();
          showAlert("Livestock added successfully!");
        });

      // Delete livestock
      async function deleteLivestock(id) {
        if (!confirm("Are you sure you want to delete this livestock?")) {
          return;
        }
        
        try {
          const response = await fetch("../../helper/deleteLiveStock.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: id })
          });
          
          const result = await response.json();
          
          if (result.status === "success") {
            alert("Livestock deleted successfully!");
            location.reload(); // Reload page to show updated list
          } else {
            alert("Error: " + result.message);
          }
        } catch (error) {
          console.error("Error:", error);
          alert("Failed to delete livestock");
        }
      }

      // Search functionality
      document
        .getElementById("searchInput")
        .addEventListener("input", function (e) {
          const searchTerm = e.target.value.toLowerCase();
          const filtered = livestockData.filter(
            (item) =>
              item.ownerName.toLowerCase().includes(searchTerm) ||
              item.species.toLowerCase().includes(searchTerm) ||
              item.breed.toLowerCase().includes(searchTerm) ||
              item.sex.toLowerCase().includes(searchTerm)
          );
          renderTable(filtered);
        });

      // Edit livestock (placeholder)
      function editLivestock(index) {}

      // Initialize
      renderTable();
    </script>
    <script src="../../assets/scripts/sidebar.js"></script>
  </body>
</html>