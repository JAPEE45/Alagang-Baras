<?php
include_once "db.php";

$ownerId = $_GET['ownerId'] ?? null;

if (!$ownerId) {
    echo json_encode(['error' => 'Missing ownerId']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM owners WHERE id = ? LIMIT 1");
$stmt->execute([$ownerId]);
$r = $stmt->fetch(PDO::FETCH_ASSOC);

if ($r) {
    echo json_encode($r);
} else {
    echo json_encode(['error' => true]);
}

$pdo = null; // optional cleanup
?>
