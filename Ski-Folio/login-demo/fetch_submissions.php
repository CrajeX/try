<?php
include 'config.php';

$applicantId = $_GET['applicantId']; // Get applicant ID from the request
$stmt = $pdo->prepare("SELECT * FROM submissions WHERE applicant_id = ?");
$stmt->execute([$applicantId]);
$submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($submissions);
?>
