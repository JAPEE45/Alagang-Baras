// Sample data
const livestockData = [
  {
    id: "LV-1002",
    ownerName: "Hannah Denielle Teodoro",
    species: "Goat",
    healthStatus: "Healthy",
    lastCheckup: "10-09-2025"
  }
];

let filteredData = [...livestockData];

// Initialize the application
function init() {
  renderTable(filteredData);
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
                    <td>${item.id}</td>
                    <td>${item.ownerName}</td>
                    <td>${item.species}</td>
                    <td>${item.healthStatus}</td>
                    <td>${item.lastCheckup}</td>
                `;
    tableBody.appendChild(row);
  });
}

// Filter functionality
function applyFilters() {
  const barangayFilter = document.getElementById("barangayFilter").value;
  const reportTypeFilter = document.getElementById("reportTypeFilter").value;

  filteredData = livestockData.filter((item) => {
    const matchesBarangay = !barangayFilter || item.id === barangayFilter;
    // For now, report type filter doesn't change data structure, just kept for UI consistency
    return matchesBarangay;
  });

  renderTable(filteredData);
}

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
      "id": item.id,
      "Owner Name": item.ownerName,
      "Farmers": item.species,
      "Vaccinated": item.healthStatus,
      "Pending": item.lastCheckup,
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
