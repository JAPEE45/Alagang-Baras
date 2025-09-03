// Form submission handler
document
  .getElementById("healthRecordForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    // Get form data
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    // Validation
    if (!data.checkupDate) {
      alert("Please select a check-up date.");
      return;
    }

    // Show success message
    alert("Health record saved successfully!");

    // In a real application, you would send this data to your backend
    console.log("Health Record Data:", data);

    // window.location.href = 'dashboard.html';
  });

function goBack() {
  if (
    confirm(
      "Are you sure you want to cancel? Any unsaved changes will be lost."
    )
  ) {
    window.history.back();
  }
}

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
