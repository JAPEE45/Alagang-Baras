<?php
include_once "db.php";
header("Content-Type: application/json");

$owner_id = $_GET['owner_id'] ?? $_POST['owner_id'] ?? null;

if (!$owner_id) {
    echo json_encode(["status" => "error", "message" => "Missing owner_id"]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT 
            id,
            species,
            breed,
            created_at,
            updated_at
        FROM livestock
        WHERE owner_id = ?
        ORDER BY created_at DESC
    ");
    
    $stmt->execute([$owner_id]);
    $livestock = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($livestock)) {
        echo json_encode(["status" => "empty", "message" => "No livestock found for this owner."]);
    } else {
        echo json_encode(["status" => "success", "data" => $livestock]);
    }

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
