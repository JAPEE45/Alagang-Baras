<?php
include_once "db.php"; // adjust path to your db connection

header("Content-Type: application/json");

try {
    $query = "
        SELECT 
            l.id,
            l.owner_id,
            o.firstName,
            o.middleName,
            o.surname,
            CONCAT(o.firstName, ' ', IFNULL(o.middleName, ''), ' ', o.surname) AS owner_name,
            l.species,
            l.breed,
            l.sex,
            l.dob,
            l.created_at,
            l.updated_at,
            l.qr_code
        FROM livestock l
        JOIN owner o ON l.owner_id = o.id
        ORDER BY l.created_at DESC
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $livestock = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $livestock
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>
