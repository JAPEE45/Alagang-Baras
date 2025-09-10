// Your livestock data
const animals = [
  {
    id: "L001",
    species: "Cattle",
    owner: "Juan Cruz",
    barangay: "Paniquihan",
  },
  { id: "L002", species: "Goat", owner: "Maria S.", barangay: "Cabacab" },
];

let filteredData = [...animals];

// Initialize the application
function init() {
  populateBarangayFilter();
  renderTable(filteredData);
}

// Populate barangay filter dropdown
function populateBarangayFilter() {
  const barangayFilter = document.getElementById("barangayFilter");
  barangayFilter.innerHTML = `<option value="">All</option>`; // reset + default option

  const barangays = [...new Set(animals.map((item) => item.barangay))];

  barangays.forEach((barangay) => {
    const option = document.createElement("option");
    option.value = barangay;
    option.textContent = barangay;
    barangayFilter.appendChild(option);
  });
}

// Render table with data
function renderTable(data) {
  const tableBody = document.getElementById("animalTable");
  tableBody.innerHTML = ""; // ✅ clear old rows first

  data.forEach((animal) => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${animal.id}</td>
      <td>${animal.species}</td>
      <td>${animal.owner}</td>
      <td>${animal.barangay}</td>
      <td><div class="qr-code" id="qr-${animal.id}"></div></td>
      <td>
        <button class="action-btn download me-2" onclick="downloadQR('${animal.id}')"><i class="fa-solid fa-download"></i></button>
        <button class="action-btn print" onclick="printQR('${animal.id}')"><i class="fa-solid fa-print"></i></button>
      </td>
    `;
    tableBody.appendChild(row);

    // Generate QR code directly with JS (data is JSON)
    const qrData = JSON.stringify(animal);
    new QRCode(document.getElementById(`qr-${animal.id}`), {
      text: qrData,
      width: 128, // ✅ smaller size so it fits
      height: 128,
    });
  });
}

// Filter functionality
function applyFilters() {
  const barangayFilter = document.getElementById("barangayFilter").value;
  const speciesFilter = document.getElementById("speciesFilter").value;

  filteredData = animals.filter((item) => {
    const matchesBarangay = !barangayFilter || item.barangay === barangayFilter;
    const matchesSpecies = !speciesFilter || item.species === speciesFilter;
    return matchesBarangay && matchesSpecies;
  });
  console.log(speciesFilter);
  renderTable(filteredData);
}

// Download QR
function downloadQR(animalId) {
  const qrCanvas = document.querySelector(`#qr-${animalId} canvas`);
  if (!qrCanvas) return;
  const link = document.createElement("a");
  link.href = qrCanvas.toDataURL("image/png");
  link.download = `${animalId}_QR.png`;
  link.click();
}

// Print QR
function printQR(animalId) {
  const qrCanvas = document.querySelector(`#qr-${animalId} canvas`);
  if (!qrCanvas) return;
  const qrImage = qrCanvas.toDataURL("image/png");
  const win = window.open("");
  win.document.write(
    `<img src="${qrImage}" onload="window.print(); window.close()">`
  );
}

// Add event listeners for filters
document
  .getElementById("barangayFilter")
  .addEventListener("change", applyFilters);
document
  .getElementById("speciesFilter")
  .addEventListener("change", applyFilters);


// Initialize app on load
window.onload = init;
