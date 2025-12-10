<?php
include_once "db.php";

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['id'])) {
  echo json_encode(["status" => "error", "message" => "Health record ID is required"]);
  exit;
}

$id = $data['id'];

try {
  $stmt = $pdo->prepare("DELETE FROM healthmonitoring WHERE id = ?");
  $stmt->execute([$id]);

  if ($stmt->rowCount() > 0) {
    echo json_encode([
      "status" => "success",
      "message" => "Health record deleted successfully"
    ]);
  } else {
    echo json_encode([
      "status" => "error",
      "message" => "Health record not found"
    ]);
  }

} catch (PDOException $e) {
  echo json_encode([
    "status" => "error",
    "message" => "Database error: " . $e->getMessage()
  ]);
}
?>
