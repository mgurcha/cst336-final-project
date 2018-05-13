<?php
include 'database.php';
$conn = getDatabaseConnection();

$sql = "SELECT * FROM movies ORDER BY RAND() LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->execute();
$records = $stmt->fetch(PDO::FETCH_ASSOC);
 echo json_encode($records);
?>