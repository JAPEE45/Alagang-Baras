<?php
include_once "db.php";

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
  echo json_encode(["status" => "error", "message" => "No data received"]);
  exit;
}

$livestock_id = $data["livestock_id"] ?? "";
$diagnosis = trim($data["diagnosis"] ?? "");
$treatment = trim($data["treatment"] ?? "");
$vaccine_given = trim($data["vaccine_given"] ?? "");
$livestock_status = $data["livestock_status"] ?? "Healthy";
$checkup_date = $data["checkup_date"] ?? date('Y-m-d');

if (empty($livestock_id)) {
  echo json_encode(["status" => "error", "message" => "Livestock ID is required"]);
  exit;
}

if (empty($diagnosis) && empty($treatment) && empty($vaccine_given)) {
  echo json_encode(["status" => "error", "message" => "At least one of diagnosis, treatment, or vaccine must be provided"]);
  exit;
}

try {
  // Verify livestock exists
  $check = $pdo->prepare("SELECT id FROM livestock WHERE id = ?");
  $check->execute([$livestock_id]);
  if (!$check->fetch()) {
    echo json_encode(["status" => "error", "message" => "Livestock not found"]);
    exit;
  }

  $stmt = $pdo->prepare("
    INSERT INTO healthmonitoring (livestock_id, diagnosis, treatment, vaccine_given, livestock_status, createdAt)
    VALUES (?, ?, ?, ?, ?, ?)
  ");
  
  $stmt->execute([
    $livestock_id, 
    $diagnosis, 
    $treatment, 
    $vaccine_given, 
    $livestock_status,
    $checkup_date
  ]);

  echo json_encode([
    "status" => "success",
    "message" => "Health record added successfully!",
    "id" => $pdo->lastInsertId()
  ]);

} catch (PDOException $e) {
  echo json_encode([
    "status" => "error",
    "message" => "Database error: " . $e->getMessage()
  ]);
}
?>
