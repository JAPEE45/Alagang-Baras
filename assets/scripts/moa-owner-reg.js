// Form handling

const ownerID = document.getElementById("ownerID")
const fullName = document.getElementById("fullName")
const address = document.getElementById("address")
const contactNum = document.getElementById("contactNum")
const email = document.getElementById("email")
const liveStockOwned = document.getElementById("livestockOwned")
const liveStockType = document.getElementById("livestockType")
const notesRemarks = document.getElementById("notesRemarks")
const getOwnerData = async(id)=>{
  const res = await fetch(`../../helper/getOwner.php?ownerId=${id}`)
  const j =  await res.json();
  console.log(j)
  ownerID.value = j.id
  fullName.value = j.fullname
  address.value = j.address
  contactNum.value = j.contact
  email.value = j.email
  liveStockOwned.value = j.number_of_livestock
  liveStockType.value = j.type_of_livestock
  notesRemarks.value = j.notes
}
const params = new URLSearchParams(location.search);
const id = params.get("uid");
if(id){
  getOwnerData(id)
}
document
  .getElementById("livestockForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    // Get form data
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    // Validate required fields
    const requiredFields = [
      "ownerID",
      "fullname",
      "address",
      "contact",
      "email",
      "number_of_livestock",
      "type_of_livestock",
      "notes"
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
   
    console.log("Form submitted with data:", data);
    fetch("add_owner.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    const msgDiv = document.getElementById("responseMessage");
    console.log('this is data '+data.message)
    if (data.success) {
       showMessage("Livestock profile updated successfully!", "success");
      this.reset(); // clear form
    } else {
      msgDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
    }
  })
  .catch(err => {
    console.error("Error:", err);
  });
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
