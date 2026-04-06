<?php
include_once '../config/db.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("
        SELECT 
            CONCAT(o.firstName, ' ', IFNULL(o.middleName, ''), ' ', o.surname) AS owner_name,
            l.species,
            l.breed,
            l.sex,
            h.diagnosis,
            h.treatment,
            h.livestock_status,
            h.createdAt AS last_checkup
        FROM owner o
        LEFT JOIN livestock l ON l.owner_id = o.id
        LEFT JOIN healthmonitoring h ON h.livestock_id = l.id
        ORDER BY h.createdAt DESC
    ");

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'data' => $data]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>