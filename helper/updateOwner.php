<?php
include_once "db.php";
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
  echo json_encode(["status" => "error", "message" => "Missing owner ID"]);
  exit;
}

$id = $data['id'];

// Prepare all values (same structure as add_owner.php)
$values = [
  'surname' => $data['surname'],
  'firstName' => $data['firstName'],
  'middleName' => $data['middleName'],
  'extName' => $data['extName'],
  'sex' => $data['sex'],
  'address' => $data['address'],
  'mobileNum' => $data['mobileNum'],
  'landlineNum' => $data['landlineNum'],
  'birthday' => $data['birthday'],
  'birthPlace' => $data['birthPlace'],
  'highestFormalEducation' => $data['highestFormalEducation'],
  'isPwd' => $data['isPwd'],
  'religion' => $data['religion'],
  'civilStatus' => $data['civilStatus'],
  'spouse' => $data['spouse'],
  'isBeneficiary4ps' => $data['isBeneficiary4ps'],
  'isIndigenous' => $data['isIndigenous'],
  'indigenousGroup' => $data['indigenousGroup'],
  'motherMaiden' => $data['motherMaiden'],
  'hasGovernmentId' => $data['hasGovernmentId'],
  'governmentIdType' => $data['governmentIdType'],
  'governmentIdNum' => $data['governmentIdNum'],
  'isHouseholdHead' => $data['isHouseholdHead'],
  'householdHeadName' => $data['householdHeadName'],
  'householdHeadRelationship' => $data['householdHeadRelationship'],
  'noOfHouseholdMembers' => $data['noOfHouseholdMembers'],
  'noOfMale' => $data['noOfMale'],
  'noOfFemale' => $data['noOfFemale'],
  'isMemberOfFarmersAssociation' => $data['isMemberOfFarmersAssociation'],
  'farmerAssociation' => $data['farmerAssociation'],
  'emergencyContactPerson' => $data['emergencyContactPerson'],
  'emergencyContactNumber' => $data['emergencyContactNumber'],
  'mainLivelihood' => $data['mainLivelihood'],
  'typeOfFarmingActivity' => $data['typeOfFarmingActivity'],
  'specifyTypeFarm' => $data['specifyTypeFarm'],
  'kindOfWork' => $data['kindOfWork'],
  'specifyKindOfWork' => $data['specifyKindOfWork'],
  'typeOfFishingActivity' => $data['typeOfFishingActivity'],
  'specifyFishingActivity' => $data['specifyFishingActivity'],
  'typeOfInvolment' => $data['typeOfInvolment'],
  'specifyTypeOfInvolment' => $data['specifyTypeOfInvolment'],
  'farmingIncome' => $data['farmingIncome'],
  'nonFarmingIncome' => $data['nonFarmingIncome']
];

$setClause = implode(", ", array_map(fn($key) => "$key = ?", array_keys($values)));
$params = array_values($values);
$params[] = $id;

try {
  $stmt = $pdo->prepare("UPDATE owner SET $setClause WHERE id = ?");
  $stmt->execute($params);
  echo json_encode(["status" => "success", "message" => "Owner updated successfully"]);
} catch (Exception $e) {
  echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
