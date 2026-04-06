// document
//   .getElementById("healthRecordForm")
//   .addEventListener("submit", function (e) {
//     e.preventDefault();

//     const animalId = document.getElementById("animalId").value;
//     const healthStatus = document.getElementById("healthStatus").value;
//     const symptoms = document.getElementById("symptoms").value;
//     const checkupDate = document.getElementById("checkupDate").value;
//     const temperature = document.getElementById("temperature").value;
//     const weight = document.getElementById("weight").value;

//     alert(
//       `Health record updated for Animal ${animalId}\nStatus: ${healthStatus}\nDate: ${checkupDate}`
//     );

//     bootstrap.Modal.getInstance(
//       document.getElementById("updateHealthModal")
//     ).hide();

//     this.reset();
//   });

// document
//   .getElementById("treatmentForm")
//   .addEventListener("submit", function (e) {
//     e.preventDefault();

//     const animalId = document.getElementById("treatmentAnimalId").value;
//     const treatmentType = document.getElementById("treatmentType").value;
//     const treatmentDate = document.getElementById("treatmentDate").value;
//     const medication = document.getElementById("medication").value;
//     const dosage = document.getElementById("dosage").value;

//     alert(
//       `Treatment recorded for Animal ${animalId}\nType: ${treatmentType}\nDate: ${treatmentDate}\nMedication: ${medication}`
//     );

//     bootstrap.Modal.getInstance(
//       document.getElementById("recordTreatmentModal")
//     ).hide();

//     this.reset();
//   });

// const today = new Date().toISOString().split("T")[0];
// document.getElementById("checkupDate").value = today;
// document.getElementById("treatmentDate").value = today;

// Donut Chart
const donutCtx = document.getElementById("donutChart").getContext("2d");
const donutChart = new Chart(donutCtx, {
  type: "doughnut",
  data: {
    labels: ["Healthy", "Sick"],
    datasets: [
      {
        data: [8, 2],
        backgroundColor: ["#5fc58f", "#f08080"],
        borderWidth: 0,
      },
    ],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    cutout: "70%",
    plugins: {
      legend: {
        position: "bottom",
        labels: {
          usePointStyle: true,
          pointStyle: "circle",
          padding: 20,
          font: {
            size: 14,
          },
        },
      },
    },
  },
});

// Bar Chart
// const barCtx = document.getElementById("barChart").getContext("2d");
// const barChart = new Chart(barCtx, {
//   type: "bar",
//   data: {
//     labels: ["CBS", "Poblacion", "San Jose", "Riverside"],
//     datasets: [
//       {
//         label: "Healthy",
//         data: [0.4, 0.7, 1.5, 2.5, 0.7],
//         backgroundColor: "#5fc58f",
//         borderRadius: 6,
//       },
//       {
//         label: "Sick",
//         data: [0, 0.4, 0, 0, 0],
//         backgroundColor: "#f08080",
//         borderRadius: 6,
//       },
//     ],
//   },
//   options: {
//     responsive: true,
//     maintainAspectRatio: false,
//     scales: {
//       y: {
//         beginAtZero: true,
//         max: 3,
//         ticks: {
//           stepSize: 1,
//           font: {
//             size: 12,
//           },
//         },
//         title: {
//           display: true,
//           text: "Number of Animals",
//           font: {
//             size: 12,
//           },
//         },
//       },
//       x: {
//         title: {
//           display: true,
//           text: "Barangay",
//           font: {
//             size: 12,
//           },
//         },
//         ticks: {
//           font: {
//             size: 12,
//           },
//         },
//       },
//     },
//     plugins: {
//       legend: {
//         position: "bottom",
//         labels: {
//           usePointStyle: true,
//           pointStyle: "circle",
//           padding: 20,
//           font: {
//             size: 14,
//           },
//         },
//       },
//     },
//   },
// });
