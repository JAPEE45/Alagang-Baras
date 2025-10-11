<?php
session_start();
require_once '../../config/db.php'; 

$ownerSuccess = '';
$ownerError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $name = $_POST['name'];
  $species = $_POST['species'];
  $breed = $_POST['breed'];
  $age = $_POST['age'];
  $sex = $_POST['sex'];
  $owner = $_POST['owner'];
  echo "<h1>this is the name ".$name>"</h1>";

  if (empty($name) || empty($species) || empty($breed) || empty($sex)) {
    $ownerError = "Please fill in all required fields.";
  } else {
    try {
      $stmt = $pdo->prepare("
        INSERT INTO animals (name, species, breed, sex, owner,age)
        VALUES (?, ?, ?, ?, ?, ?)
      ");
      $stmt->execute([
       $name,
       $species,
       $breed,
       $sex,
       $owner,
       $age
      ]);
      $ownerSuccess = "Owner added successfully!";
      header("Location: livestock-profiling-list.php");
      exit();
    } catch (PDOException $e) {
      $ownerError = "Error: " . $e->getMessage();
      echo "Error: " . $e->getMessage();
    }
  }
}
?>

