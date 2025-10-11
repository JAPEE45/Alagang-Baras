
document.getElementById("submitBtn").addEventListener("click", (e) => {
  e.preventDefault();

  const formData = {
    surname: document.getElementById("surname").value,
    firstName: document.getElementById("firstName").value,
    middleName: document.getElementById("middleName").value,
    extName: document.getElementById("extName").value,
    sex: document.getElementById("sex").value,
    address: document.getElementById("address").value,
    mobileNum: document.getElementById("mobileNum").value,
    landlineNum: document.getElementById("landlineNum").value,
    birthday: document.getElementById("birthday").value,
    birthPlace: document.getElementById("birthPlace").value,
    highestFormalEducation: document.getElementById("highestFormalEducation").value,
    isPwd: document.getElementById("isPwd").value,
    religion: document.getElementById("religion").value,
    civilStatus: document.getElementById("civilStatus").value,
    spouse: document.getElementById("spouse").value,
    isBeneficiary4ps: document.getElementById("isBeneficiary4ps").value,

    isIndigenous: document.getElementById("isIndigenousYes").checked
      ? "Yes"
      : document.getElementById("isIndigenousNo").checked
      ? "No"
      : "",
    indigenousGroup: document.getElementById("indigenousGroup").value,
    motherMaiden: document.getElementById("motherMaiden").value,

    hasGovernmentId: document.getElementById("hasGovernmentIdYes").checked
      ? "Yes"
      : document.getElementById("hasGovernmentIdNo").checked
      ? "No"
      : "",
    governmentIdType: document.getElementById("governmentIdType").value,
    governmentIdNum: document.getElementById("governmentIdNum").value,

    isHouseholdHead: document.getElementById("isHouseholdHeadYes").checked
      ? "Yes"
      : document.getElementById("isHouseholdHeadNo").checked
      ? "No"
      : "",
    householdHeadName: document.querySelector('label[for="governmentIdType"] + input.condition-input')?.value || "",
    householdHeadRelationship: document.querySelector('label[for="governmentIdNum"] + input.condition-input')?.value || "",
    noOfHouseholdMembers: document.querySelectorAll("#noOfHouseholdMembers")[0]?.value || "",
    noOfMale: document.querySelectorAll("#noOfHouseholdMembers")[1]?.value || "",
    noOfFemale: document.querySelectorAll("#noOfHouseholdMembers")[2]?.value || "",

    isMemberOfFarmersAssociation: document.getElementById("isMemberOfFarmersAssociationYes").checked
      ? "Yes"
      : document.getElementById("isMemberOfFarmersAssociationNo").checked
      ? "No"
      : "",
    farmerAssociation: document.getElementById("farmerAssociation").value,

    emergencyContactPerson: document.querySelectorAll('label[for="isIndigenous"] + input.form-control')[0]?.value || "",
    emergencyContactNumber: document.querySelectorAll('label[for="isIndigenous"] + input.form-control')[1]?.value || "",

    mainLivelihood: document.getElementById("mainLivelihood").value,
    typeOfFarmingActivity: document.getElementById("typeOfFarmingActivity")?.value || "",
    specifyTypeFarm: document.getElementById("specifyTypeFarm")?.value || "",
    kindOfWork: document.getElementById("kindOfWork")?.value || "",
    specifyKindOfWork: document.getElementById("specifyKindOfWork")?.value || "",
    typeOfFishingActivity: document.getElementById("typeOfFishingActivity")?.value || "",
    specifyFishingActivity: document.getElementById("specifyFishingActivity")?.value || "",
    typeOfInvolment: document.getElementById("typeOfInvolment")?.value || "",
    specifyTypeOfInvolment: document.getElementById("specifyTypeOfInvolment")?.value || "",
    farmingIncome: document.getElementById("farmingIncome").value,
    nonFarmingIncome: document.getElementById("nonFarmingIncome").value,
  };

  // Add id for updates
  if (id) formData.id = id;

  // Decide endpoint (insert or update)
  const url = id
    ? "../../helper/updateOwner.php"
    : "../../helper/add_owner.php";

  // 🟩 Send to server
  fetch(url, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(formData),
  })
    .then((res) => res.json())
    .then((data) => {
      console.log(data);
      if (data.status === "success") {
        alert(data.message);
      } else {
        alert("Error: " + data.message);
      }
    })
    .catch((err) => console.error("Error:", err));
});
