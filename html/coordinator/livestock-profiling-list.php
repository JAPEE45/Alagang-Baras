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
        <p>COORDINATOR PAGE</p>
      </div>

      <nav class="nav-menu">
        <div class="nav-item">
          <a href="./dashboard.php" class="nav-link ">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
          </a>
        </div>

        <div class="nav-item">
          <a href="./owner-registration.php" class="nav-link">
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
        <button
          class="btn btn-add"
          data-bs-toggle="modal"
          data-bs-target="#addLivestockModal"
        >
          <i class="fas fa-plus"></i> Add Livestock
        </button>
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
       <!-- Accordion -->
      <div class="accordion" id="ownersAccordion">
          <!-- Owner 1 -->
          <div class="accordion-item mb-3 shadow-sm">
              <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                          data-bs-target="#owner1">
                      <div class="d-flex align-items-center">
                          <div class="owner-avatar me-3">J</div>
                          <div>
                              <h5 class="mb-0">Juan Cruz Dela</h5>
                              <small class="text-muted">2 livestock</small>
                          </div>
                      </div>
                  </button>
              </h2>
              <div id="owner1" class="accordion-collapse collapse" data-bs-parent="#ownersAccordion">
                  <div class="accordion-body bg-light">
                      <div class="table-responsive">
                          <table class="table table-hover bg-white">
                              <thead>
                                  <tr>
                                      <th>Species</th>
                                      <th>Breed</th>
                                      <th>Sex</th>
                                      <th>QR Code</th>
                                      <th>Actions</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <tr>
                                      <td>Swine</td>
                                      <td>s</td>
                                      <td>Female</td>
                                      <td>
                                          <button class="btn btn-sm btn-dark" onclick="showQRModal('QR001')">
                                              View QR
                                          </button>
                                      </td>
                                      <td>
                                          <button class="btn btn-sm btn-outline-primary me-1">
                                              <i class="bi bi-pencil"></i>
                                          </button>
                                          <button class="btn btn-sm btn-outline-danger">
                                              <i class="bi bi-trash"></i>
                                          </button>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td>Swine</td>
                                      <td>Duroc</td>
                                      <td>Male</td>
                                      <td>
                                          <button class="btn btn-sm btn-dark" onclick="showQRModal('QR002')">
                                              View QR
                                          </button>
                                      </td>
                                      <td>
                        <button class="action-btn btn-edit" 
                        data-bs-toggle="modal"
          data-bs-target="#addLivestockModal"
          onclick="editLivestock(${index})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn btn-delete" onclick="deleteLivestock(${item.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                                  </tr>
                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>
          </div>

          <!-- Owner 2 -->
          <div class="accordion-item mb-3 shadow-sm">
              <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                          data-bs-target="#owner2">
                      <div class="d-flex align-items-center">
                          <div class="owner-avatar me-3">M</div>
                          <div>
                              <h5 class="mb-0">Maria Santos</h5>
                              <small class="text-muted">3 livestock</small>
                          </div>
                      </div>
                  </button>
              </h2>
              <div id="owner2" class="accordion-collapse collapse" data-bs-parent="#ownersAccordion">
                  <div class="accordion-body bg-light">
                      <div class="table-responsive">
                          <table class="table table-hover bg-white">
                              <thead>
                                  <tr>
                                      <th>Species</th>
                                      <th>Breed</th>
                                      <th>Sex</th>
                                      <th>QR Code</th>
                                      <th>Actions</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <tr>
                                      <td>Cattle</td>
                                      <td>Brahman</td>
                                      <td>Female</td>
                                      <td>
                                          <button class="btn btn-sm btn-dark" onclick="showQRModal('QR003')">
                                              View QR
                                          </button>
                                      </td>
                                      <td>
                                          <button class="btn btn-sm btn-outline-primary me-1">
                                              <i class="bi bi-pencil"></i>
                                          </button>
                                          <button class="btn btn-sm btn-outline-danger">
                                              <i class="bi bi-trash"></i>
                                          </button>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td>Cattle</td>
                                      <td>Angus</td>
                                      <td>Male</td>
                                      <td>
                                          <button class="btn btn-sm btn-dark" onclick="showQRModal('QR004')">
                                              View QR
                                          </button>
                                      </td>
                                      <td>
                                          <button class="btn btn-sm btn-outline-primary me-1">
                                              <i class="bi bi-pencil"></i>
                                          </button>
                                          <button class="btn btn-sm btn-outline-danger">
                                              <i class="bi bi-trash"></i>
                                          </button>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td>Goat</td>
                                      <td>Boer</td>
                                      <td>Female</td>
                                      <td>
                                          <button class="btn btn-sm btn-dark" onclick="showQRModal('QR005')">
                                              View QR
                                          </button>
                                      </td>
                                      <td>
                                          <button class="btn btn-sm btn-outline-primary me-1">
                                              <i class="bi bi-pencil"></i>
                                          </button>
                                          <button class="btn btn-sm btn-outline-danger">
                                              <i class="bi bi-trash"></i>
                                          </button>
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>
          </div>

          <!-- Owner 3 -->
          <div class="accordion-item mb-3 shadow-sm">
              <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                          data-bs-target="#owner3">
                      <div class="d-flex align-items-center">
                          <div class="owner-avatar me-3">P</div>
                          <div>
                              <h5 class="mb-0">Pedro Reyes</h5>
                              <small class="text-muted">1 livestock</small>
                          </div>
                      </div>
                  </button>
              </h2>
              <div id="owner3" class="accordion-collapse collapse" data-bs-parent="#ownersAccordion">
                  <div class="accordion-body bg-light">
                      <div class="table-responsive">
                          <table class="table table-hover bg-white">
                              <thead>
                                  <tr>
                                      <th>Species</th>
                                      <th>Breed</th>
                                      <th>Sex</th>
                                      <th>QR Code</th>
                                      <th>Actions</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <tr>
                                      <td>Chicken</td>
                                      <td>Rhode Island Red</td>
                                      <td>Female</td>
                                      <td>
                                          <button class="btn btn-sm btn-dark" onclick="showQRModal('QR006')">
                                              View QR
                                          </button>
                                      </td>
                                      <td>
                                          <button class="btn btn-sm btn-outline-primary me-1">
                                              <i class="bi bi-pencil"></i>
                                          </button>
                                          <button class="btn btn-sm btn-outline-danger">
                                              <i class="bi bi-trash"></i>
                                          </button>
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <div class="table-container">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>OWNER NAME</th>
              <th>SPECIES</th>
              <th>BREED</th>
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
                <td colspan="6" class="text-center text-muted py-5">
                  No livestock data available. Click "Add Livestock" to get started.
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

              <div class="text-center mt-4">
                <button type="submit" class="btn btn-add px-5">
                  Add Livestock
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
<p id="ownerId" style="display:none;"></p>
<p id="ownerName" style="display:none;"></p>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    

    <script>
      document.getElementById("addLivestockForm").addEventListener("submit", async (e) => {
  e.preventDefault();

  const livestockData = {
    owner_id: document.getElementById("ownerId").textContent,
    owner_name:  document.getElementById("ownerName").textContent,
    species: document.getElementById("species").value.trim(),
    breed: document.getElementById("breed").value.trim(),
    sex: document.getElementById("sex").value,
    dob: document.getElementById("dob").value
  };

  try {
    const res = await fetch("../../helper/addLiveStock.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(livestockData)
    });

    const result = await res.json();
    console.log(result);
    
    if (result.status === "success") {
      alert("Livestock added successfully!");
      // Close modal
      const modal = bootstrap.Modal.getInstance(document.getElementById('addLivestockModal'));
      if (modal) modal.hide();
      // Reload page to show new livestock
      location.reload();
    } else {
      alert("Error: " + result.message);
    }
  } catch (err) {
    console.error("Error:", err);
    alert("Failed to add livestock. Please try again.");
  }
});
     

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
let owners = []
fetch("../../helper/getOwnerNameId.php")
  .then(e=>e.json())
  .then(e=> owners = e.data)
  .catch(e=>console.log(e))
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
            document.getElementById("ownerId").textContent = o.id
            document.getElementById("ownerName").textContent = o.ownerName
           alert(o.ownerName)
          });
          dropdown.appendChild(item);
        });

        dropdown.style.display = "block";
      });

      document.addEventListener("click", (e) => {
        if (!e.target.closest("#ownerName")) {
          dropdown.style.display = "none";
        }
      });
    </script>
    <script>
      let livestockData = [];
      let currentQRData = "";

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
       async function showLivelihood(){
          const res = await fetch("../../helper/getLiveStock.php")
          const j = await res.json();
          console.log(j)
          renderTabledata(j.data)
      }
      // Show QR Modal

      // Render table
      function renderTabledata(data) {
        const tbody = document.getElementById("livestockTableBody");
        if (data.length === 0) {
          tbody.innerHTML =
            '<tr><td colspan="7" class="text-center text-muted py-5">No livestock data available. Click "Add Livestock" to get started.</td></tr>';
          return;
        }

        tbody.innerHTML = data
          .map(
            (item, index) => `
                <tr>
                    <td>${item.firstName} ${item.middleName} ${item.surname}</td>
                    <td>${item.species}</td>
                    <td>${item.breed}</td>
                    <td>${item.sex}</td>
                    <td class="qr-code-cell">
                        ${
                          item.qr_code
                            ? `<img src="../../${item.qr_code}" class="qr-code-img" onclick="showQRModal('${item.qr_code}')" alt="QR Code">`
                            : "Generating..."
                        }
                    </td>
                    <td>
                        <button class="action-btn btn-edit" 
                        data-bs-toggle="modal"
          data-bs-target="#addLivestockModal"
          onclick="editLivestock(${index})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn btn-delete" onclick="deleteLivestock(${item.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `
          )
          .join("");
      }

      showLivelihood()
      function showQRModal(index) {
        document.getElementById("qrModalImage").src = `../../${index}`;
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
        if (confirm("Are you sure you want to delete this livestock?")) {
          const res = await fetch(`../../helper/deleteLiveStock.php?id=${id}`)
          showLivelihood();
          showAlert("Livestock deleted successfully!", "warning");
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
          renderTabledata(filtered);
          
            const accordionItems = document.querySelectorAll('.accordion-item');

            accordionItems.forEach(item => {
                const ownerName = item.querySelector('.accordion-button h5').textContent.toLowerCase();
                const tableRows = item.querySelectorAll('tbody tr');
                let hasMatch = ownerName.includes(searchTerm);

                tableRows.forEach(row => {
                    const species = row.cells[0].textContent.toLowerCase();
                    const breed = row.cells[1].textContent.toLowerCase();
                    
                    if (species.includes(searchTerm) || breed.includes(searchTerm)) {
                        hasMatch = true;
                        row.style.display = '';
                    } else if (searchTerm !== '') {
                        row.style.display = 'none';
                    } else {
                        row.style.display = '';
                    }
                });

                item.style.display = hasMatch ? '' : 'none';
            });
        });

      // Edit livestock (placeholder)
      function editLivestock(index) {}

      // Initialize
    
      
    </script>
    <script src="../../assets/scripts/sidebar.js"></script>
  </body>
</html>
