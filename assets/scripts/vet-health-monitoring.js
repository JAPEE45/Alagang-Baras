// Form submission handler
document
  .getElementById("healthRecordForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    // Get livestock ID from select
    const livestockSelect = document.getElementById("liveStockSelect");
    const livestockId = livestockSelect.value;
    
    if (!livestockId || livestockId === "--Select Livestock--") {
      alert("Please select a livestock.");
      return;
    }

    // Get form data
    const checkupDate = document.getElementById("checkupDate").value;
    const diagnosis = document.getElementById("diagnosis").value;
    const treatment = document.getElementById("treatment").value;
    const vaccine = document.getElementById("vaccine").value;
    const healthStatus = document.getElementById("healthStatus").value;

    // Validation
    if (!checkupDate) {
      alert("Please select a check-up date.");
      return;
    }

    if (!diagnosis && !treatment && !vaccine) {
      alert("Please provide at least one of: diagnosis, treatment, or vaccine.");
      return;
    }

    const data = {
      livestock_id: livestockId,
      checkup_date: checkupDate,
      diagnosis: diagnosis,
      treatment: treatment,
      vaccine_given: vaccine || "",
      livestock_status: healthStatus || "Healthy"
    };

    try {
      const response = await fetch("../../helper/addHealthRecord.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
      });

      const result = await response.json();

      if (result.status === "success") {
        alert("Health record saved successfully!");
        // Reset form
        document.getElementById("healthRecordForm").reset();
        document.getElementById("search-owner").value = "";
        document.getElementById("liveStockSelect").innerHTML = '<option class="option-control" selected disabled>--Select Livestock--</option>';
        document.getElementById("search-species").value = "";
        document.getElementById("search-breed").value = "";
        // Redirect to monitoring page
        window.location.href = './livestock-health-monitoring.php';
      } else {
        alert("Error: " + result.message);
      }
    } catch (error) {
      console.error("Error saving health record:", error);
      alert("Failed to save health record. Please try again.");
    }
  });

document.addEventListener("DOMContentLoaded", function () {
  const today = new Date().toISOString().split("T")[0];
  document.getElementById("checkupDate").value = today;
});

const inputs = document.querySelectorAll(".form-control, .form-select");
inputs.forEach((input) => {
  input.addEventListener("focus", function () {
    this.parentElement.style.transform = "scale(1.02)";
    this.parentElement.style.transition = "transform 0.2s ease";
  });

  input.addEventListener("blur", function () {
    this.parentElement.style.transform = "scale(1)";
  });
});
