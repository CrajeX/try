<?php
include 'config.php';

$email = $_GET['email']; // Get email from the request
$stmt = $pdo->prepare("SELECT * FROM applicants WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($user);
?>
