document
  .getElementById("healthRecordForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    const animalId = document.getElementById("animalId").value;
    const healthStatus = document.getElementById("healthStatus").value;
    const symptoms = document.getElementById("symptoms").value;
    const checkupDate = document.getElementById("checkupDate").value;
    const temperature = document.getElementById("temperature").value;
    const weight = document.getElementById("weight").value;

    alert(
      `Health record updated for Animal ${animalId}\nStatus: ${healthStatus}\nDate: ${checkupDate}`
    );

    bootstrap.Modal.getInstance(
      document.getElementById("updateHealthModal")
    ).hide();

    this.reset();
  });

document
  .getElementById("treatmentForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    const animalId = document.getElementById("treatmentAnimalId").value;
    const treatmentType = document.getElementById("treatmentType").value;
    const treatmentDate = document.getElementById("treatmentDate").value;
    const medication = document.getElementById("medication").value;
    const dosage = document.getElementById("dosage").value;

    alert(
      `Treatment recorded for Animal ${animalId}\nType: ${treatmentType}\nDate: ${treatmentDate}\nMedication: ${medication}`
    );

    bootstrap.Modal.getInstance(
      document.getElementById("recordTreatmentModal")
    ).hide();

    this.reset();
  });

const today = new Date().toISOString().split("T")[0];
document.getElementById("checkupDate").value = today;
document.getElementById("treatmentDate").value = today;
