<?php
require_once '../../config/db.php'; 
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $number_of_livestock = (int) ($_POST['number_of_livestock'] ?? 0);
    $type_of_livestock = (int) ($_POST['type_of_livestock'] ?? 0);
    $notes = trim($_POST['notes'] ?? '');

    if (empty($fullname) || empty($address) || empty($contact) || empty($email)) {
        echo json_encode(["success" => false, "message" => "Please fill in all required fields."]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO owners (fullname, address, contact, email, number_of_livestock, type_of_livestock, notes)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $fullname,
            $address,
            $contact,
            $email,
            $number_of_livestock,
            $type_of_livestock,
            $notes
        ]);

        echo json_encode(["success" => true, "message" => "Owner added successfully!"]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}
