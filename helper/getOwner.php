<?php
include_once "db.php"; // adjust path to your db.php

header('Content-Type: application/json');

if (!isset($_GET['uid'])) {
    echo json_encode(["error" => "Missing owner ID"]);
    exit;
}

$uid = $_GET['uid'];

try {
    $stmt = $pdo->prepare("SELECT * FROM owner WHERE id = ?");
    $stmt->execute([$uid]);
    $owner = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($owner) {
        echo json_encode($owner);
    } else {
        echo json_encode(["error" => "No owner found"]);
    }
} catch (PDOException $e) {
    echo json_encode(["errors" => $e->getMessage()]);
}
