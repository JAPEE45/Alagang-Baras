<?php
include_once "db.php";

header("Content-Type: application/json");

try {
    $query = "
        SELECT 
            v.vet_id,
            v.prc_number,
            v.specialization,
            u.user_id,
            u.full_name,
            u.gender,
            u.contact_number,
            u.email
        FROM veterinarians v
        JOIN users u ON v.user_id = u.user_id
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $veterinarians = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $veterinarians
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>
