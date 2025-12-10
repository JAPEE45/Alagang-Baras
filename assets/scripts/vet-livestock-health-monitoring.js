// Load health records on page load
document.addEventListener('DOMContentLoaded', function() {
  loadHealthRecords();
});

async function loadHealthRecords() {
  try {
    const response = await fetch("../../helper/getHealthRecords.php");
    const result = await response.json();

    if (result.status === "success") {
      const tbody = document.getElementById("healthRecordsBody");
      tbody.innerHTML = "";

      if (result.data && result.data.length > 0) {
        result.data.forEach(record => {
          const ownerName = record.owner_name || `${record.firstName || ''} ${record.surname || ''}`.trim() || 'N/A';
          const formattedDate = new Date(record.createdAt).toLocaleDateString();
          
          const row = `
            <tr data-id="${record.id}">
              <td>${formattedDate}</td>
              <td>${ownerName}</td>
              <td>${record.species || 'N/A'}</td>
              <td>${record.breed || 'N/A'}</td>
              <td>${record.diagnosis || '-'}</td>
              <td>${record.treatment || '-'}</td>
              <td>${record.vaccine_given || '-'}</td>
              <td><span class="badge bg-${record.livestock_status === 'Healthy' ? 'success' : 'warning'}">${record.livestock_status || 'N/A'}</span></td>
              <td>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteHealthRecord(${record.id})">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          `;
          tbody.insertAdjacentHTML("beforeend", row);
        });
      } else {
        tbody.innerHTML = '<tr><td colspan="9" class="text-center">No health records found</td></tr>';
      }
    } else {
      console.error("Error loading health records:", result.message);
    }
  } catch (error) {
    console.error("Error fetching health records:", error);
  }
}

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
      loadHealthRecords(); // Reload the table
    } else {
      alert("Error: " + result.message);
    }
  } catch (error) {
    console.error("Error deleting health record:", error);
    alert("Failed to delete health record. Please try again.");
  }
}

// Keep existing modal functions for backwards compatibility
document.getElementById("recordDate").valueAsDate = new Date();

function addNewRecord() {
  const form = document.getElementById("addRecordForm");
  const formData = new FormData(form);

  if (!form.checkValidity()) {
    form.reportValidity();
    return;
  }

  const date = document.getElementById("recordDate").value;
  const ownerName = document.getElementById("ownerName").value;
  const animalType = document.getElementById("animalType").value;
  const vaccineGiven = document.getElementById("vaccineGiven").value;

  const formattedDate = new Date(date).toLocaleDateString("en-US", {
    month: "2-digit",
    day: "2-digit",
    year: "2-digit",
  });


  const tableBody = document.querySelector("#healthRecordsTable tbody");
  const newRow = `
                <tr class="new-row">
                    <td>${formattedDate}</td>
                    <td><span>${ownerName}</span></td>
                    <td>${animalType}</td>
                    <td>${vaccineGiven}</td>
                    <td>
                        <a href="./new-health-record.php" class="btn btn-sm btn-outline-primary me-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteRecord(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

  tableBody.insertAdjacentHTML("beforeend", newRow);

  setTimeout(() => {
    const newRowElement = document.querySelector(".new-row");
    if (newRowElement) {
      newRowElement.classList.remove("new-row");
      newRowElement.style.background = "rgba(40, 167, 69, 0.1)";
      setTimeout(() => {
        newRowElement.style.background = "";
      }, 2000);
    }
  }, 100);

  form.reset();
  document.getElementById("recordDate").valueAsDate = new Date();
  const modal = bootstrap.Modal.getInstance(
    document.getElementById("addRecordModal")
  );
  modal.hide();

  showNotification("Health record added successfully!", "success");
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

function deleteRecord(button) {
  if (confirm("Are you sure you want to delete this health record?")) {
    const row = button.closest("tr");
    row.style.animation = "fadeOut 0.3s ease-out";
    setTimeout(() => {
      row.remove();
      showNotification("Health record deleted successfully!", "danger");
    }, 300);
  }
}

function resetModal() {
  document.getElementById("addRecordModalLabel").innerHTML =
    '<i class="fas fa-plus-circle me-2"></i>Add New Health Record';
  document.querySelector("#addRecordModal .btn-primary").innerHTML =
    "Save Record";
  document
    .querySelector("#addRecordModal .btn-primary")
    .setAttribute("onclick", "addNewRecord()");
  document.getElementById("addRecordForm").reset();
  document.getElementById("recordDate").valueAsDate = new Date();
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
