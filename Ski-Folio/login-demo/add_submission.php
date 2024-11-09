<?php
include 'config.php';

$applicantId = $_POST['applicantId'];  // Get applicant ID from request
$liveDemoLink = $_POST['liveDemoLink'];
$htmlScore = $_POST['htmlScore'];  // Scores should be passed from frontend
$cssScore = $_POST['cssScore'];
$jsScore = $_POST['jsScore'];
$percentage = $_POST['percentage'];  // Percentage should be calculated based on scores

$stmt = $pdo->prepare("INSERT INTO submissions (applicant_id, liveDemoLink, htmlScore, cssScore, jsScore, percentage) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([$applicantId, $liveDemoLink, $htmlScore, $cssScore, $jsScore, $percentage]);

echo json_encode(["status" => "success"]);
?>
