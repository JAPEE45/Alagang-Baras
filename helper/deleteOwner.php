<?php
    include_once 'db.php';
    $ownerId = $_GET['ownerId'];
    $stmt = $pdo->prepare("DELETE FROM owners WHERE id = ?");
    $stmt->execute([$ownerId]);
    echo json_encode(['success'=>true]);
?>