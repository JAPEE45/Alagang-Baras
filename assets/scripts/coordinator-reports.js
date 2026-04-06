const BASE_PATH = "/alagang-baras"; // adjust to your project folder

let reportData = [];

// ─── Load Data ───────────────────────────────────────────
async function loadReports() {
  try {
    const res  = await fetch(`${BASE_PATH}/helper/getReports.php`);
    const json = await res.json();

    if (json.status === "success") {
      reportData = json.data;
      renderTable(reportData);
    } else {
      console.error("Report error:", json.message);
    }
  } catch (err) {
    console.error("Fetch error:", err);
  }
}

// ─── Render Table ─────────────────────────────────────────
function renderTable(data) {
  const tbody = document.getElementById("tableBody");

  if (!data.length) {
    tbody.innerHTML = `<tr>
      <td colspan="8" class="text-center text-muted py-4">No records found.</td>
    </tr>`;
    return;
  }

  tbody.innerHTML = data.map(row => `
    <tr>
      <td>${row.owner_name ?? "-"}</td>
      <td>${row.species    ?? "-"}</td>
      <td>${row.breed      ?? "-"}</td>
      <td>${row.sex        ?? "-"}</td>
      <td>${row.diagnosis  ?? "-"}</td>
      <td>${row.treatment  ?? "-"}</td>
      <td>
        <span class="badge bg-${row.livestock_status === "Healthy" ? "success" : "warning"}">
          ${row.livestock_status ?? "-"}
        </span>
      </td>
      <td>${row.last_checkup ? new Date(row.last_checkup).toLocaleDateString("en-US") : "-"}</td>
    </tr>
  `).join("");
}

// ─── Export to Excel ──────────────────────────────────────
function exportToExcel() {
  const headers = [
    "Owner", "Species", "Breed", "Sex",
    "Diagnosis", "Treatment", "Health Status", "Last Check-up"
  ];

  const rows = reportData.map(row => [
    row.owner_name   ?? "-",
    row.species      ?? "-",
    row.breed        ?? "-",
    row.sex          ?? "-",
    row.diagnosis    ?? "-",
    row.treatment    ?? "-",
    row.livestock_status ?? "-",
    row.last_checkup
      ? new Date(row.last_checkup).toLocaleDateString("en-US")
      : "-"
  ]);

  const worksheet  = XLSX.utils.aoa_to_sheet([headers, ...rows]);
  const workbook   = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, "Livestock Report");
  XLSX.writeFile(workbook, "livestock_report.xlsx");
}

// ─── Print ────────────────────────────────────────────────
function printReport() {
  window.print();
}

// ─── Init ─────────────────────────────────────────────────
loadReports();