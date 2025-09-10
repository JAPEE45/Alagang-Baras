// Form handling
document
  .getElementById("livestockForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    // Get form data
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    // Validate required fields
    const requiredFields = [
      "animalId",
      "species",
      "breed",
      "age",
      "sex",
      "ownerId",
      "location",
    ];
    const emptyFields = requiredFields.filter((field) => !data[field]);

    if (emptyFields.length > 0) {
      showMessage(
        "Please fill in all required fields: " + emptyFields.join(", "),
        "error"
      );
      return;
    }

    // Simulate saving data
    showMessage("Livestock profile updated successfully!", "success");
    console.log("Form submitted with data:", data);
  });

// Reset button functionality
document.getElementById("resetBtn").addEventListener("click", function (e) {
  e.preventDefault();

  // Reset all form fields
  document.getElementById("livestockForm").reset();

  // Show confirmation message
  showMessage("Form has been reset successfully!", "info");

  // Add visual feedback
  const formInputs = document.querySelectorAll(".form-control, .form-select");
  formInputs.forEach((input) => {
    input.style.transform = "scale(0.98)";
    setTimeout(() => {
      input.style.transform = "scale(1)";
    }, 150);
  });
});

// Cancel button functionality
function cancelForm() {
  if (
    confirm(
      "Are you sure you want to cancel? Any unsaved changes will be lost."
    )
  ) {
    document.getElementById("livestockForm").reset();
    showMessage("Operation cancelled.", "info");
  }
}

// Message display function
function showMessage(message, type) {
  const messageContainer = document.getElementById("message-container");
  const alertClass =
    type === "error"
      ? "alert-danger"
      : type === "success"
      ? "alert-success"
      : "alert-info";

  const alertDiv = document.createElement("div");
  alertDiv.className = `alert ${alertClass} fade-in`;
  alertDiv.innerHTML = `
                <i class="fas fa-${
                  type === "error"
                    ? "exclamation-circle"
                    : type === "success"
                    ? "check-circle"
                    : "info-circle"
                } me-2"></i>
                ${message}
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            `;

  messageContainer.appendChild(alertDiv);

  // Auto-remove after 5 seconds
  setTimeout(() => {
    if (alertDiv.parentElement) {
      alertDiv.remove();
    }
  }, 5000);
}

// Form validation with real-time feedback
document.querySelectorAll(".form-control, .form-select").forEach((input) => {
  input.addEventListener("blur", function () {
    if (this.hasAttribute("required") && !this.value) {
      this.style.borderColor = "#dc3545";
    } else {
      this.style.borderColor = "";
    }
  });
});
