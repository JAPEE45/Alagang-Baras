<?php
include_once "db.php";

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
  echo json_encode(["status" => "error", "message" => "No data received"]);
  exit;
}

$id = $data["id"] ?? "";
$diagnosis = trim($data["diagnosis"] ?? "");
$treatment = trim($data["treatment"] ?? "");
$vaccine_given = trim($data["vaccine_given"] ?? "");
$livestock_status = $data["livestock_status"] ?? "Healthy";

if (empty($id)) {
  echo json_encode(["status" => "error", "message" => "Health record ID is required"]);
  exit;
}

try {
  $stmt = $pdo->prepare("
    UPDATE healthmonitoring 
    SET diagnosis = ?, treatment = ?, vaccine_given = ?, livestock_status = ?
    WHERE id = ?
  ");
  
  $stmt->execute([$diagnosis, $treatment, $vaccine_given, $livestock_status, $id]);

  if ($stmt->rowCount() > 0) {
    echo json_encode([
      "status" => "success",
      "message" => "Health record updated successfully!"
    ]);
  } else {
    echo json_encode([
      "status" => "error",
      "message" => "No changes made or record not found"
    ]);
  }

} catch (PDOException $e) {
  echo json_encode([
    "status" => "error",
    "message" => "Database error: " . $e->getMessage()
  ]);
}
?>
