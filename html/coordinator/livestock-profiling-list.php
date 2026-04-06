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
      <!-- Remove the static accordion and table-container, replace with: -->
      <div class="accordion" id="ownersAccordion"></div>
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

<input type="text" class="form-control" id="ownerNameInput"
       placeholder="Search owner..." required hidden/>

<!-- REMOVE the two hidden <p> tags and replace with a single hidden input: -->
<input type="hidden" id="ownerIdHidden" value="" />
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
  // ─── Constants ───────────────────────────────────────────
  const BASE_PATH = "/alagang-baras"; // change to match your project folder name

  // ─── State ───────────────────────────────────────────────
  let allLivestockData = [];

  // ─── Owner Search Dropdown ───────────────────────────────
  const ownerInput   = document.getElementById("ownerNameInput");
  const ownerIdEl    = document.getElementById("ownerIdHidden");
  const dropdown     = document.getElementById("ownerDropdown");

  Object.assign(dropdown.style, {
    position: "absolute", background: "white",
    border: "1px solid #ccc", width: "100%",
    maxHeight: "180px", overflowY: "auto",
    zIndex: "1000", display: "none"
  });

  let owners = [];
  fetch(`${BASE_PATH}/helper/getOwnerNameId.php`)
    .then(r => r.json())
    .then(r => owners = r.data)
    .catch(e => console.error("Owner fetch error:", e));

  ownerInput.addEventListener("input", function () {
    const query = this.value.toLowerCase().trim();
    dropdown.innerHTML = "";
    if (!query) { dropdown.style.display = "none"; return; }

    const filtered = owners.filter(o => o.ownerName.toLowerCase().includes(query));
    if (!filtered.length) { dropdown.style.display = "none"; return; }

    filtered.forEach(o => {
      const item = document.createElement("div");
      item.textContent = o.ownerName;
      item.className = "p-2";
      item.style.cursor = "pointer";
      item.addEventListener("mouseenter", () => item.style.background = "#f1f1f1");
      item.addEventListener("mouseleave", () => item.style.background = "white");
      item.addEventListener("click", () => {
        ownerInput.value   = o.ownerName;
        ownerIdEl.value    = o.id;
        dropdown.style.display = "none";
      });
      dropdown.appendChild(item);
    });
    dropdown.style.display = "block";
  });

  document.addEventListener("click", e => {
    if (!e.target.closest("#ownerNameInput")) dropdown.style.display = "none";
  });

  // ─── Form Submit ─────────────────────────────────────────
  document.getElementById("addLivestockForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const ownerId = ownerIdEl.value;
    if (!ownerId) { alert("Please select a valid owner from the dropdown."); return; }

    const payload = {
      owner_id:   ownerId,
      owner_name: ownerInput.value.trim(),
      species:    document.getElementById("species").value.trim(),
      breed:      document.getElementById("breed").value.trim(),
      sex:        document.getElementById("sex").value,
      dob:        document.getElementById("dob").value
    };

    try {
      const res    = await fetch(`${BASE_PATH}/helper/addLiveStock.php`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
      });
      const result = await res.json();

      if (result.status === "success") {
        alert("Livestock added successfully!");
        bootstrap.Modal.getInstance(document.getElementById("addLivestockModal"))?.hide();
        await loadLivestock();
      } else {
        alert("Error: " + result.message);
      }
    } catch (err) {
      console.error(err);
      alert("Failed to add livestock. Please try again.");
    }
  });

  // ─── Load & Render Livestock ──────────────────────────────
  async function loadLivestock() {
    try {
      const res  = await fetch(`${BASE_PATH}/helper/getLiveStock.php`);
      const json = await res.json();
      if (json.status === "success") {
        allLivestockData = json.data;
        renderTable(allLivestockData);
      }
    } catch (err) {
      console.error("Load livestock error:", err);
    }
  }

  function renderTable(data) {
  const accordion = document.getElementById("ownersAccordion");

  if (!data.length) {
    accordion.innerHTML = `<div class="text-center text-muted py-5">
      No livestock data available. Click "Add Livestock" to get started.
    </div>`;
    return;
  }

  // Group livestock by owner
  const grouped = {};
  data.forEach(item => {
    const key = item.owner_id;
    if (!grouped[key]) {
      grouped[key] = {
        owner_id:   item.owner_id,
        owner_name: item.owner_name ?? `${item.firstName} ${item.middleName ?? ""} ${item.surname}`.trim(),
        livestock:  []
      };
    }
    grouped[key].livestock.push(item);
  });

  accordion.innerHTML = Object.values(grouped).map(owner => {
    const initial = owner.owner_name.charAt(0).toUpperCase();
    const collapseId = `owner_${owner.owner_id}`;

    const rows = owner.livestock.map((item, index) => {
      // find real index in allLivestockData for edit
      const realIndex = allLivestockData.findIndex(d => d.id === item.id);

      const qrCell = item.qr_code
        ? `<img src="${BASE_PATH}/${item.qr_code}"
               style="width:45px;height:45px;cursor:pointer;"
               onclick="showQRModal('${item.qr_code}')"
               alt="QR Code">`
        : `<span class="text-muted">No QR</span>`;

      return `
        <tr data-id="${item.id}">
          <td>${item.species}</td>
          <td>${item.breed}</td>
          <td>${item.sex}</td>
          <td>${qrCell}</td>
          <td>
            <button class="btn btn-sm btn-outline-primary me-1"
              onclick="editLivestock(${realIndex})"
              data-bs-toggle="modal"
              data-bs-target="#addLivestockModal">
              <i class="fas fa-edit"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger"
              onclick="deleteLivestock(${item.id})">
              <i class="fas fa-trash"></i>
            </button>
          </td>
        </tr>`;
    }).join("");

    return `
      <div class="accordion-item mb-3 shadow-sm">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#${collapseId}">
            <div class="d-flex align-items-center">
              <div class="owner-avatar me-3">${initial}</div>
              <div>
                <h5 class="mb-0">${owner.owner_name}</h5>
                <small class="text-muted">${owner.livestock.length} livestock</small>
              </div>
            </div>
          </button>
        </h2>
        <div id="${collapseId}" class="accordion-collapse collapse">
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
                <tbody>${rows}</tbody>
              </table>
            </div>
          </div>
        </div>
      </div>`;
  }).join("");
}

  // ─── QR Modal ────────────────────────────────────────────
  let currentQRSrc = "";
  function showQRModal(qrPath) {
    currentQRSrc = `${BASE_PATH}/${qrPath}`;
    document.getElementById("qrModalImage").src = currentQRSrc;
    new bootstrap.Modal(document.getElementById("qrModal")).show();
  }

  function downloadQR() {
    const link = document.createElement("a");
    link.download = "livestock_qr_code.png";
    link.href = currentQRSrc;
    link.click();
  }

  // ─── Delete ───────────────────────────────────────────────
  async function deleteLivestock(id) {
    if (!confirm("Are you sure you want to delete this livestock?")) return;
    try {
      await fetch(`${BASE_PATH}/helper/deleteLiveStock.php?id=${id}`);
      await loadLivestock();
    } catch (err) {
      console.error("Delete error:", err);
    }
  }

  // ─── Edit (placeholder) ───────────────────────────────────
  function editLivestock(index) {
    const item = allLivestockData[index];
    if (!item) return;
    // populate modal fields here if needed
    console.log("Edit:", item);
  }

  // ─── Search ───────────────────────────────────────────────
  document.getElementById("searchInput").addEventListener("input", function () {
    const term = this.value.toLowerCase();
    const filtered = allLivestockData.filter(item =>
      (item.owner_name ?? "").toLowerCase().includes(term) ||
      item.species.toLowerCase().includes(term) ||
      item.breed.toLowerCase().includes(term) ||
      item.sex.toLowerCase().includes(term)
    );
    renderTable(filtered);
  });

  // ─── Init ─────────────────────────────────────────────────
  loadLivestock();
</script>
    <script src="../../assets/scripts/sidebar.js"></script>
  </body>
</html>
