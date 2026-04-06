// Sample data
const livestockData = [
  // {
  //   barangay: "Cabcab",
  //   totalLivestock: 312,
  //   farmers: 54,
  //   vaccinated: 290,
  //   pending: 22,
  // },
  {
    barangay: "Paniquihan",
    totalLivestock: 205,
    farmers: 39,
    vaccinated: 180,
    pending: 25,
  },
  // {
  //   barangay: "Malvar",
  //   totalLivestock: 445,
  //   farmers: 78,
  //   vaccinated: 420,
  //   pending: 25,
  // },
  // {
  //   barangay: "San Vicente",
  //   totalLivestock: 189,
  //   farmers: 32,
  //   vaccinated: 165,
  //   pending: 24,
  // },
  // {
  //   barangay: "Poblacion",
  //   totalLivestock: 267,
  //   farmers: 45,
  //   vaccinated: 240,
  //   pending: 27,
  // },
  // {
  //   barangay: "Santo Niño",
  //   totalLivestock: 334,
  //   farmers: 58,
  //   vaccinated: 310,
  //   pending: 24,
  // },
  // {
  //   barangay: "Biñan",
  //   totalLivestock: 198,
  //   farmers: 41,
  //   vaccinated: 175,
  //   pending: 23,
  // },
];

let filteredData = [...livestockData];

// Initialize the application
function init() {
  populateBarangayFilter();
  renderTable(filteredData);
}

// Populate barangay filter dropdown
function populateBarangayFilter() {
  const barangayFilter = document.getElementById("barangayFilter");
  const barangays = [...new Set(livestockData.map((item) => item.barangay))];

  barangays.forEach((barangay) => {
    const option = document.createElement("option");
    option.value = barangay;
    option.textContent = barangay;
    barangayFilter.appendChild(option);
  });
}

// Render table with data
function renderTable(data) {
  const tableBody = document.getElementById("tableBody");
  tableBody.innerHTML = "";

  if (data.length === 0) {
    tableBody.innerHTML =
      '<tr><td colspan="5" class="no-data">No data available</td></tr>';
    return;
  }

  data.forEach((item) => {
    const row = document.createElement("tr");
    row.innerHTML = `
                    <td>${item.barangay}</td>
                    <td>${item.totalLivestock}</td>
                    <td>${item.farmers}</td>
                    <td>${item.vaccinated}</td>
                    <td>${item.pending}</td>
                `;
    tableBody.appendChild(row);
  });
}

// Filter functionality
function applyFilters() {
  const barangayFilter = document.getElementById("barangayFilter").value;
  const reportTypeFilter = document.getElementById("reportTypeFilter").value;

  filteredData = livestockData.filter((item) => {
    const matchesBarangay = !barangayFilter || item.barangay === barangayFilter;
    // For now, report type filter doesn't change data structure, just kept for UI consistency
    return matchesBarangay;
  });

  renderTable(filteredData);
}

// Add event listeners for filters
document
  .getElementById("barangayFilter")
  .addEventListener("change", applyFilters);
document
  .getElementById("reportTypeFilter")
  .addEventListener("change", applyFilters);

// Export to PDF function
function exportToPDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();

  // Add title
  doc.setFontSize(16);
  doc.text("Livestock Report", 14, 22);

  // Add current date
  doc.setFontSize(10);
  doc.text(`Generated on: ${new Date().toLocaleDateString()}`, 14, 35);

  // Create table data
  const headers = [
    ["Barangay", "Total Livestock", "Farmers", "Vaccinated", "Pending"],
  ];
  const data = filteredData.map((item) => [
    item.barangay,
    item.totalLivestock.toString(),
    item.farmers.toString(),
    item.vaccinated.toString(),
    item.pending.toString(),
  ]);

  // Add table
  doc.autoTable({
    head: headers,
    body: data,
    startY: 45,
    theme: "grid",
    styles: {
      fontSize: 10,
      cellPadding: 5,
    },
    headStyles: {
      fillColor: [40, 167, 69],
      textColor: 255,
    },
  });

  doc.save("livestock_report.pdf");
}

// Export to Excel function
function exportToExcel() {
  const ws = XLSX.utils.json_to_sheet(
    filteredData.map((item) => ({
      Barangay: item.barangay,
      "Total Livestock": item.totalLivestock,
      Farmers: item.farmers,
      Vaccinated: item.vaccinated,
      Pending: item.pending,
    }))
  );

  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, "Livestock Report");

  // Add some styling
  const range = XLSX.utils.decode_range(ws["!ref"]);
  for (let C = range.s.c; C <= range.e.c; ++C) {
    const address = XLSX.utils.encode_col(C) + "1";
    if (!ws[address]) continue;
    ws[address].s = {
      font: { bold: true },
      fill: { fgColor: { rgb: "28A745" } },
    };
  }

  XLSX.writeFile(wb, "livestock_report.xlsx");
}

// Print function
function printReport() {
  window.print();
}

// Initialize when page loads
document.addEventListener("DOMContentLoaded", init);
