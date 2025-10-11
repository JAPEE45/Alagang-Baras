<?php
include_once "db.php";

try {
    $stmt = $pdo->query("
        SELECT 
            id, 
            CONCAT(firstName, ' ', COALESCE(middleName, ''), ' ', surname, ' ', COALESCE(extName, '')) AS name
        FROM owner
        ORDER BY surname ASC
    ");

    $owners = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($owners);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
