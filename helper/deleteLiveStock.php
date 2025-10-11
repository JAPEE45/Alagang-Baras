<?php
include_once "db.php";

if (!isset($_GET['id'])) {
    echo json_encode(["message" => "No livestock ID provided."]);
    exit;
}

$id = $_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT qr_code FROM livestock WHERE id = ?");
    $stmt->execute([$id]);
    $livestock = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$livestock) {
        echo json_encode(["message" => "Livestock not found."]);
        exit;
    }

    $deleteStmt = $pdo->prepare("DELETE FROM livestock WHERE id = ?");
    $deleteStmt->execute([$id]);

    if (!empty($livestock['qr_code']) && file_exists($livestock['qr_code'])) {
        unlink($livestock['qr_code']);
    }

    echo json_encode(["message" => "Livestock deleted successfully!"]);
} catch (PDOException $e) {
    echo json_encode(["message" => "Database error: " . $e->getMessage()]);
}
?>
