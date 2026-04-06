<?php
include_once "db.php";

header('Content-Type: application/json');

// Read JSON body (sent from fetch with Content-Type: application/json)
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;

if (!$id) {
    echo json_encode(["status" => "error", "message" => "No livestock ID provided."]);
    exit;
}

try {
    // Get QR code path before deleting
    $stmt = $pdo->prepare("SELECT qr_code FROM livestock WHERE id = ?");
    $stmt->execute([$id]);
    $livestock = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$livestock) {
        echo json_encode(["status" => "error", "message" => "Livestock not found."]);
        exit;
    }

    // Delete from DB
    $deleteStmt = $pdo->prepare("DELETE FROM livestock WHERE id = ?");
    $deleteStmt->execute([$id]);

    // Delete QR code file if exists
    if (!empty($livestock['qr_code']) && file_exists($livestock['qr_code'])) {
        unlink($livestock['qr_code']);
    }

    echo json_encode(["status" => "success", "message" => "Livestock deleted successfully!"]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>