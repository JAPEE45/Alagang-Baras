<?php
include_once "db.php";
require_once "phpqrcode/qrlib.php"; 

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
  echo json_encode(["status" => "error", "message" => "No data received"]);
  exit;
}

$owner_id = $data["owner_id"] ?? "";
$owner_name = $data["owner_name"] ?? "";
$species = $data["species"] ?? "";
$breed = $data["breed"] ?? "";
$sex = $data["sex"] ?? "";
$dob = $data["dob"] ?? "";

if (empty($owner_id) || empty($owner_name) || empty($species) || empty($breed) || empty($sex) || empty($dob)) {
  echo json_encode(["status" => "error", "message" => "Please fill in all fields"]);
  exit;
}

try {
  $stmt = $pdo->prepare("
    INSERT INTO livestock (owner_id, owner_name, species, breed, sex, dob)
    VALUES (?, ?, ?, ?, ?, ?)
  ");
  $stmt->execute([$owner_id, $owner_name, $species, $breed, $sex, $dob]);
  $livestock_id = $pdo->lastInsertId();
  $qrData = "Livestock ID: $livestock_id\nOwner: $owner_name\nSpecies: $species\nBreed: $breed\nSex: $sex\nDate of Birth: $dob";
  $qrDir = __DIR__ . "/../uploads/qrcodes/";
  if (!file_exists($qrDir)) {
    mkdir($qrDir, 0777, true);
  }

  $fileName = "livestock_" . $livestock_id . ".png";
  $filePath = $qrDir . $fileName;

  QRcode::png($qrData, $filePath, QR_ECLEVEL_L, 5);

  $relativePath = "uploads/qrcodes/" . $fileName;
  $update = $pdo->prepare("UPDATE livestock SET qr_code = ? WHERE id = ?");
  $update->execute([$relativePath, $livestock_id]);

  echo json_encode([
    "status" => "success",
    "message" => "Livestock added successfully!",
    "qr_code" => $relativePath
  ]);

} catch (PDOException $e) {
  echo json_encode([
    "status" => "error",
    "message" => "Database error: " . $e->getMessage()
  ]);
}
?>
