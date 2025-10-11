<?php
include_once "db.php";
header("Content-Type: application/json");

try {
    $stmt = $pdo->query("
        SELECT 
            id,
            CONCAT_WS(' ', surname, firstName, middleName, extName) AS ownerName
        FROM owner
        ORDER BY surname ASC
    ");
    
    $owners = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["status" => "success", "data" => $owners]);

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
