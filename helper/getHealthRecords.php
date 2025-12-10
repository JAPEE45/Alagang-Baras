<?php
include_once "db.php";

header('Content-Type: application/json');

try {
  $livestock_id = $_GET['livestock_id'] ?? null;
  $owner_id = $_GET['owner_id'] ?? null;
  
  if ($livestock_id) {
    // Get records for specific livestock
    $stmt = $pdo->prepare("
      SELECT h.*, l.owner_name, l.species, l.breed, l.sex, o.firstName, o.surname
      FROM healthmonitoring h
      JOIN livestock l ON h.livestock_id = l.id
      LEFT JOIN owner o ON l.owner_id = o.id
      WHERE h.livestock_id = ?
      ORDER BY h.createdAt DESC
    ");
    $stmt->execute([$livestock_id]);
  } elseif ($owner_id) {
    // Get records for all livestock of an owner
    $stmt = $pdo->prepare("
      SELECT h.*, l.owner_name, l.species, l.breed, l.sex, o.firstName, o.surname
      FROM healthmonitoring h
      JOIN livestock l ON h.livestock_id = l.id
      LEFT JOIN owner o ON l.owner_id = o.id
      WHERE l.owner_id = ?
      ORDER BY h.createdAt DESC
    ");
    $stmt->execute([$owner_id]);
  } else {
    // Get all health records
    $stmt = $pdo->prepare("
      SELECT h.*, l.owner_name, l.species, l.breed, l.sex, l.id as livestock_id_ref, o.firstName, o.surname
      FROM healthmonitoring h
      JOIN livestock l ON h.livestock_id = l.id
      LEFT JOIN owner o ON l.owner_id = o.id
      ORDER BY h.createdAt DESC
    ");
    $stmt->execute();
  }
  
  $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
  echo json_encode([
    "status" => "success",
    "data" => $records
  ]);

} catch (PDOException $e) {
  echo json_encode([
    "status" => "error",
    "message" => "Database error: " . $e->getMessage()
  ]);
}
?>
