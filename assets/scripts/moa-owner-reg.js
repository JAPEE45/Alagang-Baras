// Get owner ID from URL for editing
const urlParams = new URLSearchParams(window.location.search);
const id = urlParams.get('uid');

// Load owner data if editing
if (id) {
  loadOwnerData(id);
}

async function loadOwnerData(ownerId) {
  try {
    const response = await fetch(`../../helper/getOwner.php?id=${ownerId}`);
    const result = await response.json();
    
    if (result.status === 'success' && result.data) {
      const owner = result.data;
      
      // Fill in all form fields
      document.getElementById("surname").value = owner.surname || '';
      document.getElementById("firstName").value = owner.firstName || '';
      document.getElementById("middleName").value = owner.middleName || '';
      document.getElementById("extName").value = owner.extName || '';
      document.getElementById("sex").value = owner.sex || '';
      document.getElementById("address").value = owner.address || '';
      document.getElementById("mobileNum").value = owner.mobileNum || '';
      document.getElementById("landlineNum").value = owner.landlineNum || '';
      document.getElementById("birthday").value = owner.birthday || '';
      document.getElementById("birthPlace").value = owner.birthPlace || '';
      document.getElementById("highestFormalEducation").value = owner.highestFormalEducation || '';
      document.getElementById("isPwd").value = owner.isPwd || '';
      document.getElementById("religion").value = owner.religion || '';
      document.getElementById("civilStatus").value = owner.civilStatus || '';
      document.getElementById("spouse").value = owner.spouse || '';
      document.getElementById("isBeneficiary4ps").value = owner.isBeneficiary4ps || '';
      
      // Handle radio buttons for isIndigenous
      if (owner.isIndigenous === 'Yes') {
        document.getElementById("isIndigenousYes").checked = true;
      } else if (owner.isIndigenous === 'No') {
        document.getElementById("isIndigenousNo").checked = true;
      }
      document.getElementById("indigenousGroup").value = owner.indigenousGroup || '';
      
      document.getElementById("motherMaiden").value = owner.motherMaiden || '';
      
      // Handle radio buttons for hasGovernmentId
      if (owner.hasGovernmentId === 'Yes') {
        document.getElementById("hasGovernmentIdYes").checked = true;
      } else if (owner.hasGovernmentId === 'No') {
        document.getElementById("hasGovernmentIdNo").checked = true;
      }
      document.getElementById("governmentIdType").value = owner.governmentIdType || '';
      document.getElementById("governmentIdNum").value = owner.governmentIdNum || '';
      
      // Handle radio buttons for isHouseholdHead
      if (owner.isHouseholdHead === 'Yes') {
        document.getElementById("isHouseholdHeadYes").checked = true;
      } else if (owner.isHouseholdHead === 'No') {
        document.getElementById("isHouseholdHeadNo").checked = true;
      }
      
      // Handle radio buttons for isMemberOfFarmersAssociation
      if (owner.isMemberOfFarmersAssociation === 'Yes') {
        document.getElementById("isMemberOfFarmersAssociationYes").checked = true;
      } else if (owner.isMemberOfFarmersAssociation === 'No') {
        document.getElementById("isMemberOfFarmersAssociationNo").checked = true;
      }
      document.getElementById("farmerAssociation").value = owner.farmerAssociation || '';
      
      document.getElementById("mainLivelihood").value = owner.mainLivelihood || '';
      
      // Trigger change event to show conditional fields
      document.getElementById("mainLivelihood").dispatchEvent(new Event('change'));
      
      if (document.getElementById("typeOfFarmingActivity")) {
        document.getElementById("typeOfFarmingActivity").value = owner.typeOfFarmingActivity || '';
      }
      if (document.getElementById("specifyTypeFarm")) {
        document.getElementById("specifyTypeFarm").value = owner.specifyTypeFarm || '';
      }
      if (document.getElementById("kindOfWork")) {
        document.getElementById("kindOfWork").value = owner.kindOfWork || '';
      }
      if (document.getElementById("specifyKindOfWork")) {
        document.getElementById("specifyKindOfWork").value = owner.specifyKindOfWork || '';
      }
      if (document.getElementById("typeOfFishingActivity")) {
        document.getElementById("typeOfFishingActivity").value = owner.typeOfFishingActivity || '';
      }
      if (document.getElementById("typeOfInvolment")) {
        document.getElementById("typeOfInvolment").value = owner.typeOfInvolment || '';
      }
      if (document.getElementById("specifyTypeOfInvolment")) {
        document.getElementById("specifyTypeOfInvolment").value = owner.specifyTypeOfInvolment || '';
      }
      
      document.getElementById("farmingIncome").value = owner.farmingIncome || '';
      document.getElementById("nonFarmingIncome").value = owner.nonFarmingIncome || '';
    }
  } catch (error) {
    console.error('Error loading owner data:', error);
    alert('Failed to load owner data');
  }
}

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
