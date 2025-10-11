<?php
include_once "db.php"; // must define $pdo = new PDO(...)

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
  echo json_encode(["status" => "error", "message" => "No data received"]);
  exit;
}

$sql = "INSERT INTO owner (
  surname, firstName, middleName, extName, sex, address, mobileNum, landlineNum, 
  birthday, birthPlace, highestFormalEducation, isPwd, religion, civilStatus, spouse, 
  isBeneficiary4ps, isIndigenous, indigenousGroup, motherMaiden, hasGovernmentId, 
  governmentIdType, governmentIdNum, isHouseholdHead, householdHeadName, householdHeadRelationship, 
  noOfHouseholdMembers, noOfMale, noOfFemale, isMemberOfFarmersAssociation, farmerAssociation, 
  emergencyContactPerson, emergencyContactNumber, mainLivelihood, typeOfFarmingActivity, 
  specifyTypeFarm, kindOfWork, specifyKindOfWork, typeOfFishingActivity, specifyFishingActivity, 
  typeOfInvolment, specifyTypeOfInvolment, farmingIncome, nonFarmingIncome
) VALUES (
  :surname, :firstName, :middleName, :extName, :sex, :address, :mobileNum, :landlineNum, 
  :birthday, :birthPlace, :highestFormalEducation, :isPwd, :religion, :civilStatus, :spouse, 
  :isBeneficiary4ps, :isIndigenous, :indigenousGroup, :motherMaiden, :hasGovernmentId, 
  :governmentIdType, :governmentIdNum, :isHouseholdHead, :householdHeadName, :householdHeadRelationship, 
  :noOfHouseholdMembers, :noOfMale, :noOfFemale, :isMemberOfFarmersAssociation, :farmerAssociation, 
  :emergencyContactPerson, :emergencyContactNumber, :mainLivelihood, :typeOfFarmingActivity, 
  :specifyTypeFarm, :kindOfWork, :specifyKindOfWork, :typeOfFishingActivity, :specifyFishingActivity, 
  :typeOfInvolment, :specifyTypeOfInvolment, :farmingIncome, :nonFarmingIncome
)";

$stmt = $pdo->prepare($sql);
$stmt->execute($data);

echo json_encode(["status" => "success", "message" => "Owner inserted successfully"]);
?>
